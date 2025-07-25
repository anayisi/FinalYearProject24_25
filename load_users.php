<?php
session_start();
$conn = new mysqli("localhost", "root", "", "project");

$response = ["students" => [], "lecturers" => []];

$student_query = $conn->query("SELECT id_num, name FROM students");
while ($row = $student_query->fetch_assoc()) {
    $response["students"][] = $row;
}

$lecturer_query = $conn->query("SELECT id_num, name FROM lecturers");
while ($row = $lecturer_query->fetch_assoc()) {
    $response["lecturers"][] = $row;
}

echo json_encode($response);
?>
