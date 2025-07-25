<?php
session_start();
$conn = new mysqli("localhost", "root", "", "project");

if (!isset($_SESSION['lecturer_id'])) {
    echo json_encode(['error' => 'Not authenticated']);
    exit;
}

$lecturer_id = $_SESSION['lecturer_id'];
$admin_id = $_POST['admin_id'] ?? '';

if (!$admin_id) {
    echo json_encode(['error' => 'Missing admin_id']);
    exit;
}

try {
    $stmt = $conn->prepare("
        SELECT * FROM conversations 
        WHERE 
            (sender_type = 'lecturer' AND sender_id = ? AND receiver_type = 'admin' AND receiver_id = ?)
            OR
            (sender_type = 'admin' AND sender_id = ? AND receiver_type = 'lecturer' AND receiver_id = ?)
        ORDER BY timestamp ASC
    ");
    $stmt->bind_param("ssss", $lecturer_id, $admin_id, $admin_id, $lecturer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $conversations = $result->fetch_all(MYSQLI_ASSOC);

    echo json_encode($conversations);
} catch (Exception $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>