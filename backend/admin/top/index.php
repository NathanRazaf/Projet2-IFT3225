<?php
 
require_once '../../db_conn.php';

header('Content-Type: application/json');

$nb = isset($_GET['nb']) ? $_GET['nb'] : '1';

// Validate input (should be a number)
if (!is_numeric($nb)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid number']);
    exit;
}

try {
    // Get the top $nb players with the highest scores
    $sql = "SELECT player_id, login, cumulated_score FROM players ORDER BY cumulated_score DESC LIMIT $nb";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        echo json_encode(['status' => 'error', 'message' => 'No players found']);
        exit;
    }
    
    $players = [];
    while ($row = $result->fetch_assoc()) {
        $players[] = [
            'id' => $row['player_id'],
            'login' => $row['login'],
            'score' => $row['cumulated_score']
        ];
    }
    
    echo json_encode(['status' => 'success', 'players' => $players]);
} catch (Exception $e) {
    // Handle any errors
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    exit;
}
