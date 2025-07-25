<?php
header('Content-Type: application/json');
session_start();

try {
    // 1. Check if admin is logged in and target user is specified
    if (!isset($_SESSION['admin_id']) || !isset($_POST['user_id'], $_POST['user_type'])) {
        throw new Exception("Required parameters missing.");
    }

    // 2. Connect to database
    $conn = new mysqli("localhost", "root", "", "project");
    if ($conn->connect_error) {
        throw new Exception("Database connection failed: " . $conn->connect_error);
    }

    // 3. Get IDs and validate
    $admin_id = intval($_SESSION['admin_id']);
    $user_id = intval($_POST['user_id']);
    $user_type = $conn->real_escape_string($_POST['user_type']); // student or lecturer

    // 4. Fetch conversation between admin and specific target user
    $stmt = $conn->prepare("
        SELECT 
            id,
            sender_type, 
            sender_id, 
            receiver_type, 
            receiver_id, 
            message, 
            is_read,
            timestamp 
        FROM conversations
        WHERE 
            /* Case 1: Target user sent to admin */
            (sender_type = ? AND sender_id = ? AND receiver_type = 'admin' AND receiver_id = ?)
            OR
            /* Case 2: Admin sent to target user */
            (sender_type = 'admin' AND sender_id = ? AND receiver_type = ? AND receiver_id = ?)
        ORDER BY timestamp ASC
    ");
    
    // Bind parameters: user_type, user_id, admin_id, admin_id, user_type, user_id
    $stmt->bind_param("ssssss", $user_type, $user_id, $admin_id, $admin_id, $user_type, $user_id);
    
    if (!$stmt->execute()) {
        throw new Exception("Failed to fetch messages.");
    }

    // 5. Mark unread messages as read (optional)
    if ($_POST['mark_read'] ?? false) {
        $conn->query("UPDATE conversations SET is_read = 1 
                     WHERE receiver_type = 'admin' AND receiver_id = $admin_id
                     AND sender_type = '$user_type' AND sender_id = $user_id");
    }

    // 6. Return formatted messages
    $result = $stmt->get_result();
    $messages = $result->fetch_all(MYSQLI_ASSOC);

    echo json_encode( $messages);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>