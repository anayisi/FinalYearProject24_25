<?php
session_start();
header('Content-Type: application/json');

// Ensure lecturer is logged in
if (!isset($_SESSION['lecturer_id'])) {
    echo json_encode(['success' => false, 'message' => 'Lecturer not logged in.']);
    exit();
}

$lecturerId = $_SESSION['lecturer_id'];

$data = json_decode(file_get_contents('php://input'), true);
$examId = $data['exam_id'];

$conn = new mysqli("localhost", "root", "", "project");
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
    exit();
}

// Check if exam_id is associated with this lecturer
$checkStmt = $conn->prepare("SELECT 1 FROM questions WHERE exam_id = ? AND lecturer_id = ? LIMIT 1");
$checkStmt->bind_param("ss", $examId, $lecturerId);
$checkStmt->execute();
$checkStmt->store_result();

if ($checkStmt->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access to results.']);
    $checkStmt->close();
    $conn->close();
    exit();
}
$checkStmt->close();

// Proceed to fetch results
$stmt = $conn->prepare("SELECT student_name, student_idNum, exam_id, result_id, score FROM results WHERE exam_id = ?");
$stmt->bind_param("s", $examId);
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
