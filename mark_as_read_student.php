<?php
session_start();
$conn = new mysqli("localhost", "root", "", "project");

if (!isset($_SESSION['student_id'])) {
    echo json_encode(['error' => 'Not authenticated']);
    exit;
}

$student_id = $_SESSION['student_id'];
$admin_id = $_POST['admin_id'] ?? '';

if (!$admin_id) {
    echo json_encode(['error' => 'Missing admin_id']);
    exit;
}

try {
    $stmt = $conn->prepare("UPDATE conversations SET is_read = 1 WHERE sender_type = 'admin' AND sender_id = ? AND receiver_type = 'student' AND receiver_id = ?");
    $stmt->execute([$admin_id, $student_id]);
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
