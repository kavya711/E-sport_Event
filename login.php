<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $errors = [];

    if ($email === "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "email_invalid";
    }
    if ($password === "") {
        $errors[] = "password_required";
    }
    if (empty($errors)) {
        $storageDir = __DIR__ . DIRECTORY_SEPARATOR . "storage";
        $filePath = $storageDir . DIRECTORY_SEPARATOR . "users.json";
        $users = [];
        if (file_exists($filePath)) {
            $contents = file_get_contents($filePath);
            $decoded = json_decode($contents, true);
            if (is_array($decoded)) {
                $users = $decoded;
            }
        }
        $foundUser = null;
        foreach ($users as $user) {
            if (isset($user["email"]) && strtolower($user["email"]) === strtolower($email)) {
                $foundUser = $user;
                break;
            }
        }
        if (!$foundUser || !password_verify($password, $foundUser["password_hash"] ?? "")) {
            $errors[] = "invalid_credentials";
        } else {
            $_SESSION["user"] = [
                "username" => $foundUser["username"],
                "email" => $foundUser["email"],
            ];
            header("Location: index.html");
            exit;
        }
    }

    if (!empty($errors)) {
        $query = http_build_query([
            "error" => implode(",", $errors),
            "email" => $email,
        ]);
        header("Location: login.html?" . $query);
        exit;
    }
}

header("Location: login.html");
exit;

