<?php
session_start();
header('Content-Type: application/json');

// Check login
if (!isset($_SESSION['student_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

$studentId = $_SESSION['student_id'];
$data = json_decode(file_get_contents('php://input'), true);
$examId = $data['exam_id'];

$conn = new mysqli("localhost", "root", "", "project");
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit();
}

// Fetch all matching results
$stmt = $conn->prepare("SELECT result_id, student_name, student_idNum, exam_id, score FROM results WHERE student_id = ? AND exam_id = ?");
$stmt->bind_param("ss", $studentId, $examId);
$stmt->execute();
$result = $stmt->get_result();

$results = [];
while ($row = $result->fetch_assoc()) {
    $results[] = $row;
}

echo json_encode(['success' => true, 'results' => $results]);

$stmt->close();
$conn->close();
?>
