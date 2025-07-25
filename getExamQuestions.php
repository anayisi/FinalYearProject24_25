<?php
session_start();
header('Content-Type: application/json');

if (!isset($_GET['exam_id'])) {
    echo json_encode(['success' => false, 'message' => 'Exam ID not provided.']);
    exit();
}

$examId = $_GET['exam_id'];

$conn = new mysqli("localhost", "root", "", "project");
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
    exit();
}

$stmt = $conn->prepare("SELECT id, question, option_a, option_b, option_c, option_d, correct_answer FROM questions WHERE exam_id = ?");
$stmt->bind_param("s", $examId);
$stmt->execute();
$result = $stmt->get_result();

$questions = [];
while ($row = $result->fetch_assoc()) {
    $correctAnswer = strtoupper(trim($row['correct_answer']));
    $type = (strlen($correctAnswer) > 1) ? 'multiple' : 'single';

    $questions[] = [
        'id' => $row['id'],
        'text' => nl2br(htmlspecialchars($row['question'])),
        'options' => [
            $row['option_a'],
            $row['option_b'],
            $row['option_c'],
            $row['option_d']
        ],
        'type' => $type,
        'correct_answer' => $correctAnswer // You might hide this client-side in production
    ];
}

echo json_encode(['success' => true, 'questions' => $questions]);
$conn->close();
?>
