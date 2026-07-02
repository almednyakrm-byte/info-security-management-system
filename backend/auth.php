<?php

// Start session to track user status
session_start();

// Import database connection
require_once 'db.php';

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    // If user is logged in, return JSON response with user status
    echo json_encode(['status' => 'logged_in', 'user_id' => $_SESSION['user_id']]);
    exit;
}

// Handle login request
if (isset($_POST['action']) && $_POST['action'] == 'login') {
    // Check if required fields are present
    if (!isset($_POST['username']) || !isset($_POST['password'])) {
        echo json_encode(['error' => 'Missing required fields']);
        exit;
    }

    // Sanitize input fields
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

    // Prepare SQL query to select user
    $stmt = $db->prepare('SELECT * FROM users WHERE username = :username');
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    // Fetch user data
    $user = $stmt->fetch();

    // Check if user exists
    if ($user && password_verify($password, $user['password'])) {
        // If user exists and password is correct, log them in
        $_SESSION['user_id'] = $user['id'];
        echo json_encode(['status' => 'logged_in', 'user_id' => $_SESSION['user_id']]);
    } else {
        // If user does not exist or password is incorrect, return error
        echo json_encode(['error' => 'Invalid username or password']);
    }
    exit;
}

// Handle register request
if (isset($_POST['action']) && $_POST['action'] == 'register') {
    // Check if required fields are present
    if (!isset($_POST['username']) || !isset($_POST['email']) || !isset($_POST['password']) || !isset($_POST['confirm_password'])) {
        echo json_encode(['error' => 'Missing required fields']);
        exit;
    }

    // Sanitize input fields
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
    $confirm_password = filter_var($_POST['confirm_password'], FILTER_SANITIZE_STRING);

    // Check if password and confirm password match
    if ($password !== $confirm_password) {
        echo json_encode(['error' => 'Passwords do not match']);
        exit;
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL query to insert new user
    $stmt = $db->prepare('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);
    $stmt->execute();

    // Return JSON response with user ID
    echo json_encode(['status' => 'registered', 'user_id' => $db->lastInsertId()]);
    exit;
}

// Handle logout request
if (isset($_POST['action']) && $_POST['action'] == 'logout') {
    // Destroy session to log user out
    session_destroy();
    echo json_encode(['status' => 'logged_out']);
    exit;
}

// If no action is specified, return JSON response with error
echo json_encode(['error' => 'Invalid action']);
exit;

?>