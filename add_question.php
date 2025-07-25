<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['lecturer_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

$lecturer_id = $_SESSION['lecturer_id'];
$data = json_decode(file_get_contents('php://input'), true);

$exam_id = trim($data['exam_id'] ?? '');
$question = trim($data['question'] ?? '');
$option_a = trim($data['option_a'] ?? '');
$option_b = trim($data['option_b'] ?? '');
$option_c = trim($data['option_c'] ?? '');
$option_d = trim($data['option_d'] ?? '');
$correct_answer = trim($data['correct_answer'] ?? '');

if (!$exam_id || !$question || !$option_a || !$option_b || !$option_c || !$option_d || !$correct_answer) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

$mysqli = new mysqli('localhost', 'root', '', 'project');
if ($mysqli->connect_errno) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

$stmt = $mysqli->prepare("INSERT INTO questions (lecturer_id, exam_id, question, option_a, option_b, option_c, option_d, correct_answer)
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param('isssssss', $lecturer_id, $exam_id, $question, $option_a, $option_b, $option_c, $option_d, $correct_answer);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to insert question']);
}

$stmt->close();
$mysqli->close();
