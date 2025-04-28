<?php
// Extract the sub-resource (second segment)
$player = isset($segments[1]) ? $segments[1] : '';

if ($player === 'add' && isset($segments[2]) && isset($segments[3])) {
    // GET /gamers/add/{joueur}/{pwd} - Add a player
    $joueur = $segments[2];
    $pwd = $segments[3];

    include __DIR__ . '/../functions/gamers/add_player.php';
    exit;
} else if ($player === 'login' && isset($segments[2]) && isset($segments[3])) {
    // GET /gamers/login/{joueur}/{pwd} - Login a player
    $joueur = $segments[2];
    $pwd = $segments[3];

    include __DIR__ . '/../functions/gamers/login_player.php';
    exit;
} else if ($player === 'logout' && isset($segments[2]) && isset($segments[3])) {
    // GET /gamers/logout/{joueur}/{pwd} - Logout a player
    $joueur = $segments[2];
    $pwd = $segments[3];

    include __DIR__ . '/../functions/gamers/logout_player.php';
    exit;
} else {
    // GET /gamers/{joueur} - Get player info
    $joueur = $player;

    include __DIR__ . '/../functions/gamers/get_player.php';
    exit;
}