<?php
session_start();

require_once __DIR__ . '/utilities/db_conn.php';
require_once __DIR__ . '/utilities/check_player.php';

// Set default content type to JSON
header('Content-Type: application/json');

// Get the request URI and remove query string
$request_uri = $_SERVER['REQUEST_URI'];
$request_uri = strtok($request_uri, '?');

// Remove base path from the URI
$base_path = '/';
if (strpos($request_uri, $base_path) === 0) {
    $request_uri = substr($request_uri, strlen($base_path));
}

// Remove leading/trailing slashes and split into segments
$path = trim($request_uri, '/');
$segments = !empty($path) ? explode('/', $path) : [];

// Get HTTP method
$method = $_SERVER['REQUEST_METHOD'];

// Extract the main resource (first segment)
$resource = isset($segments[0]) ? $segments[0] : '';

// Route the request based on the resource and HTTP method
switch ($resource) {
    case 'gamers':

        $player = isset($segments[1]) ? $segments[1] : '';

        if ($player === 'add' && isset($segments[2]) && isset($segments[3])) {
            // GET /gamers/add/{joueur}/{pwd} - Add a player
            $joueur = $segments[2];
            $pwd = $segments[3];

            include __DIR__ . '/functions/gamers/add_player.php';
            exit;
        } else if ($player === 'login' && isset($segments[2]) && isset($segments[3])) {
            // GET /gamers/login/{joueur}/{pwd} - Login a player
            $joueur = $segments[2];
            $pwd = $segments[3];

            include __DIR__ . '/functions/gamers/login_player.php';
            exit;
        } else if ($player === 'logout' && isset($segments[2]) && isset($segments[3])) {
            // GET /gamers/logout/{joueur}/{pwd} - Logout a player
            $joueur = $segments[2];
            $pwd = $segments[3];

            include __DIR__ . '/functions/gamers/logout_player.php';
            exit;
        } else {
            // GET /gamers/{joueur} - Get player info
            $joueur = $player;

            include __DIR__ . '/functions/gamers/get_player.php';
            exit;
        }

    case 'admin':
        $action = isset($segments[1]) ? $segments[1] : '';

        if ($action === 'top') {
            // GET /admin/top/{nb} - Get top players
            $nb = isset($segments[2]) ? $segments[2] : '10';

            include __DIR__ . '/functions/admin/get_top_players.php';
            exit;
        } else if ($action === 'delete' && isset($segments[2])) {
            $type = $segments[2];

            if ($type === 'joueur' && isset($segments[3])) {
                // GET /admin/delete/joueur/{joueur} - Delete a player
                $joueur = $segments[3];

                include __DIR__ . '/functions/admin/delete_player.php';
                exit;
            } else if ($type === 'def' && isset($segments[3])) {
                // GET /admin/delete/def/{id} - Delete a definition
                $id = $segments[3];

                include __DIR__ . '/functions/admin/delete_def.php';
                exit;
            }
        }
        break;

    case 'word':
        // GET /word/{nb}/{from} - Get words
        $nb = isset($segments[1]) ? $segments[1] : '10';
        $from = isset($segments[2]) ? $segments[2] : '1';

        include __DIR__ . '/functions/word/get_words.php';
        break;

    case 'jeu':
        // Change content type to HTML for game pages
        header('Content-Type: text/html');

        $game_type = isset($segments[1]) ? $segments[1] : '';

        if ($game_type === 'word') {
            // GET /jeu/word/{lg}/{time}/{hint} - Word guessing game
            $language = isset($segments[2]) ? $segments[2] : 'en';
            $time = isset($segments[3]) ? intval($segments[3]) : 60;
            $hint_interval = isset($segments[4]) ? intval($segments[4]) : 10;

            // Include the game view
            include __DIR__ . '/views/jeu/word/word_game.php';
            exit;
        } else if ($game_type === 'def') {
            // GET /jeu/def/{lg}/{time} - Definition game
            $language = isset($segments[2]) ? $segments[2] : 'en';
            $time = isset($segments[3]) ? intval($segments[3]) : 60;

            // Include the game view
            include __DIR__ . '/views/jeu/def/def_game.php';
            exit;
        }
        break;

    case 'dump':
        // GET /dump/{step} - DataTable view of definitions
        header('Content-Type: text/html');
        $step = isset($segments[1]) ? intval($segments[1]) : 10;

        // Include the dump view
        include __DIR__ . '/views/dump/dump.php';
        exit;

    case 'doc':
        // GET /doc - Documentation page
        header('Content-Type: text/html');
        include __DIR__ . '/views/doc/documentation.php';
        exit;

    case 'api':
        $api_type = isset($segments[1]) ? $segments[1] : '';

        if ($api_type === 'definitions') {
            include __DIR__ . '/functions/api/get_definitions.php';
            exit;
        }
        break;

    default:
        // If no route matches, return 404
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Endpoint not found']);
        break;
}