<?php
try {
    // Check if player already exists
    $stmt = $conn->prepare("SELECT player_id FROM players WHERE login = ?");
    $stmt->bind_param("s", $joueur);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Player already exists
        echo json_encode(['status' => 'error', 'message' => 'Player already exists']);
        exit;
    }

    // Player doesn't exist, insert new player
    // Hash the password for security
    $hashed_password = password_hash($pwd, PASSWORD_DEFAULT);

    // Insert the new player
    $stmt = $conn->prepare("INSERT INTO players (login, password, last_login) VALUES (?, ?, NOW())");
    $stmt->bind_param("ss", $joueur, $hashed_password);
    $stmt->execute();

    // Get the newly inserted player's ID
    $player_id = $conn->insert_id;

    // Set cookies to maintain session
    // Set cookie to expire in 1 day (86400 seconds)
    setcookie('player_id', $player_id, time() + 86400, '/');
    setcookie('player_login', $joueur, time() + 86400, '/');

    // Update last_login time
    $stmt = $conn->prepare("UPDATE players SET last_login = NOW() WHERE player_id = ?");
    $stmt->bind_param("i", $player_id);
    $stmt->execute();

    // Return success with the player ID
    echo json_encode([
        'status' => 'success',
        'id' => $player_id,
        'message' => 'Player created and logged in successfully'
    ]);

} catch (Exception $e) {
    // Handle any errors
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}
