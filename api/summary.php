<?php

declare(strict_types=1);

require_once __DIR__ . '/db.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

$pdo = hr_db();

$summary = [
    'totalEmployees' => (int) $pdo->query('SELECT COUNT(*) FROM employees')->fetchColumn(),
    'activeEmployees' => (int) $pdo->query("SELECT COUNT(*) FROM employees WHERE status = 'aktif'")->fetchColumn(),
    'pendingLeaves' => (int) $pdo->query("SELECT COUNT(*) FROM leave_requests WHERE status = 'pending'")->fetchColumn(),
    'approvedLeaves' => (int) $pdo->query("SELECT COUNT(*) FROM leave_requests WHERE status = 'disetujui'")->fetchColumn(),
    'recentActivities' => $pdo->query(
        'SELECT actor, action, detail, created_at
         FROM activity_log
         ORDER BY id DESC
         LIMIT 6'
    )->fetchAll(),
];

hr_send_json([
    'ok' => true,
    'data' => $summary,
]);
