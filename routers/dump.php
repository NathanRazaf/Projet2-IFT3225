<?php
// GET /dump/{step} - DataTable view of definitions
header('Content-Type: text/html');
$step = isset($segments[1]) ? intval($segments[1]) : 10;

// Include the dump view
include __DIR__ . '/../views/dump/dump.php';