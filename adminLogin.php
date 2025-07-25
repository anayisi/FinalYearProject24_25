<?php
header('X-Content-Type-Options: nosniff');

header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1
header('Pragma: no-cache'); // HTTP 1.0
header('Expires: 0');

session_start();
header('Content-Type: application/json');

$conn = new mysqli('localhost', 'root', '', 'project');
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $user = null;
    $table = '';
    
    // Check if email exists in students table
    $sql = "SELECT * FROM administrators WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    if ($user) {
        $table = 'administrators';
    } 
    
    if ($user && password_verify($password, $user['password'])) {
        if ($table === 'administrators') {
            $_SESSION['admin_id'] = $user['admin_id'];
            echo json_encode(['success' => true, 'role' => 'admin', 'message' => 'Admin Login successful!']);
        }

    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid email or password.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}

$conn->close();
