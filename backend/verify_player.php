<?php

require_once 'db_conn.php';

header('Content-Type: application/json');

// Get parameters from the request
$joueur = isset($_GET['joueur']) ? $_GET['joueur'] : '';
$pwd = isset($_GET['pwd']) ? $_GET['pwd'] : '';

// Validate input
if (empty($joueur) || empty($pwd)) {
    echo json_encode(['status' => 'error', 'message' => 'Missing username or password']);
    exit;
}
