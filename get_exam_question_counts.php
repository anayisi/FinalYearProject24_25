<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$conn = new mysqli('localhost', 'root', '', 'project');
if ($conn->connect_error) {
    echo json_encode(['success' => false]);
    exit;
}

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['exam_ids']) || !is_array($data['exam_ids']) || empty($data['exam_ids'])) {
    echo json_encode([]);
    exit();
}

$exam_ids = $data['exam_ids'];
$placeholders = implode(',', array_fill(0, count($exam_ids), '?'));
$types = str_repeat('s', count($exam_ids));

$sql = "SELECT exam_id, COUNT(*) AS question_count FROM questions WHERE exam_id IN ($placeholders) GROUP BY exam_id";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(['error' => 'Prepare failed: ' . $conn->error]);
    exit();
}

$stmt->bind_param($types, ...$exam_ids);
$stmt->execute();
$result = $stmt->get_result();

$counts = [];
while ($row = $result->fetch_assoc()) {
    $counts[$row['exam_id']] = $row['question_count'];
}

echo json_encode($counts);
?>
