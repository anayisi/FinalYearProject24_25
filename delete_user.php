<?php
$data = json_decode(file_get_contents('php://input'), true);
$type = $data['type'];
$id = $data['id'];

$conn = new mysqli('localhost', 'root', '', 'project');
if ($conn->connect_error) {
    echo json_encode(['success' => false]);
    exit;
}

$table = '';
$key = '';

switch ($type) {
    case 'administrator':
        $table = 'administrators';
        $key = 'admin_id';
        break;
    case 'lecturer':
        $table = 'lecturers';
        $key = 'lecturer_id';
        break;
    case 'student':
        $table = 'students';
        $key = 'student_id';
        break;
    default:
        echo json_encode(['success' => false]);
        exit;
}

$stmt = $conn->prepare("DELETE FROM $table WHERE $key = ?");
$stmt->bind_param('i', $id);
$success = $stmt->execute();

echo json_encode(['success' => $success]);
?>
