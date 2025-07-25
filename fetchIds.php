<?php
header('Content-Type: application/json');

// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'project');

// Check connection
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
    exit();
}

// Fetch student IDs
$studentQuery = "SELECT student_id FROM randstu";
$studentResult = $conn->query($studentQuery);
$studentIds = [];
while ($row = $studentResult->fetch_assoc()) {
    $studentIds[] = $row['student_id'];
}

// Fetch lecturer IDs
$lecturerQuery = "SELECT lecturer_id FROM randlec";
$lecturerResult = $conn->query($lecturerQuery);
$lecturerIds = [];
while ($row = $lecturerResult->fetch_assoc()) {
    $lecturerIds[] = $row['lecturer_id'];
}

// Fetch admin IDs
$adminQuery = "SELECT admin_id FROM randadmin";
$adminResult = $conn->query($adminQuery);
$adminIds = [];
while ($row = $adminResult->fetch_assoc()) {
    $adminIds[] = $row['admin_id'];
}

// Combine IDs into a list of rows
$maxLength = max(count($studentIds), count($lecturerIds), count($adminIds));
$combined = [];

for ($i = 0; $i < $maxLength; $i++) {
    $combined[] = [
        'student_id' => $studentIds[$i] ?? '',
        'lecturer_id' => $lecturerIds[$i] ?? '',
        'admin_id' => $adminIds[$i] ?? ''
    ];
}

// Return combined data
echo json_encode(['success' => true, 'ids' => $combined]);

$conn->close();
?>
