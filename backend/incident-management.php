<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Check if user is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    http_response_code(403);
    echo json_encode(array('error' => 'Forbidden'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if (isset($_GET['action']) && $_GET['action'] == 'get_all') {
    $stmt = $pdo->prepare('SELECT * FROM incident_management');
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($data);
} elseif (isset($_GET['action']) && $_GET['action'] == 'get_one') {
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }
    $stmt = $pdo->prepare('SELECT * FROM incident_management WHERE id = :id');
    $stmt->bindParam(':id', $input['id']);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($data) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($data);
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'Not found'));
    }
}

// Handle POST request
elseif (isset($_GET['action']) && $_GET['action'] == 'create') {
    if (!isset($input['incident_type']) || !isset($input['incident_description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }
    $input['incident_type'] = htmlspecialchars($input['incident_type']);
    $input['incident_description'] = htmlspecialchars($input['incident_description']);
    $stmt = $pdo->prepare('INSERT INTO incident_management (incident_type, incident_description) VALUES (:incident_type, :incident_description)');
    $stmt->bindParam(':incident_type', $input['incident_type']);
    $stmt->bindParam(':incident_description', $input['incident_description']);
    $stmt->execute();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Created successfully'));
}

// Handle PUT request
elseif (isset($_GET['action']) && $_GET['action'] == 'update') {
    if (!isset($input['id']) || !isset($input['incident_type']) || !isset($input['incident_description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }
    $input['incident_type'] = htmlspecialchars($input['incident_type']);
    $input['incident_description'] = htmlspecialchars($input['incident_description']);
    $stmt = $pdo->prepare('UPDATE incident_management SET incident_type = :incident_type, incident_description = :incident_description WHERE id = :id');
    $stmt->bindParam(':id', $input['id']);
    $stmt->bindParam(':incident_type', $input['incident_type']);
    $stmt->bindParam(':incident_description', $input['incident_description']);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Updated successfully'));
}

// Handle DELETE request
elseif (isset($_GET['action']) && $_GET['action'] == 'delete') {
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }
    $stmt = $pdo->prepare('DELETE FROM incident_management WHERE id = :id');
    $stmt->bindParam(':id', $input['id']);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Deleted successfully'));
}

// Handle invalid requests
else {
    http_response_code(400);
    echo json_encode(array('error' => 'Invalid request'));
}
?>