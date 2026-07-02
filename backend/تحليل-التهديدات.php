<?php
require_once 'db.php';

// Get user role and ID from session
$userRole = $_SESSION['userRole'];
$userID = $_SESSION['userID'];

// Check if user is logged in
if (!$userID) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $stmt = $pdo->prepare('SELECT * FROM تحليل_التهديدات');
    $stmt->execute();
    $threats = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($threats);
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Validate input
    $requiredFields = array('name', 'description', 'risk_level');
    foreach ($requiredFields as $field) {
        if (!isset($input[$field]) || empty($input[$field])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Missing required field: ' . $field));
            exit;
        }
    }

    // Sanitize input
    $input['name'] = trim($input['name']);
    $input['description'] = trim($input['description']);

    // Prepare insert statement
    $stmt = $pdo->prepare('INSERT INTO تحليل_التهديدات (name, description, risk_level, created_by) VALUES (:name, :description, :risk_level, :created_by)');
    $stmt->bindParam(':name', $input['name']);
    $stmt->bindParam(':description', $input['description']);
    $stmt->bindParam(':risk_level', $input['risk_level']);
    $stmt->bindParam(':created_by', $userID);

    // Execute insert statement
    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(array('message' => 'Threat analysis created successfully'));
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Failed to create threat analysis'));
    }
}

// Handle PUT request
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Validate input
    $requiredFields = array('id', 'name', 'description', 'risk_level');
    foreach ($requiredFields as $field) {
        if (!isset($input[$field]) || empty($input[$field])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Missing required field: ' . $field));
            exit;
        }
    }

    // Sanitize input
    $input['name'] = trim($input['name']);
    $input['description'] = trim($input['description']);

    // Check if user is admin
    if ($userRole != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Prepare update statement
    $stmt = $pdo->prepare('UPDATE تحليل_التهديدات SET name = :name, description = :description, risk_level = :risk_level, updated_by = :updated_by WHERE id = :id');
    $stmt->bindParam(':id', $input['id']);
    $stmt->bindParam(':name', $input['name']);
    $stmt->bindParam(':description', $input['description']);
    $stmt->bindParam(':risk_level', $input['risk_level']);
    $stmt->bindParam(':updated_by', $userID);

    // Execute update statement
    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(array('message' => 'Threat analysis updated successfully'));
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Failed to update threat analysis'));
    }
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Validate input
    if (!isset($input['id']) || empty($input['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Missing required field: id'));
        exit;
    }

    // Check if user is admin
    if ($userRole != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Prepare delete statement
    $stmt = $pdo->prepare('DELETE FROM تحليل_التهديدات WHERE id = :id');
    $stmt->bindParam(':id', $input['id']);

    // Execute delete statement
    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(array('message' => 'Threat analysis deleted successfully'));
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Failed to delete threat analysis'));
    }
}