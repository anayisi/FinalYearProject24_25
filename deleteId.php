<?php
header('Content-Type: application/json');

// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'project');

// Check for connection error
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
    exit();
}

// Get JSON input
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['idType']) || !isset($data['id'])) {
    echo json_encode(['success' => false, 'message' => 'Missing parameters.']);
    exit();
}

$idType = $data['idType'];
$id = $data['id'];

$table = '';
$column = '';

// Map user type to table and column
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
        echo json_encode(['success' => false, 'message' => 'Invalid id type.']);
        exit();
}

// Prepare and execute delete statement
$sql = "DELETE FROM $table WHERE $column = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Failed to prepare delete statement.']);
    exit();
}

$stmt->bind_param("s", $id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => ucfirst($idType) . ' ID deleted successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to delete ID.']);
}

$stmt->close();
$conn->close();
?>
