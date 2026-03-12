<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.html?need_login=1");
    exit;
}
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.html");
    exit;
}
function sanitize_value($value)
{
    return trim(filter_var($value, FILTER_SANITIZE_FULL_SPECIAL_CHARS));
}
$game = sanitize_value($_POST["game"] ?? "");
$teamName = sanitize_value($_POST["team_name"] ?? "");
$playerName = sanitize_value($_POST["player_name"] ?? "");
$email = trim($_POST["email"] ?? "");
$region = sanitize_value($_POST["region"] ?? "");

$modeRaw = $_POST["mode"] ?? "";
if (is_array($modeRaw)) {
    $cleanModes = [];
    foreach ($modeRaw as $m) {
        $m = sanitize_value($m);
        if ($m !== "") {
            $cleanModes[] = $m;
        }
    }
    $mode = implode(", ", $cleanModes);
} else {
    $mode = sanitize_value($modeRaw);
}

$notes = sanitize_value($_POST["notes"] ?? "");
$hasError = false;
if ($game === "" || $teamName === "" || $playerName === "" || $email === "" || $region === "" || $mode === "") {
    $hasError = true;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $hasError = true;
}
$redirectPage = "index.html";
if ($game === "BGMI") {
    $redirectPage = "bgmi.html";
} elseif ($game === "Free Fire") {
    $redirectPage = "freefire.html";
} elseif ($game === "Call of Duty Mobile") {
    $redirectPage = "codm.html";
} elseif ($game === "Valorant") {
    $redirectPage = "valorant.html";
}
if ($hasError) {
    header("Location: " . $redirectPage . "?status=error");
    exit;
}
$entry = [
    "game" => $game,
    "team_name" => $teamName,
    "player_name" => $playerName,
    "email" => $email,
    "region" => $region,
    "mode" => $mode,
    "notes" => $notes,
    "user_email" => $_SESSION["user"]["email"] ?? "",
    "created_at" => date("c"),
];
$storageDir = __DIR__ . DIRECTORY_SEPARATOR . "storage";
if (!is_dir($storageDir)) {
    mkdir($storageDir, 0775, true);
}
$filePath = $storageDir . DIRECTORY_SEPARATOR . "game_bookings.json";
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
header("Location: " . $redirectPage . "?status=success");
exit;

