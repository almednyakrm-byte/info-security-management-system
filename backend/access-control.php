<?php
// Import database connection
require_once 'db.php';

// Initialize database connection
$dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
$pdo = new PDO($dsn, DB_USER, DB_PASSWORD, $options);

// Function to check if user is logged in
function isLoggedIn() {
    // Implement your own logic to check if user is logged in
    // For demonstration purposes, assume a session variable 'logged_in' is set
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

// Function to check if user is admin
function isAdmin() {
    // Implement your own logic to check if user is admin
    // For demonstration purposes, assume a session variable 'role' is set
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Handle HTTP requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Validate and sanitize input
    $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

    // Check if user is logged in
    if (!isLoggedIn()) {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // SQL query structure: Select all or by id
    $sql = 'SELECT * FROM access_control';
    if ($id !== null) {
        $sql .= ' WHERE id = :id';
    }

    // Prepare and execute query
    $stmt = $pdo->prepare($sql);
    if ($id !== null) {
        $stmt->bindParam(':id', $id);
    }
    $stmt->execute();

    // Output processing
    $result = $stmt->fetchAll();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($result);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $data = json_decode(file_get_contents('php://input'), true);
    $name = filter_var($data['name'] ?? null, FILTER_SANITIZE_STRING);
    $description = filter_var($data['description'] ?? null, FILTER_SANITIZE_STRING);

    // Check if user is logged in and admin
    if (!isLoggedIn() || !isAdmin()) {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Check if required fields are present
    if ($name === null || $description === null) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Missing required fields']);
        exit;
    }

    // SQL query structure: Insert
    $sql = 'INSERT INTO access_control (name, description) VALUES (:name, :description)';

    // Prepare and execute query
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    // Output processing
    $id = $pdo->lastInsertId();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['id' => $id, 'name' => $name, 'description' => $description]);
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Validate and sanitize input
    $data = json_decode(file_get_contents('php://input'), true);
    $id = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT);
    $name = filter_var($data['name'] ?? null, FILTER_SANITIZE_STRING);
    $description = filter_var($data['description'] ?? null, FILTER_SANITIZE_STRING);

    // Check if user is logged in and admin
    if (!isLoggedIn() || !isAdmin()) {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Check if required fields are present
    if ($id === null || $name === null || $description === null) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Missing required fields']);
        exit;
    }

    // SQL query structure: Update
    $sql = 'UPDATE access_control SET name = :name, description = :description WHERE id = :id';

    // Prepare and execute query
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    // Output processing
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['id' => $id, 'name' => $name, 'description' => $description]);
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Validate and sanitize input
    $data = json_decode(file_get_contents('php://input'), true);
    $id = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT);

    // Check if user is logged in and admin
    if (!isLoggedIn() || !isAdmin()) {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Check if required fields are present
    if ($id === null) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Missing required fields']);
        exit;
    }

    // SQL query structure: Delete
    $sql = 'DELETE FROM access_control WHERE id = :id';

    // Prepare and execute query
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Output processing
    http_response_code(204);
    header('Content-Type: application/json');
} else {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Method not allowed']);
}