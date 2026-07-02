<?php

require_once 'db.php';

// Get user role and ID from session
$userRole = $_SESSION['userRole'];
$userID = $_SESSION['userID'];

// Get input data from JSON or POST
$inputData = json_decode(file_get_contents('php://input'), true);
if (!$inputData) {
    $inputData = $_POST;
}

// Define constants for HTTP status codes and headers
define('HTTP_CREATED', 201);
define('HTTP_OK', 200);
define('HTTP_BAD_REQUEST', 400);
define('HTTP_UNAUTHORIZED', 401);
define('HTTP_NOT_FOUND', 404);
define('HTTP_METHOD_NOT_ALLOWED', 405);
define('HTTP_INTERNAL_SERVER_ERROR', 500);

// Define constants for database table and columns
define('TABLE_NAME', 'ثوابت_الأمان');
define('COLUMN_ID', 'id');
define('COLUMN_NAME', 'name');
define('COLUMN_DESCRIPTION', 'description');

// Define function for CRUD operations
function crudOperation($method, $id = null) {
    global $pdo, $inputData, $userRole, $userID;

    // Check if user is logged in
    if (!$userRole) {
        http_response_code(HTTP_UNAUTHORIZED);
        echo json_encode(['error' => 'Unauthorized']);
        return;
    }

    // Check if user is admin for edits/deletions
    if ($method === 'PUT' || $method === 'DELETE') {
        if ($userRole !== 'admin') {
            http_response_code(HTTP_UNAUTHORIZED);
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }
    }

    // Validate and sanitize input data
    if ($method === 'POST') {
        if (!isset($inputData[COLUMN_NAME]) || !isset($inputData[COLUMN_DESCRIPTION])) {
            http_response_code(HTTP_BAD_REQUEST);
            echo json_encode(['error' => 'Invalid input data']);
            return;
        }
        $name = filter_var($inputData[COLUMN_NAME], FILTER_SANITIZE_STRING);
        $description = filter_var($inputData[COLUMN_DESCRIPTION], FILTER_SANITIZE_STRING);
    } elseif ($method === 'PUT') {
        if (!isset($inputData[COLUMN_NAME]) || !isset($inputData[COLUMN_DESCRIPTION])) {
            http_response_code(HTTP_BAD_REQUEST);
            echo json_encode(['error' => 'Invalid input data']);
            return;
        }
        $name = filter_var($inputData[COLUMN_NAME], FILTER_SANITIZE_STRING);
        $description = filter_var($inputData[COLUMN_DESCRIPTION], FILTER_SANITIZE_STRING);
    }

    // Prepare SQL query
    if ($method === 'GET') {
        $query = "SELECT * FROM " . TABLE_NAME;
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll();
    } elseif ($method === 'POST') {
        $query = "INSERT INTO " . TABLE_NAME . " (" . COLUMN_NAME . ", " . COLUMN_DESCRIPTION . ") VALUES (:name, :description)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->execute();
        $id = $pdo->lastInsertId();
    } elseif ($method === 'PUT') {
        $query = "UPDATE " . TABLE_NAME . " SET " . COLUMN_NAME . " = :name, " . COLUMN_DESCRIPTION . " = :description WHERE " . COLUMN_ID . " = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    } elseif ($method === 'DELETE') {
        $query = "DELETE FROM " . TABLE_NAME . " WHERE " . COLUMN_ID . " = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    // Process output
    if ($method === 'GET') {
        http_response_code(HTTP_OK);
        header('Content-Type: application/json');
        echo json_encode($result);
    } elseif ($method === 'POST') {
        http_response_code(HTTP_CREATED);
        header('Content-Type: application/json');
        echo json_encode(['id' => $id]);
    } elseif ($method === 'PUT' || $method === 'DELETE') {
        http_response_code(HTTP_OK);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Operation successful']);
    }
}

// Handle HTTP requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    crudOperation('GET');
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    crudOperation('POST');
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $id = filter_var($_GET[COLUMN_ID], FILTER_SANITIZE_NUMBER_INT);
    crudOperation('PUT', $id);
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = filter_var($_GET[COLUMN_ID], FILTER_SANITIZE_NUMBER_INT);
    crudOperation('DELETE', $id);
} else {
    http_response_code(HTTP_METHOD_NOT_ALLOWED);
    echo json_encode(['error' => 'Method not allowed']);
}