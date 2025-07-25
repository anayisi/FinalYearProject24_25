<?php
$conn = new mysqli("localhost", "root", "", "project");

$id = $_POST['id'];
$q = $_POST['question'];
$a = $_POST['option_a'];
$b = $_POST['option_b'];
$c = $_POST['option_c'];
$d = $_POST['option_d'];
$correct = $_POST['correct_answer'];

$stmt = $conn->prepare("UPDATE questions SET question=?, option_a=?, option_b=?, option_c=?, option_d=?, correct_answer=? WHERE id=?");
$stmt->bind_param("ssssssi", $q, $a, $b, $c, $d, $correct, $id);

if ($stmt->execute()) {
    echo "Question updated successfully.";
} else {
    echo "Failed to update question.";
}
?>
