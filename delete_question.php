<?php
$conn = new mysqli("localhost", "root", "", "project");
$id = $_POST['id'];
$stmt = $conn->prepare("DELETE FROM questions WHERE id = ?");
$stmt->bind_param("i", $id);
if ($stmt->execute()) {
    echo "Question deleted.";
} else {
    echo "Failed to delete.";
}
?>
