<?php

declare(strict_types=1);

function hr_db_path(): string
{
    return __DIR__ . '/../data/hr.sqlite';
}

function hr_db(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $dbPath = hr_db_path();
    $dbDir = dirname($dbPath);

    if (!is_dir($dbDir)) {
        mkdir($dbDir, 0777, true);
    }

    $pdo = new PDO('sqlite:' . $dbPath, null, null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    $pdo->exec('PRAGMA foreign_keys = ON');

    hr_bootstrap($pdo);

    return $pdo;
}

function hr_bootstrap(PDO $pdo): void
{
    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS employees (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            full_name TEXT NOT NULL,
            email TEXT NOT NULL UNIQUE,
            department TEXT NOT NULL,
            position TEXT NOT NULL,
            status TEXT NOT NULL DEFAULT "aktif",
            joined_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
        )'
    );

    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS activity_log (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            actor TEXT NOT NULL,
            action TEXT NOT NULL,
            detail TEXT NOT NULL,
            created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
        )'
    );

    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS leave_requests (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            employee_id INTEGER NOT NULL,
            type TEXT NOT NULL,
            start_date TEXT NOT NULL,
            end_date TEXT NOT NULL,
            reason TEXT NOT NULL,
            status TEXT NOT NULL DEFAULT "pending",
            created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE
        )'
    );

    $employeeCount = (int) $pdo->query('SELECT COUNT(*) FROM employees')->fetchColumn();

    if ($employeeCount === 0) {
        $seedEmployees = [
            ['Ayu Lestari', 'ayu.lestari@dummyhr.local', 'Human Capital', 'HR Officer', 'aktif'],
            ['Bima Pratama', 'bima.pratama@dummyhr.local', 'Finance', 'Payroll Specialist', 'aktif'],
            ['Sinta Maharani', 'sinta.maharani@dummyhr.local', 'IT', 'Frontend Developer', 'aktif'],
            ['Raka Saputra', 'raka.saputra@dummyhr.local', 'Operations', 'Supervisor', 'nonaktif'],
        ];

        $statement = $pdo->prepare(
            'INSERT INTO employees (full_name, email, department, position, status) VALUES (:full_name, :email, :department, :position, :status)'
        );

        foreach ($seedEmployees as $employee) {
            $statement->execute([
                ':full_name' => $employee[0],
                ':email' => $employee[1],
                ':department' => $employee[2],
                ':position' => $employee[3],
                ':status' => $employee[4],
            ]);
        }

        $leaveSeed = [
            [1, 'Tahunan', '2026-07-08', '2026-07-10', 'Cuti keluarga', 'pending'],
            [2, 'Sakit', '2026-07-02', '2026-07-03', 'Istirahat karena demam', 'disetujui'],
            [3, 'Tahunan', '2026-07-15', '2026-07-17', 'Liburan singkat', 'pending'],
        ];

        $leaveStatement = $pdo->prepare(
            'INSERT INTO leave_requests (employee_id, type, start_date, end_date, reason, status) VALUES (:employee_id, :type, :start_date, :end_date, :reason, :status)'
        );

        foreach ($leaveSeed as $leave) {
            $leaveStatement->execute([
                ':employee_id' => $leave[0],
                ':type' => $leave[1],
                ':start_date' => $leave[2],
                ':end_date' => $leave[3],
                ':reason' => $leave[4],
                ':status' => $leave[5],
            ]);
        }

        $activityStatement = $pdo->prepare(
            'INSERT INTO activity_log (actor, action, detail) VALUES (:actor, :action, :detail)'
        );

        $activitySeed = [
            ['System', 'seed', 'Database awal berhasil dibuat'],
            ['HR Admin', 'employee_created', '4 karyawan contoh ditambahkan'],
            ['HR Admin', 'leave_created', 'Data cuti contoh ditambahkan'],
        ];

        foreach ($activitySeed as $activity) {
            $activityStatement->execute([
                ':actor' => $activity[0],
                ':action' => $activity[1],
                ':detail' => $activity[2],
            ]);
        }
    }
}

function hr_json_input(): array
{
    $rawInput = file_get_contents('php://input') ?: '';
    $decoded = json_decode($rawInput, true);

    if (is_array($decoded)) {
        return $decoded;
    }

    return $_POST;
}

function hr_send_json(array $payload, int $statusCode = 200): void
{
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function hr_fail(string $message, int $statusCode = 400): void
{
    hr_send_json([
        'ok' => false,
        'message' => $message,
    ], $statusCode);
}

function hr_string_value(array $data, string $key, string $fallback = ''): string
{
    $value = $data[$key] ?? $fallback;

    if (!is_string($value)) {
        return $fallback;
    }

    return trim($value);
}

function hr_log_activity(PDO $pdo, string $actor, string $action, string $detail): void
{
    $statement = $pdo->prepare(
        'INSERT INTO activity_log (actor, action, detail) VALUES (:actor, :action, :detail)'
    );

    $statement->execute([
        ':actor' => $actor,
        ':action' => $action,
        ':detail' => $detail,
    ]);
}
