<?php
session_start();
require_once 'db.php'; // Ensure this path correctly points to your database connection file

// Check if the form has been submitted via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate and process form based on user type
    switch ($_POST['user-type']) {
        case 'Student':
        case 'Lecturer':
            validateAndProcessUser($_POST);
            break;
        case 'Alumni':
            validateAndProcessAlumni($_POST);
            break;
        default:
            $_SESSION['error'] = 'Invalid user type';
            header('Location: register_view.php');
            exit;
    }
} else {
    // If not a POST request, redirect to the registration page
    header('Location: register_view.php');
    exit;
}

function validateAndProcessUser($formData) {
    global $db;
    // Validate input fields
    $requiredFields = ['full-name', 'username', 'email', 'password', 'confirm-password', 'user-faculty'];
    foreach ($requiredFields as $field) {
        if (!isset($formData[$field]) || empty($formData[$field])) {
            $_SESSION['error'] = 'All fields are required';
            header('Location: register_view.php');
            exit;
        }
    }

    // Extract form data
    $user_type = $formData['user-type'];
    $full_name = trim($formData['full-name']);
    $username = trim($formData['username']);
    $email = trim($formData['email']);
    $password = trim($formData['password']);
    $confirm_password = trim($formData['confirm-password']);
    $user_faculty = $formData['user-faculty']; 

    // Validate email format based on user type
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !strpos($email, 'apiit.lk')) {
        $_SESSION['error'] = 'Invalid email format';
        header('Location: register_view.php');
        exit;
    }

    // Check if password and confirm password match
    if ($password != $confirm_password) {
        $_SESSION['error'] = 'Password and confirm password do not match';
        header('Location: register_view.php');
        exit;
    }

    // Check if username already exists
    $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetch(PDO::FETCH_ASSOC)) {
        $_SESSION['error'] = 'Username already exists';
        header('Location: register_view.php');
        exit;
    }

    // Check if email already exists
    $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch(PDO::FETCH_ASSOC)) {
        $_SESSION['error'] = 'Email already exists';
        header('Location: register_view.php');
        exit;
    }

    // Insert the user data into the database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash the password for security
    $stmt = $db->prepare("INSERT INTO users (username, password, user_type, full_name, email, user_faculty) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$username, $hashed_password, $user_type, $full_name, $email, $user_faculty]);

    // Redirect after successful registration
    header('Location: login.html');
    exit;
}

function validateAndProcessAlumni($formData) {
    global $db;
    // Validate input fields
    $requiredFields = ['full-name', 'username', 'email', 'password', 'confirm-password', 'alumni-association-id', 'user-faculty'];
    foreach ($requiredFields as $field) {
        if (!isset($formData[$field]) || empty($formData[$field])) {
            $_SESSION['error'] = 'All fields are required';
            header('Location: register_view.php');
            exit;
        }
    }

    // Extract form data
    $user_type = $formData['user-type'];
    $full_name = trim($formData['full-name']);
    $username = trim($formData['username']);
    $email = trim($formData['email']);
    $password = trim($formData['password']);
    $confirm_password = trim($formData['confirm-password']);
    $alumni_association_id = trim($formData['alumni-association-id']);
    $user_faculty = $formData['user-faculty']; 

    // Validate email format for alumni
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'Invalid email format';
        header('Location: register_view.php');
        exit;
    }

    // Check if password and confirm password match
    if ($password != $confirm_password) {
        $_SESSION['error'] = 'Password and confirm password do not match';
        header('Location: register_view.php');
        exit;
    }

    // Check if username already exists
    $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetch(PDO::FETCH_ASSOC)) {
        $_SESSION['error'] = 'Username already exists';
        header('Location: register_view.php');
        exit;
    }

    // Check if email already exists
    $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch(PDO::FETCH_ASSOC)) {
        $_SESSION['error'] = 'Email already exists';
        header('Location: register_view.php');
        exit;
    }

    // Check if alumni association ID exists in the alumni table
    $stmt = $db->prepare("SELECT * FROM alumni WHERE alumni_id = ?");
    $stmt->execute([$alumni_association_id]);
    $alumni = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$alumni) {
        $_SESSION['error'] = 'Invalid alumni association ID';
        header('Location: register_view.php');
        exit;
    }



    // Insert the user data into the database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash the password for security
    $stmt = $db->prepare("INSERT INTO users (username, password, user_type, full_name, email, user_faculty, alumni_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$username, $hashed_password, $user_type, $full_name, $email, $user_faculty, $alumni['id']]);



    // Redirect after successful registration
    header('Location: login.html');
    exit;
}
?>