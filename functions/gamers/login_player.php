<?php

try {
    $checkResult = checkPlayer($conn, $joueur, $pwd);

    if ($checkResult['status'] == 'error') {
        // Return error if user check failed
        echo json_encode($checkResult);
        exit;
    }

    $player = $checkResult['player'];

    // Set cookies to log the player in (expire in 1 day)
    setcookie('player_id', $player['player_id'], time() + 86400, '/');
    setcookie('player_login', $joueur, time() + 86400, '/');

    // Update last_login time
    $stmt = $conn->prepare("UPDATE players SET last_login = NOW() WHERE player_id = ?");
    $stmt->bind_param("i", $player['player_id']);
    $stmt->execute();

    // Return success with the player ID
    echo json_encode([
        'status' => 'success',
        'id' => $player['player_id'],
        'message' => 'Player logged in successfully'
    ]);
} catch (Exception $e) {
    // Handle any errors
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
