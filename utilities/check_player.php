<?php

function checkPlayer($conn, $joueur, $pwd) {
    try {
        // Check if player exists
        $stmt = $conn->prepare("SELECT player_id, password FROM players WHERE login = ?");
        $stmt->bind_param("s", $joueur);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            // Player doesn't exist
            return ['status' => 'error', 'message' => 'Player not found'];
        }

        // Check if the password is correct
        $player = $result->fetch_assoc();
        if (!password_verify($pwd, $player['password'])) {
            // Invalid password
            return ['status' => 'error', 'message' => 'Invalid password'];
        }

        // Return player data if everything is correct
        return ['status' => 'success', 'player' => $player];
    } catch (Exception $e) {
        // Handle any errors
        return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
    }
}
?>
