<?php
 
require_once '../../../db_conn.php';

header('Content-Type: application/json');

// Get parameters from the request
$id = isset($_GET['id']) ? $_GET['id'] : '';

// Validate input
if (empty($id)) {
    echo json_encode(['status' => 'error', 'message' => 'Missing definition id']);
    exit;
}

try {
    $stmt = $conn->prepare("DELETE FROM word_definitions WHERE def_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    echo json_encode(['status' => 'success', 'message' => 'Definition deleted successfully']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    exit;
}

