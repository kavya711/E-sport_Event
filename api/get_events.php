<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$eventsFile = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'events.json';

if (file_exists($eventsFile)) {
    $events = json_decode(file_get_contents($eventsFile), true) ?: [];
    echo json_encode(["status" => "success", "data" => $events]);
} else {
    echo json_encode(["status" => "success", "data" => []]);
}
exit;
