<?php
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.html");
    exit;
}

function sanitize($value)
{
    return trim(filter_var($value, FILTER_SANITIZE_FULL_SPECIAL_CHARS));
}

$teamName = isset($_POST["team_name"]) ? sanitize($_POST["team_name"]) : "";
$captainName = isset($_POST["captain_name"]) ? sanitize($_POST["captain_name"]) : "";
$email = isset($_POST["email"]) ? filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL) : "";
$discord = isset($_POST["discord"]) ? sanitize($_POST["discord"]) : "";
$game = isset($_POST["game"]) ? sanitize($_POST["game"]) : "";
$region = isset($_POST["region"]) ? sanitize($_POST["region"]) : "";
$teamSize = isset($_POST["team_size"]) ? sanitize($_POST["team_size"]) : "";
$notes = isset($_POST["notes"]) ? sanitize($_POST["notes"]) : "";
$acceptRules = isset($_POST["accept_rules"]) ? $_POST["accept_rules"] : "";

$hasError = false;

if ($teamName === "" || $captainName === "" || $email === "" || $game === "" || $region === "" || $teamSize === "") {
    $hasError = true;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $hasError = true;
}

if ($acceptRules !== "1") {
    $hasError = true;
}

if ($hasError) {
    header("Location: index.html?status=error");
    exit;
}

$entry = [
    "team_name" => $teamName,
    "captain_name" => $captainName,
    "email" => $email,
    "discord" => $discord,
    "game" => $game,
    "region" => $region,
    "team_size" => $teamSize,
    "notes" => $notes,
    "accepted_rules" => $acceptRules === "1",
    "submitted_at" => date("c"),
    "ip" => $_SERVER["REMOTE_ADDR"] ?? "",
    "user_agent" => $_SERVER["HTTP_USER_AGENT"] ?? "",
];

$storageDir = __DIR__ . DIRECTORY_SEPARATOR . "storage";
if (!is_dir($storageDir)) {
    mkdir($storageDir, 0775, true);
}

$filePath = $storageDir . DIRECTORY_SEPARATOR . "registrations.json";

if (!file_exists($filePath)) {
    file_put_contents($filePath, json_encode([$entry], JSON_PRETTY_PRINT));
} else {
    $contents = file_get_contents($filePath);
    $data = json_decode($contents, true);
    if (!is_array($data)) {
        $data = [];
    }
    $data[] = $entry;
    file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT));
}

header("Location: index.html?status=success");
exit;

