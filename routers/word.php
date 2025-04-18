<?php
// GET /word/{nb}/{from} - Get words
$nb = isset($segments[1]) ? $segments[1] : '10';
$from = isset($segments[2]) ? $segments[2] : '1';

include __DIR__ . '/../functions/word/get_words.php';