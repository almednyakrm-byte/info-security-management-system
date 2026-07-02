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
try {
    $pdo = new PDO($dsn, DB_USER, DB_PASSWORD, $options);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Connection failed: ' . $e->getMessage()]);
    exit;
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'You must be logged in to access this resource.']);
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);
if (empty($input)) {
    $input = $_POST;
}

// GET all permissions
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Validate and sanitize input
    $limit = isset($input['limit']) ? (int) $input['limit'] : 10;
    $offset = isset($input['offset']) ? (int) $input['offset'] : 0;

    // Prepare SQL query
    $sql = 'SELECT * FROM permissions LIMIT :limit OFFSET :offset';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

    // Execute query
    $stmt->execute();

    // Process output
    $permissions = $stmt->fetchAll();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($permissions);
    exit;
}

// POST new permission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'You do not have permission to create new permissions.']);
        exit;
    }

    // Validate and sanitize input
    $name = isset($input['name']) ? trim($input['name']) : '';
    $description = isset($input['description']) ? trim($input['description']) : '';

    // Check for empty fields
    if (empty($name) || empty($description)) {
        http_response_code(400);
        echo json_encode(['error' => 'Name and description are required.']);
        exit;
    }

    // Prepare SQL query
    $sql = 'INSERT INTO permissions (name, description) VALUES (:name, :description)';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);

    // Execute query
    if ($stmt->execute()) {
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Permission created successfully.']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to create permission.']);
    }
    exit;
}

// PUT existing permission
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'You do not have permission to update permissions.']);
        exit;
    }

    // Validate and sanitize input
    $id = isset($input['id']) ? (int) $input['id'] : 0;
    $name = isset($input['name']) ? trim($input['name']) : '';
    $description = isset($input['description']) ? trim($input['description']) : '';

    // Check for empty fields
    if (empty($id) || empty($name) || empty($description)) {
        http_response_code(400);
        echo json_encode(['error' => 'ID, name, and description are required.']);
        exit;
    }

    // Prepare SQL query
    $sql = 'UPDATE permissions SET name = :name, description = :description WHERE id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);

    // Execute query
    if ($stmt->execute()) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Permission updated successfully.']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update permission.']);
    }
    exit;
}

// DELETE existing permission
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'You do not have permission to delete permissions.']);
        exit;
    }

    // Validate and sanitize input
    $id = isset($input['id']) ? (int) $input['id'] : 0;

    // Check for empty fields
    if (empty($id)) {
        http_response_code(400);
        echo json_encode(['error' => 'ID is required.']);
        exit;
    }

    // Prepare SQL query
    $sql = 'DELETE FROM permissions WHERE id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    // Execute query
    if ($stmt->execute()) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Permission deleted successfully.']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to delete permission.']);
    }
    exit;
}

// Invalid request method
http_response_code(405);
echo json_encode(['error' => 'Invalid request method.']);