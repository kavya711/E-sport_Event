<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirm = $_POST["confirm_password"] ?? "";

    $errors = [];

    if ($username === "") {
        $errors[] = "username_required";
    }
    if ($email === "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "email_invalid";
    }
    if ($password === "" || strlen($password) < 6) {
        $errors[] = "password_short";
    }
    if ($password !== $confirm) {
        $errors[] = "password_mismatch";
    }

    if (empty($errors)) {
        $storageDir = __DIR__ . DIRECTORY_SEPARATOR . "storage";
        if (!is_dir($storageDir)) {
            mkdir($storageDir, 0775, true);
        }
        $filePath = $storageDir . DIRECTORY_SEPARATOR . "users.json";
        $users = [];
        if (file_exists($filePath)) {
            $contents = file_get_contents($filePath);
            $decoded = json_decode($contents, true);
            if (is_array($decoded)) {
                $users = $decoded;
            }
        }
        foreach ($users as $user) {
            if (isset($user["email"]) && strtolower($user["email"]) === strtolower($email)) {
                $errors[] = "email_exists";
                break;
            }
        }
        if (empty($errors)) {
            $users[] = [
                "username" => $username,
                "email" => $email,
                "password_hash" => password_hash($password, PASSWORD_DEFAULT),
                "created_at" => date("c"),
            ];
            file_put_contents($filePath, json_encode($users, JSON_PRETTY_PRINT));
            header("Location: login.html?registered=1");
            exit;
        }
    }

    if (!empty($errors)) {
        $query = http_build_query([
            "error" => implode(",", $errors),
            "username" => $username,
            "email" => $email,
        ]);
        header("Location: signup.html?" . $query);
        exit;
    }
}

header("Location: signup.html");
exit;

