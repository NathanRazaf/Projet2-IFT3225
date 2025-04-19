<?php
// Get a language parameter if provided
$language = isset($_GET['language']) ? $_GET['language'] : null;

try {
    // Get the total number of words
    $countSql = "SELECT COUNT(*) as total FROM words";
    $params = [];
    $types = "";

    // Add language filter if specified
    if ($language) {
        $countSql .= " WHERE language = ?";
        $params[] = $language;
        $types .= "s";
    }

    // Execute the count query
    $countStmt = $conn->prepare($countSql);
    if (!empty($params)) {
        $countStmt->bind_param($types, ...$params);
    }
    $countStmt->execute();
    $totalWords = $countStmt->get_result()->fetch_assoc()['total'];

    if ($totalWords === 0) {
        echo json_encode(['status' => 'error', 'message' => 'No words found']);
        exit;
    }

    // Get a random offset
    $randomOffset = mt_rand(0, $totalWords - 1);

    // Query to get a random word
    $sql = "SELECT w.id, w.language, w.word, GROUP_CONCAT(wd.definition SEPARATOR '||') as definitions 
            FROM words w
            LEFT JOIN word_definitions wd ON w.id = wd.word_id";

    // Add language filter if specified
    if ($language) {
        $sql .= " WHERE w.language = ?";
    }

    $sql .= " GROUP BY w.id
              LIMIT 1 OFFSET ?";

    // Execute the query
    $stmt = $conn->prepare($sql);
    if ($language) {
        $stmt->bind_param("si", $language, $randomOffset);
    } else {
        $stmt->bind_param("i", $randomOffset);
    }
    $stmt->execute();

    // Process the result
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Split the concatenated definitions into an array
        $definitionsList = explode('||', $row['definitions']);

        // Create word object
        $word = [
            'word' => $row['word'],
            'id' => $row['id'],
            'def' => $definitionsList
        ];

        // Return as JSON
        echo json_encode([$word]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No words found']);
    }
} catch (Exception $e) {
    // Handle any errors
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    exit;
}