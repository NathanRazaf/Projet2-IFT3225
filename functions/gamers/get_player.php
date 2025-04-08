<?php
if (empty($joueur)) {
    echo json_encode(['status' => 'error', 'message' => 'Missing player name']);
    exit;
}
 
try {
    $stmt = $conn->prepare("SELECT login, num_games_played, num_games_won, cumulated_score, last_login FROM players WHERE login = ?");
    $stmt->bind_param("s", $joueur);
    $stmt->execute();
    $result = $stmt->get_result();
    $player = $result->fetch_assoc();

    if (!$player) {
        echo json_encode(['status' => 'error', 'message' => 'Player not found']);
        exit;
    }

    echo json_encode(['status' => 'success', 'data' => $player]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    exit;
}
