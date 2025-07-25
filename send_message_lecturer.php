<?php
session_start();
$conn = new mysqli("localhost", "root", "", "project");

if (!isset($_SESSION['lecturer_id'])) {
    echo json_encode(['error' => 'Not authenticated']);
    exit;
}

$lecturer_id = $_SESSION['lecturer_id'];
$admin_id = $_POST['admin_id'] ?? '';
$message = trim($_POST['message'] ?? '');

if (!$admin_id || $message === '') {
    echo json_encode(['error' => 'Missing admin_id or message']);
    exit;
}

try {
    $stmt = $conn->prepare("INSERT INTO conversations (sender_type, sender_id, receiver_type, receiver_id, message, is_read, timestamp) VALUES ('lecturer', ?, 'admin', ?, ?, 0, NOW())");
    $stmt->execute([$lecturer_id, $admin_id, $message]);
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
