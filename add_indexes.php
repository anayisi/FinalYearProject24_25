<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$type = $data['type'] ?? '';
$ids = $data['ids'] ?? [];

if (empty($type) || empty($ids) || !is_array($ids)) {
    echo json_encode(['success' => false, 'message' => 'Invalid input.']);
    exit;
}

$tableMap = [
    'student' => ['table' => 'randstu', 'column' => 'student_id'],
    'lecturer' => ['table' => 'randlec', 'column' => 'lecturer_id'],
    'admin' => ['table' => 'randadmin', 'column' => 'admin_id']
];

if (!isset($tableMap[$type])) {
    echo json_encode(['success' => false, 'message' => 'Invalid index type.']);
    exit;
}

$table = $tableMap[$type]['table'];
$column = $tableMap[$type]['column'];

$mysqli = new mysqli('localhost', 'root', '', 'project');
if ($mysqli->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
    exit;
}

// Step 1: Get existing IDs
$placeholders = implode(',', array_fill(0, count($ids), '?'));
$types = str_repeat('s', count($ids));

$existingIds = [];
$query = "SELECT `$column` FROM `$table` WHERE `$column` IN ($placeholders)";
$stmt = $mysqli->prepare($query);
$stmt->bind_param($types, ...$ids);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $existingIds[] = $row[$column];
}

$stmt->close();

// Step 2: Insert only new IDs
$uniqueIds = array_diff($ids, $existingIds);

$successCount = 0;

if (count($uniqueIds) > 0) {
    $insertStmt = $mysqli->prepare("INSERT INTO `$table` (`$column`) VALUES (?)");

    foreach ($uniqueIds as $id) {
        $insertStmt->bind_param('s', $id);
        if ($insertStmt->execute()) {
            $successCount++;
        }
    }

    $insertStmt->close();
}

$mysqli->close();

echo json_encode([
    'success' => true,
    'message' => "$successCount ID(s) successfully added to $table.". 
        (count($existingIds) > 0 ? " The following ID(s) were skipped because they already exist: " . implode(', ', $existingIds) : ""),
    'skippedIds' => $existingIds
    ]);
?>
