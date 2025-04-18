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

// Route the request based on the resource
switch ($resource) {
    case 'gamers':
        include __DIR__ . '/routers/gamers.php';
        exit;
    case 'admin':
        include __DIR__ . '/routers/admin.php';
        exit;
    case 'word':
        include __DIR__ . '/routers/word.php';
        exit;
    case 'jeu':
        include __DIR__ . '/routers/jeu.php';
        exit;
    case 'dump':
        include __DIR__ . '/routers/dump.php';
        exit;
    case 'doc':
        include __DIR__ . '/routers/doc.php';
        exit;
    case 'api':
        include __DIR__ . '/routers/api.php';
        exit;
    default:
        // If no route matches, return 404
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Endpoint not found']);
        break;
}