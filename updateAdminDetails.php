<?php
header('X-Content-Type-Options: nosniff');

// Database connection details
$servername = "localhost";  // Replace with your server name
$username = "root";         // Replace with your database username
$password = "";             // Replace with your database password
$dbname = "project";  // Replace with your database name

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the raw POST data
$data = json_decode(file_get_contents('php://input'), true);

// Extract user details
$name = $data['name'];
$email = $data['email'];
$dob = $data['dob'];

// Assuming you have the user's ID stored in the session
session_start();
$admin_id = $_SESSION['admin_id'];

// Update query
$sql = "UPDATE administrators SET name = ?, email = ?, dob = ? WHERE admin_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssi", $name, $email, $dob, $admin_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}

$stmt->close();
$conn->close();
?>
