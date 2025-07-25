<?php
header('Content-Type: application/json');

try {
    $conn = new mysqli("localhost", "root", "", "project");
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    $users = [];

    // Fetch students
    $students = $conn->query("SELECT student_id, name, id_num FROM students");
    if (!$students) throw new Exception("Error fetching students: " . $conn->error);

    while ($row = $students->fetch_assoc()) {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM conversations WHERE sender_type = 'student' AND sender_id = ? AND receiver_type = 'admin' AND is_read = 0");
        $stmt->bind_param("s", $row['student_id']);
        if (!$stmt->execute()) throw new Exception("Error checking unread student messages");
        $stmt->bind_result($unread);
        $stmt->fetch();
        $stmt->close();

        $users[] = [
            'id' => $row['student_id'],
            'name' => $row['name'],
            'id_num' => $row['id_num'],
            'type' => 'student',
            'has_unread' => $unread > 0
        ];
    }

    // Fetch lecturers
    $lecturers = $conn->query("SELECT lecturer_id, name, id_num FROM lecturers");
    if (!$lecturers) throw new Exception("Error fetching lecturers: " . $conn->error);

    while ($row = $lecturers->fetch_assoc()) {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM conversations WHERE sender_type = 'lecturer' AND sender_id = ? AND receiver_type = 'admin' AND is_read = 0");
        $stmt->bind_param("s", $row['lecturer_id']);
        if (!$stmt->execute()) throw new Exception("Error checking unread lecturer messages");
        $stmt->bind_result($unread);
        $stmt->fetch();
        $stmt->close();

        $users[] = [
            'id' => $row['lecturer_id'],
            'name' => $row['name'],
            'id_num' => $row['id_num'],
            'type' => 'lecturer',
            'has_unread' => $unread > 0
        ];
    }

    echo json_encode($users);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
