<?php
session_start();
header('Content-Type: application/json');
$conn = new mysqli('localhost', 'root', '', 'project');
if ($conn->connect_error) {
    echo json_encode(['success' => false]);
    exit;
}

if (!isset($_SESSION['lecturer_id'])) {
    echo json_encode(['success' => false, 'message' => 'Lecturer not logged in']);
    exit();
}

$lecturer_id = $_SESSION['lecturer_id'];

// Query to get the highest score per student per exam (and the attempt it occurred on)
$sql = "
    SELECT r1.student_idNum, r1.student_name, r1.exam_id, r1.score, r1.attempts
    FROM results r1
    INNER JOIN (
        SELECT student_idNum, exam_id, 
               MAX(CAST(SUBSTRING_INDEX(score, '/', 1) AS UNSIGNED)) AS max_correct
        FROM results
        WHERE exam_id IN (
            SELECT DISTINCT exam_id
            FROM questions
            WHERE lecturer_id = ?
        )
        GROUP BY student_idNum, exam_id
    ) r2 ON r1.student_idNum = r2.student_idNum 
        AND r1.exam_id = r2.exam_id 
        AND CAST(SUBSTRING_INDEX(r1.score, '/', 1) AS UNSIGNED) = r2.max_correct
    WHERE r1.exam_id IN (
        SELECT DISTINCT exam_id
        FROM questions
        WHERE lecturer_id = ?
    )
    -- In case of ties, pick the row with the lowest attempt number (earliest)
    GROUP BY r1.student_idNum, r1.exam_id
    ORDER BY r1.attempts ASC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $lecturer_id, $lecturer_id);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode(['success' => true, 'results' => $data]);
?>