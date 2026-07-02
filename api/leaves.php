<?php

declare(strict_types=1);

require_once __DIR__ . '/db.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

$pdo = hr_db();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $leaveRequests = $pdo->query(
        'SELECT lr.id, lr.employee_id, e.full_name AS employee_name, lr.type, lr.start_date, lr.end_date, lr.reason, lr.status, lr.created_at
         FROM leave_requests lr
         JOIN employees e ON e.id = lr.employee_id
         ORDER BY lr.id DESC'
    )->fetchAll();

    hr_send_json([
        'ok' => true,
        'data' => $leaveRequests,
    ]);
}

if ($method === 'POST') {
    $input = hr_json_input();
    $id = isset($input['id']) ? (int) $input['id'] : 0;
    $action = hr_string_value($input, 'action');
    $employeeId = isset($input['employee_id']) ? (int) $input['employee_id'] : 0;
    $type = hr_string_value($input, 'type');
    $startDate = hr_string_value($input, 'start_date');
    $endDate = hr_string_value($input, 'end_date');
    $reason = hr_string_value($input, 'reason');
    $status = hr_string_value($input, 'status', 'pending');

    if ($id > 0 && $action === 'delete') {
        $leaveStatement = $pdo->prepare(
            'SELECT lr.id, e.full_name AS employee_name
             FROM leave_requests lr
             JOIN employees e ON e.id = lr.employee_id
             WHERE lr.id = :id'
        );
        $leaveStatement->execute([':id' => $id]);
        $leaveData = $leaveStatement->fetch();

        if (!$leaveData) {
            hr_fail('Data cuti tidak ditemukan.', 404);
        }

        $deleteStatement = $pdo->prepare('DELETE FROM leave_requests WHERE id = :id');
        $deleteStatement->execute([':id' => $id]);

        if (isset($leaveData['employee_name']) && is_string($leaveData['employee_name'])) {
            hr_log_activity($pdo, 'HR Admin', 'leave_deleted', $leaveData['employee_name'] . ' - data cuti dihapus');
        }

        hr_send_json([
            'ok' => true,
            'message' => 'Data cuti berhasil dihapus.',
        ]);
    }

    if ($id > 0 && $status !== '') {
        $leaveStatement = $pdo->prepare(
            'SELECT lr.id, e.full_name AS employee_name
             FROM leave_requests lr
             JOIN employees e ON e.id = lr.employee_id
             WHERE lr.id = :id'
        );
        $leaveStatement->execute([':id' => $id]);
        $leaveData = $leaveStatement->fetch();

        $statement = $pdo->prepare('UPDATE leave_requests SET status = :status WHERE id = :id');
        $statement->execute([
            ':status' => $status,
            ':id' => $id,
        ]);

        if (is_array($leaveData) && isset($leaveData['employee_name']) && is_string($leaveData['employee_name'])) {
            hr_log_activity($pdo, 'HR Admin', 'leave_updated', $leaveData['employee_name'] . ' - status cuti diubah ke ' . $status);
        }

        hr_send_json([
            'ok' => true,
            'message' => 'Status cuti diperbarui.',
        ]);
    }

    if ($employeeId <= 0 || $type === '' || $startDate === '' || $endDate === '' || $reason === '') {
        hr_fail('Data cuti belum lengkap.');
    }

    $statement = $pdo->prepare(
        'INSERT INTO leave_requests (employee_id, type, start_date, end_date, reason, status) VALUES (:employee_id, :type, :start_date, :end_date, :reason, :status)'
    );
    $statement->execute([
        ':employee_id' => $employeeId,
        ':type' => $type,
        ':start_date' => $startDate,
        ':end_date' => $endDate,
        ':reason' => $reason,
        ':status' => $status === '' ? 'pending' : $status,
    ]);

    $employeeNameStatement = $pdo->prepare('SELECT full_name FROM employees WHERE id = :id');
    $employeeNameStatement->execute([':id' => $employeeId]);
    $employeeName = $employeeNameStatement->fetchColumn();

    if (is_string($employeeName) && $employeeName !== '') {
        hr_log_activity($pdo, 'HR Admin', 'leave_created', $employeeName . ' mengajukan cuti ' . $type);
    }

    hr_send_json([
        'ok' => true,
        'message' => 'Permintaan cuti berhasil dibuat.',
    ], 201);
}

hr_fail('Method tidak didukung.', 405);
