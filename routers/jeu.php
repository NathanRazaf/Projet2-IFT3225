<?php
// Change content type to HTML for game pages
header('Content-Type: text/html');

$game_type = isset($segments[1]) ? $segments[1] : '';

if ($game_type === 'word') {
    // GET /jeu/word/{lg}/{time}/{hint} - Word guessing game
    $language = isset($segments[2]) ? $segments[2] : 'en';
    $time = isset($segments[3]) ? intval($segments[3]) : 60;
    $hint_interval = isset($segments[4]) ? intval($segments[4]) : 10;

    // Include the game view
    include __DIR__ . '/../views/jeu/word/word_game.php';
    exit;
} else if ($game_type === 'def') {
    // GET /jeu/def/{lg}/{time} - Definition game
    $language = isset($segments[2]) ? $segments[2] : 'en';
    $time = isset($segments[3]) ? intval($segments[3]) : 60;

    // Include the game view
    include __DIR__ . '/../views/jeu/def/def_game.php';
    exit;
}