<?php
// GET /word/{nb}/{from} - Get words
// or GET /word/random - Get random word

// Check if the request is for a random word
if (isset($segments[1]) && $segments[1] === 'random') {
    include __DIR__ . '/../functions/word/get_random_word.php';
    exit;
} else {
    // Original behavior - get words by range
    $nb = isset($segments[1]) ? $segments[1] : '10';
    $from = isset($segments[2]) ? $segments[2] : '1';

    include __DIR__ . '/../functions/word/get_words.php';
    exit;
}