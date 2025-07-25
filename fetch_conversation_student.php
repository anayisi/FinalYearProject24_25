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
    $stmt = $conn->prepare("
        SELECT * FROM conversations 
        WHERE 
            (sender_type = 'student' AND sender_id = ? AND receiver_type = 'admin' AND receiver_id = ?)
            OR
            (sender_type = 'admin' AND sender_id = ? AND receiver_type = 'student' AND receiver_id = ?)
        ORDER BY timestamp ASC
    ");
    $stmt->bind_param("ssss", $student_id, $admin_id, $admin_id, $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $conversations = $result->fetch_all(MYSQLI_ASSOC);

    echo json_encode($conversations);
} catch (Exception $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>