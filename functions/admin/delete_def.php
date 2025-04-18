<?php
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

