<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Optional, useful for frontend testing

// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'project');

// Check connection
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
    exit();
}

// Get raw POST data and decode JSON
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['idType']) || !isset($data['newId'])) {
    echo json_encode(['success' => false, 'message' => 'Missing parameters.']);
    exit();
}

$idType = $data['idType'];
$newId = $data['newId'];

$table = '';
$column = '';

// Determine table and column based on idType
switch ($idType) {
    case 'student':
        $table = 'randstu';
        $column = 'student_id';
        break;
    case 'lecturer':
        $table = 'randlec';
        $column = 'lecturer_id';
        break;
    case 'admin':
        $table = 'randadmin';
        $column = 'admin_id';
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid idType.']);
        exit();
}

// Prepare and execute insert statement
$sql = "INSERT INTO $table ($column) VALUES (?)";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Database error during preparation.']);
    exit();
}

$stmt->bind_param("s", $newId);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => ucfirst($idType) . " ID stored successfully."]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to store ID.']);
}

$stmt->close();
$conn->close();
?>
