<?php
$conn = new mysqli('localhost', 'root', '', 'project');
if ($conn->connect_error) {
    echo json_encode(['success' => false]);
    exit;
}

$administrators = $conn->query("SELECT admin_id, name, email, id_num FROM administrators")->fetch_all(MYSQLI_ASSOC);
$lecturers = $conn->query("SELECT lecturer_id, name, email, school, id_num FROM lecturers")->fetch_all(MYSQLI_ASSOC);
$students = $conn->query("SELECT student_id, name, level, program, id_num FROM students")->fetch_all(MYSQLI_ASSOC);

echo json_encode([
    'success' => true,
    'administrators' => $administrators,
    'lecturers' => $lecturers,
    'students' => $students
]);
?>
