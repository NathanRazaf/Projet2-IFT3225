<?php
if (empty($joueur) || !is_numeric($score)) {
    echo json_encode(['status' => 'error', 'message' => 'Missing player name or invalid score']);
    exit;
}

try {
    // Start a transaction for data consistency
    $conn->begin_transaction();

    // First, find the player
    $stmt = $conn->prepare("SELECT player_id, login, num_games_played, num_games_won, cumulated_score, last_login FROM players WHERE login = ?");
    $stmt->bind_param("s", $joueur);
    $stmt->execute();
    $result = $stmt->get_result();
    $player = $result->fetch_assoc();

    if (!$player) {
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => 'Player not found']);
        exit;
    }

    // Update player stats
    $newGamesPlayed = $player['num_games_played'] + 1;
    $newScore = $player['cumulated_score'] + intval($score);

    // Increment games won if score is positive
    $newGamesWon = $player['num_games_won'];
    if (intval($score) > 0) {
        $newGamesWon += 1;
    }

    // Update the player's record
    $update = $conn->prepare("UPDATE players SET num_games_played = ?, num_games_won = ?, cumulated_score = ? WHERE player_id = ?");
    $update->bind_param("iiii", $newGamesPlayed, $newGamesWon, $newScore, $player['player_id']);
    $success = $update->execute();

    if (!$success) {
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => 'Failed to update player stats']);
        exit;
    }

    // Commit the transaction
    $conn->commit();

    // Return the updated player data
    echo json_encode([
        'status' => 'success',
        'message' => 'Player stats updated successfully',
        'data' => [
            'login' => $player['login'],
            'num_games_played' => $newGamesPlayed,
            'num_games_won' => $newGamesWon,
            'cumulated_score' => $newScore,
            'last_login' => $player['last_login']
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