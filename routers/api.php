<?php
$api_type = isset($segments[1]) ? $segments[1] : '';

if ($api_type === 'definitions') {
    include __DIR__ . '/../functions/api/get_definitions.php';
    exit;
}