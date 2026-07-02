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
    $employees = $pdo->query(
        'SELECT id, full_name, email, department, position, status, joined_at FROM employees ORDER BY id DESC'
    )->fetchAll();

    hr_send_json([
        'ok' => true,
        'data' => $employees,
    ]);
}

if ($method === 'POST') {
    $input = hr_json_input();
    $id = isset($input['id']) ? (int) $input['id'] : 0;
    $action = hr_string_value($input, 'action');
    $fullName = hr_string_value($input, 'full_name');
    $email = hr_string_value($input, 'email');
    $department = hr_string_value($input, 'department');
    $position = hr_string_value($input, 'position');
    $status = hr_string_value($input, 'status', 'aktif');

    if ($id > 0 && $action === 'delete') {
        $employeeStatement = $pdo->prepare('SELECT full_name FROM employees WHERE id = :id');
        $employeeStatement->execute([':id' => $id]);
        $employeeName = $employeeStatement->fetchColumn();

        if (!is_string($employeeName) || $employeeName === '') {
            hr_fail('Karyawan tidak ditemukan.', 404);
        }

        $deleteStatement = $pdo->prepare('DELETE FROM employees WHERE id = :id');
        $deleteStatement->execute([':id' => $id]);

        hr_log_activity($pdo, 'HR Admin', 'employee_deleted', $employeeName . ' dihapus dari sistem');

        hr_send_json([
            'ok' => true,
            'message' => 'Karyawan berhasil dihapus.',
        ]);
    }

    if ($id > 0) {
        $employeeStatement = $pdo->prepare('SELECT full_name, email, department, position, status FROM employees WHERE id = :id');
        $employeeStatement->execute([':id' => $id]);
        $currentEmployee = $employeeStatement->fetch();

        if (!$currentEmployee) {
            hr_fail('Karyawan tidak ditemukan.', 404);
        }

        $hasProfileData = $fullName !== '' || $email !== '' || $department !== '' || $position !== '';

        if ($hasProfileData) {
            if ($fullName === '' || $email === '' || $department === '' || $position === '') {
                hr_fail('Nama, email, departemen, dan jabatan wajib diisi untuk edit data.');
            }

            $statement = $pdo->prepare(
                'UPDATE employees
                 SET full_name = :full_name,
                     email = :email,
                     department = :department,
                     position = :position,
                     status = :status
                 WHERE id = :id'
            );

            try {
                $statement->execute([
                    ':full_name' => $fullName,
                    ':email' => $email,
                    ':department' => $department,
                    ':position' => $position,
                    ':status' => $status === '' ? (string) $currentEmployee['status'] : $status,
                    ':id' => $id,
                ]);
            } catch (PDOException $exception) {
                hr_fail('Email karyawan sudah terdaftar.', 409);
            }

            hr_log_activity($pdo, 'HR Admin', 'employee_updated', $fullName . ' diperbarui datanya');

            hr_send_json([
                'ok' => true,
                'message' => 'Data karyawan berhasil diperbarui.',
            ]);
        }

        if ($status === '') {
            hr_fail('Status karyawan wajib diisi.');
        }

        $statement = $pdo->prepare('UPDATE employees SET status = :status WHERE id = :id');
        $statement->execute([
            ':status' => $status,
            ':id' => $id,
        ]);

        if (is_array($currentEmployee) && isset($currentEmployee['full_name']) && is_string($currentEmployee['full_name'])) {
            hr_log_activity($pdo, 'HR Admin', 'employee_updated', $currentEmployee['full_name'] . ' diubah ke status ' . $status);
        }

        hr_send_json([
            'ok' => true,
            'message' => 'Status karyawan diperbarui.',
        ]);
    }

    if ($fullName === '' || $email === '' || $department === '' || $position === '') {
        hr_fail('Nama, email, departemen, dan jabatan wajib diisi.');
    }

    $statement = $pdo->prepare(
        'INSERT INTO employees (full_name, email, department, position, status) VALUES (:full_name, :email, :department, :position, :status)'
    );

    try {
        $statement->execute([
            ':full_name' => $fullName,
            ':email' => $email,
            ':department' => $department,
            ':position' => $position,
            ':status' => $status === '' ? 'aktif' : $status,
        ]);

        hr_log_activity($pdo, 'HR Admin', 'employee_created', $fullName . ' ditambahkan ke departemen ' . $department);
    } catch (PDOException $exception) {
        hr_fail('Email karyawan sudah terdaftar.', 409);
    }

    hr_send_json([
        'ok' => true,
        'message' => 'Karyawan baru berhasil ditambahkan.',
    ], 201);
}

hr_fail('Method tidak didukung.', 405);
