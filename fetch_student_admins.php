<?php
session_start();
$conn = new mysqli("localhost", "root", "", "project");

if (!isset($_SESSION['student_id'])) {
    echo json_encode(['error' => 'Not authenticated']);
    exit;
}

$student_id = $_SESSION['student_id'];

try {
    $stmt = $conn->query("SELECT admin_id, name FROM administrators");
    $admins = [];

    while ($row = $stmt->fetch_assoc()) {
        $admin_id = $row['admin_id'];

        $checkUnread = $conn->prepare("SELECT COUNT(*) FROM conversations WHERE sender_type = 'admin' AND receiver_type = 'student' AND receiver_id = ? AND sender_id = ? AND is_read = 0");
        $checkUnread->bind_param("ss", $student_id, $admin_id);
        $checkUnread->execute();
        $checkUnread->bind_result($unreadCount);
        $checkUnread->fetch();
        $hasUnread = $unreadCount > 0;
        $checkUnread->close();

        $admins[] = [
            'id' => $admin_id,
            'name' => $row['name'],
            'has_unread' => $hasUnread
        ];
    }

    echo json_encode($admins);
} catch (Exception $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
