<?php

require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get user role
$userRole = $_SESSION['user_role'];

// Check if user is admin
$isAdmin = ($userRole == 'admin');

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Validate and sanitize input
    $id = isset($_GET['id']) ? intval($_GET['id']) : null;

    // Check if admin or user wants to view their own profile
    if ($id === null || ($id > 0 && $id == $_SESSION['user_id'])) {
        // Prepare SQL query
        $stmt = $pdo->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // Fetch and return user data
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($user);
        } else {
            http_response_code(404);
            echo json_encode(array('error' => 'User not found'));
        }
    } else {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Read JSON input
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $name = isset($input['name']) ? trim($input['name']) : null;
    $email = isset($input['email']) ? trim($input['email']) : null;
    $password = isset($input['password']) ? trim($input['password']) : null;
    $role = isset($input['role']) ? trim($input['role']) : null;

    // Check if admin or user wants to create a new user
    if ($isAdmin || ($name && $email && $password && $role)) {
        // Prepare SQL query
        $stmt = $pdo->prepare('INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':role', $role);
        $stmt->execute();

        // Return user ID
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(array('id' => $pdo->lastInsertId()));
    } else {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // Validate and sanitize input
    $id = isset($_GET['id']) ? intval($_GET['id']) : null;
    $name = isset($_POST['name']) ? trim($_POST['name']) : null;
    $email = isset($_POST['email']) ? trim($_POST['email']) : null;
    $password = isset($_POST['password']) ? trim($_POST['password']) : null;
    $role = isset($_POST['role']) ? trim($_POST['role']) : null;

    // Check if admin or user wants to edit their own profile
    if ($id === null || ($id > 0 && $id == $_SESSION['user_id'])) {
        // Prepare SQL query
        $stmt = $pdo->prepare('UPDATE users SET name = :name, email = :email, password = :password, role = :role WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':role', $role);
        $stmt->execute();

        // Return success message
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'User updated successfully'));
    } else {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // Validate and sanitize input
    $id = isset($_GET['id']) ? intval($_GET['id']) : null;

    // Check if admin or user wants to delete their own profile
    if ($id === null || ($id > 0 && $id == $_SESSION['user_id'])) {
        // Prepare SQL query
        $stmt = $pdo->prepare('DELETE FROM users WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // Return success message
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'User deleted successfully'));
    } else {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
    }
}