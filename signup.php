<?php
header('X-Content-Type-Options: nosniff');
header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1
header('Pragma: no-cache'); // HTTP 1.0
header('Expires: 0');
header('Content-Type: application/json');

// Retrieve JSON input
$input = json_decode(file_get_contents('php://input'), true);

$name = $input['name'];
$dob = $input['dob'];
$email = $input['email'];
$password = $input['password'];
$category = $input['userType'];
$id_num = $input['id_num']; // Required for all categories

// Validate required input fields
if (empty($name) || empty($dob) || empty($email) || empty($password) || empty($category) || empty($id_num)) {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit();
}

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Database connection
$conn = new mysqli('localhost', 'root', '', 'project');
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
    exit();
}

// Check if email already exists in any user table
$tables = ['students', 'lecturers', 'administrators'];
$email_exists = false;

foreach ($tables as $table) {
    $stmt = $conn->prepare("SELECT * FROM $table WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $email_exists = true;
        break;
    }
}

if ($email_exists) {
    echo json_encode(['success' => false, 'message' => 'Email already exists.']);
    exit();
}

// Check if ID already exists in any user table
$id_exists = false;

foreach ($tables as $table) {
    $stmt = $conn->prepare("SELECT * FROM $table WHERE id_num = ?");
    $stmt->bind_param('s', $id_num);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $id_exists = true;
        break;
    }
}

if ($id_exists) {
    echo json_encode(['success' => false, 'message' => 'ID number is already in use.']);
    exit();
}

// Validate ID against appropriate random table
$valid_id = false;

if ($category === 'student') {
    $checkTable = 'randstu';
    $column = 'student_id';
} elseif ($category === 'lecturer') {
    $checkTable = 'randlec';
    $column = 'lecturer_id';
} elseif ($category === 'admin') {
    $checkTable = 'randadmin';
    $column = 'admin_id';
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid user category.']);
    exit();
}

// Check if the ID exists in the correct random ID table
$stmt = $conn->prepare("SELECT * FROM $checkTable WHERE $column = ?");
$stmt->bind_param('s', $id_num);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Incorrect ID for the selected category.']);
    exit();
}

// Insert user data based on the category
if ($category === 'student') {
    $level = $input['level'];
    $school = $input['school'];
    $program = $input['program'];

    $stmt = $conn->prepare("INSERT INTO students (name, dob, email, password, level, school, program, id_num) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('ssssssss', $name, $dob, $email, $hashed_password, $level, $school, $program, $id_num);

} elseif ($category === 'lecturer') {
    $school = $input['school'];

    $stmt = $conn->prepare("INSERT INTO lecturers (name, dob, email, password, school, id_num) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('ssssss', $name, $dob, $email, $hashed_password, $school, $id_num);

} elseif ($category === 'admin') {
    $stmt = $conn->prepare("INSERT INTO administrators (name, dob, email, password, id_num) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param('sssss', $name, $dob, $email, $hashed_password, $id_num);
}

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Signup successful!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Signup failed. Please try again later.']);
}

$stmt->close();
$conn->close();
?>
<?php
// This PHP script handles user signup for a web application. It validates input data, checks for existing users, and inserts new user data into the appropriate database table based on the user category (student, lecturer, or administrator). The script also hashes passwords for security and returns JSON responses indicating success or failure.
// It ensures that all required fields are provided, checks for duplicate email and ID numbers, and verifies the ID against a random ID table. If all checks pass, it inserts the user data into the corresponding table and returns a success message.