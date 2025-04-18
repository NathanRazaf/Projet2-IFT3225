<?php
// Extract the sub-resource (second segment)
$action = isset($segments[1]) ? $segments[1] : '';

if ($action === 'top') {
    // GET /admin/top/{nb} - Get top players
    $nb = isset($segments[2]) ? $segments[2] : '10';

    include __DIR__ . '/../functions/admin/get_top_players.php';
    exit;
} else if ($action === 'delete' && isset($segments[2])) {
    $type = $segments[2];

    if ($type === 'joueur' && isset($segments[3])) {
        // GET /admin/delete/joueur/{joueur} - Delete a player
        $joueur = $segments[3];

        include __DIR__ . '/../functions/admin/delete_player.php';
        exit;
    } else if ($type === 'def' && isset($segments[3])) {
        // GET /admin/delete/def/{id} - Delete a definition
        $id = $segments[3];

        include __DIR__ . '/../functions/admin/delete_def.php';
        exit;
    }
}