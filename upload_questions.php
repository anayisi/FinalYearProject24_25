<?php
header('X-Content-Type-Options: nosniff');

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (!isset($_SESSION['lecturer_id'])) {
        echo "Unauthorized access. Please log in as a lecturer.";
        exit;
    }

    $lecturer_id = $_SESSION['lecturer_id'];

    // Retrieve the exam_id
    $examId = $_POST['examId'];

    if (!isset($_FILES["questionFile"])) {
        echo "No file uploaded.";
        exit;
    }

    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["questionFile"]["name"]);

    // Check file type
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    if ($fileType != "txt") {
        echo "Only .txt files are allowed.";
        exit;
    }

    // Check for upload errors
    if ($_FILES["questionFile"]["error"] != UPLOAD_ERR_OK) {
        echo "Error uploading file. Error code: " . $_FILES["questionFile"]["error"];
        exit;
    }

    // Upload the file temporarily
    if (move_uploaded_file($_FILES["questionFile"]["tmp_name"], $target_file)) {
        $questions = file($target_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $parsedQuestions = parseQuestions($questions);

        if (empty($parsedQuestions)) {
            echo "No valid questions found in the file.";
            exit;
        }

        $storedCount = storeQuestions($lecturer_id, $examId, $parsedQuestions);

        echo "Upload complete. $storedCount question(s) successfully saved.";
    } else {
        echo "Error uploading file.";
    }
}

function parseQuestions($lines) {
    $questions = [];
    foreach ($lines as $line) {
        $parts = explode('//', $line);
        if (count($parts) == 6) {
            $questions[] = [
                'question' => trim($parts[0]),
                'option_a' => trim($parts[1]),
                'option_b' => trim($parts[2]),
                'option_c' => trim($parts[3]),
                'option_d' => trim($parts[4]),
                'correct_answer' => trim($parts[5]),
            ];
        }
    }
    return $questions;
}

function storeQuestions($lecturer_id, $examId, $questions) {
    $conn = new mysqli("localhost", "root", "", "project");

    if ($conn->connect_error) {
        echo "Database connection failed.";
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO questions (lecturer_id, exam_id, question, option_a, option_b, option_c, option_d, correct_answer) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $count = 0;

    foreach ($questions as $q) {
        $stmt->bind_param("ssssssss", 
            $lecturer_id, 
            $examId, 
            $q['question'], 
            $q['option_a'], 
            $q['option_b'], 
            $q['option_c'], 
            $q['option_d'], 
            $q['correct_answer']
        );
        if ($stmt->execute()) {
            $count++;
        }
    }

    $stmt->close();
    $conn->close();
    return $count;
}
?>
