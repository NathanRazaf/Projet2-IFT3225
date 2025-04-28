<?php
// Validate required parameters
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit;
}

// Check if player_login cookie exists
if (!isset($_COOKIE['player_login'])) {
    echo json_encode(['status' => 'error', 'message' => 'Missing player login. Please log in first.']);
    exit;
}

$login = $_COOKIE['player_login']; // Extract login from cookie

// Get JSON data from request body
$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

// Check if all required fields are present
if (empty($data) || !isset($data['word']) || !isset($data['new_def'])) {
    echo json_encode(['status' => 'error', 'message' => 'Missing required fields: word and new_def are required']);
    exit;
}

$word = $data['word'];
$new_def = $data['new_def'];

try {
    // Start a transaction for data consistency
    $conn->begin_transaction();

    // First, check if the player exists
    $player_stmt = $conn->prepare("SELECT player_id, cumulated_score FROM players WHERE login = ?");
    $player_stmt->bind_param("s", $login);
    $player_stmt->execute();
    $player_result = $player_stmt->get_result();
    $player = $player_result->fetch_assoc();

    if (!$player) {
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => 'Player not found']);
        exit;
    }

    // Check if the word exists, if not create it
    $word_stmt = $conn->prepare("SELECT id FROM words WHERE word = ?");
    $word_stmt->bind_param("s", $word);
    $word_stmt->execute();
    $word_result = $word_stmt->get_result();
    $word_row = $word_result->fetch_assoc();

    $word_id = null;
    if (!$word_row) {
        // Word doesn't exist, create it
        $insert_word_stmt = $conn->prepare("INSERT INTO words (word, language, source) VALUES (?, 'unknown', ?)");
        $insert_word_stmt->bind_param("ss", $word, $login);
        $insert_word_stmt->execute();
        $word_id = $conn->insert_id;
    } else {
        $word_id = $word_row['id'];
    }

    // Check if the definition already exists
    $def_check_stmt = $conn->prepare("SELECT def_id FROM word_definitions 
                                      WHERE word_id = ? AND definition = ?");
    $def_check_stmt->bind_param("is", $word_id, $new_def);
    $def_check_stmt->execute();
    $def_result = $def_check_stmt->get_result();

    if ($def_result->num_rows > 0) {
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => 'This definition already exists for the word']);
        exit;
    }

    // Add the new definition
    $insert_def_stmt = $conn->prepare("INSERT INTO word_definitions (word_id, definition) VALUES (?, ?)");
    $insert_def_stmt->bind_param("is", $word_id, $new_def);
    $success = $insert_def_stmt->execute();

    if (!$success) {
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => 'Failed to add new definition']);
        exit;
    }

    // Award 5 points to the player
    $new_score = $player['cumulated_score'] + 5;
    $update_player_stmt = $conn->prepare("UPDATE players SET cumulated_score = ? WHERE player_id = ?");
    $update_player_stmt->bind_param("ii", $new_score, $player['player_id']);
    $update_success = $update_player_stmt->execute();

    if (!$update_success) {
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => 'Failed to update player score']);
        exit;
    }

    // Commit the transaction
    $conn->commit();

    // Return success response
    echo json_encode([
        'status' => 'success',
        'message' => 'Definition added successfully',
        'data' => [
            'word' => $word,
            'definition' => $new_def,
            'source' => $login,
            'points_awarded' => 5,
            'player_new_score' => $new_score
        ]
    ]);

} catch (Exception $e) {
    // Rollback the transaction in case of error
    if ($conn->connect_errno) {
        $conn->rollback();
    }
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    exit;
}
