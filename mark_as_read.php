<?php
header('Content-Type: application/json');

try {
    if (!isset($_POST['user_type'], $_POST['user_id'])) {
        throw new Exception("Missing parameters.");
    }

    $conn = new mysqli("localhost", "root", "", "project");
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    $user_type = $_POST['user_type'];
    $user_id = intval($_POST['user_id']);

    $stmt = $conn->prepare("UPDATE conversations SET is_read = 1 WHERE sender_type = ? AND sender_id = ? AND receiver_type = 'admin'");
    $stmt->bind_param("ss", $user_type, $user_id);

    if (!$stmt->execute()) {
        throw new Exception("Failed to mark messages as read.");
    }

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
