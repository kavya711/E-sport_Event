<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Check if a normal user is logged in
$is_logged_in = isset($_SESSION["user"]);
$user_data = $is_logged_in ? $_SESSION["user"] : null;

// Optionally check if admin is logged in
$is_admin = isset($_SESSION["admin_logged_in"]) && $_SESSION["admin_logged_in"] === true;

echo json_encode([
    "status" => "success",
    "is_logged_in" => $is_logged_in,
    "user" => $user_data,
    "is_admin" => $is_admin
]);
exit;
