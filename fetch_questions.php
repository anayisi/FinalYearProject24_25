<?php
session_start();
header('Content-Type: application/json');

// Ensure lecturer is logged in
if (!isset($_SESSION['lecturer_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

$lecturer_id = $_SESSION['lecturer_id'];
$data = json_decode(file_get_contents('php://input'), true);
$exam_id = $data['exam_id'] ?? '';

if (empty($exam_id)) {
    echo json_encode(['success' => false, 'message' => 'Exam ID not provided']);
    exit;
}

$mysqli = new mysqli('localhost', 'root', '', 'project');
if ($mysqli->connect_errno) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

$stmt = $mysqli->prepare("SELECT id, question, option_a, option_b, option_c, option_d, correct_answer 
                          FROM questions 
                          WHERE exam_id = ? AND lecturer_id = ?");
$stmt->bind_param('ss', $exam_id, $lecturer_id);
$stmt->execute();
$result = $stmt->get_result();

$questions = [];
while ($row = $result->fetch_assoc()) {
    $questions[] = $row;
}

if (empty($questions)) {
    echo json_encode([
        'success' => false,
        'message' => 'No questions found for this Exam ID under your account.'
    ]);
} else {
    echo json_encode(['success' => true, 'questions' => $questions]);
}

$stmt->close();
$mysqli->close();
