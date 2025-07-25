<?php
session_start();
$student_id = $_SESSION['student_id'];
$exam_id = $_POST['exam_id'];

$conn = new mysqli("localhost", "root", "", "project");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Count previous attempts
$stmt = $conn->prepare("SELECT COUNT(*) as attempts FROM results WHERE student_id = ? AND exam_id = ?");
$stmt->bind_param("ss", $student_id, $exam_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$attempts = $row['attempts'];

if ($attempts >= 3) {
    echo json_encode(["status" => "denied", "message" => "You have already attempted this exam 3 times."]);
} else {
    echo json_encode(["status" => "allowed", "attempt" => $attempts + 1]);
}

$conn->close();
?>
