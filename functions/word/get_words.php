<?php
// Validate input (should be a number)
if (!is_numeric($nb) || !is_numeric($from)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid number']);
    exit;
}

try {
    // Get the definitions of the $nb words starting with the one with id $from
    $sql = "SELECT w.id, w.language, w.word, GROUP_CONCAT(wd.definition SEPARATOR '||') as definitions
        FROM words w
        LEFT JOIN word_definitions wd ON w.id = wd.word_id
        WHERE w.id >= ?
        GROUP BY w.id
        ORDER BY w.id
        LIMIT ?";

    // Execute the query
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $from, $nb);
    $stmt->execute();
    $result = $stmt->get_result();

    // Process the results
    $words = [];
    while ($row = $result->fetch_assoc()) {
        // Split the concatenated definitions into an array
        $definitionsList = explode('||', $row['definitions']);

        // Add to words array
        $words[] = [
            'word' => $row['word'],
	    'id' => $row['id'],
            'def' => $definitionsList
        ];
    }

    // Return the results as JSON
    echo json_encode($words);
} catch (Exception $e) {
    // Handle any errors
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    exit;
}
