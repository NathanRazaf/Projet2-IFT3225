<?php
 
require_once '../../user_functions.php';
require_once '../../verify_player.php';

try {
    $checkResult = checkPlayer($conn, $joueur, $pwd);

    if ($checkResult['status'] == 'error') {
        // Return error if user check failed
        echo json_encode($checkResult);
        exit;
    }

    $player = $checkResult['player'];

    // Remove cookies
    setcookie('player_id', '', time() - 3600, '/');
    setcookie('player_login', '', time() - 3600, '/');

    // Return success with the player ID
    echo json_encode([
        'status' => 'success',
        'id' => $player['player_id'],
        'message' => 'Player logged out successfully'
    ]);
} catch (Exception $e) {
    // Handle any errors
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
