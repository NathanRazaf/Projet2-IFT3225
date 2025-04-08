<?php
if (empty($joueur)) {
    echo json_encode(['status' => 'error', 'message' => 'Missing player username']);
    exit;
}

try {
    $get_player_id = $conn->prepare("SELECT player_id FROM players WHERE login = ?");
    $get_player_id->bind_param("s", $joueur);
    $get_player_id->execute();
    $result = $get_player_id->get_result();

    if ($result->num_rows == 0) {
        echo json_encode(['status' => 'error', 'message' => 'Player not found']);
        exit;
    }

    $player = $result->fetch_assoc();

    $stmt = $conn->prepare("DELETE FROM players WHERE player_id = ?");
    $stmt->bind_param("i", $player['player_id']);
    $stmt->execute();

    echo json_encode(
        ['status' => 'success',
        'message' => 'Player deleted successfully',
            'player_id' => $player['player_id']]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    exit;
}
