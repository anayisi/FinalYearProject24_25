<?php
header('Content-Type: application/json');

try {
    if (!isset($_POST['receiver_type'], $_POST['receiver_id'], $_POST['message'])) {
        throw new Exception("Missing parameters.");
    }

    $conn = new mysqli("localhost", "root", "", "project");
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    session_start();
    if (!isset($_SESSION['admin_id'])) {
        throw new Exception("Not authorized.");
    }

    $admin_id = $_SESSION['admin_id'];
    $receiver_type = $_POST['receiver_type'];
    $receiver_id = intval($_POST['receiver_id']);
    $message = trim($_POST['message']);

    if ($message === '') {
        throw new Exception("Message cannot be empty.");
    }

    $stmt = $conn->prepare("INSERT INTO conversations (sender_type, sender_id, receiver_type, receiver_id, message) 
                            VALUES ('admin', ?, ?, ?, ?)");
    $stmt->bind_param("isss", $admin_id, $receiver_type, $receiver_id, $message);

    if (!$stmt->execute()) {
        throw new Exception("Failed to send message.");
    }

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
