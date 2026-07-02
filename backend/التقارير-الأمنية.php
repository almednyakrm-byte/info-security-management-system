<?php
require_once 'db.php';

// Get user role and ID from session
$userRole = $_SESSION['userRole'];
$userID = $_SESSION['userID'];

// Get input data
$inputData = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if user is logged in
    if (!$userRole) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Get all reports
    $stmt = $pdo->prepare('SELECT * FROM تقارير_الأمنية');
    $stmt->execute();
    $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return reports
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($reports);
}

// Handle POST request
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if user is logged in
    if (!$userRole) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Validate input data
    if (!isset($inputData['title']) || !isset($inputData['description'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request data']);
        exit;
    }

    // Sanitize input data
    $title = filter_var($inputData['title'], FILTER_SANITIZE_STRING);
    $description = filter_var($inputData['description'], FILTER_SANITIZE_STRING);

    // Insert report
    $stmt = $pdo->prepare('INSERT INTO تقارير_الأمنية (title, description, created_by) VALUES (:title, :description, :created_by)');
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':created_by', $userID);
    $stmt->execute();

    // Return report ID
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['report_id' => $pdo->lastInsertId()]);
}

// Handle PUT request
elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Check if user is logged in and has admin role
    if (!$userRole || $userRole !== 'admin') {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Validate input data
    if (!isset($inputData['id']) || !isset($inputData['title']) || !isset($inputData['description'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request data']);
        exit;
    }

    // Sanitize input data
    $id = filter_var($inputData['id'], FILTER_SANITIZE_NUMBER_INT);
    $title = filter_var($inputData['title'], FILTER_SANITIZE_STRING);
    $description = filter_var($inputData['description'], FILTER_SANITIZE_STRING);

    // Update report
    $stmt = $pdo->prepare('UPDATE تقارير_الأمنية SET title = :title, description = :description, updated_by = :updated_by WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':updated_by', $userID);
    $stmt->execute();

    // Return success message
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Report updated successfully']);
}

// Handle DELETE request
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Check if user is logged in and has admin role
    if (!$userRole || $userRole !== 'admin') {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Validate input data
    if (!isset($inputData['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request data']);
        exit;
    }

    // Sanitize input data
    $id = filter_var($inputData['id'], FILTER_SANITIZE_NUMBER_INT);

    // Delete report
    $stmt = $pdo->prepare('DELETE FROM تقارير_الأمنية WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Return success message
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Report deleted successfully']);
}