<?php
$api_type = isset($segments[1]) ? $segments[1] : '';

if ($api_type === 'definitions') {
    include __DIR__ . '/../functions/api/get_definitions.php';
    exit;
} else if ($api_type === 'score' && isset($segments[2]) && isset($segments[3])) {
    // POST /gamers/score/{joueur}/{score} - Update player score
    $joueur = $segments[2];
    $score = $segments[3];

    include __DIR__ . '/../functions/api/add_score.php';
    exit;
}