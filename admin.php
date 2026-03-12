<?php
session_start();

$admin_username = "admin";
$admin_password = "password123";

// Handle Logout
if (isset($_GET['logout'])) {
    unset($_SESSION['admin_logged_in']);
    header("Location: admin.php");
    exit;
}

// Handle Login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    if ($username === $admin_username && $password === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin.php");
        exit;
    } else {
        $login_error = "Invalid username or password.";
    }
}

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Elite Arena</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .admin-login-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: var(--dark-bg);
        }
        .admin-login-card {
            background: var(--card-bg);
            padding: 3rem;
            border-radius: var(--border-radius-lg);
            border: 1px solid var(--border-color);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .admin-login-card h1 {
            margin-bottom: 2rem;
            font-size: 2rem;
            color: var(--primary-light);
        }
    </style>
</head>
<body>
    <div class="admin-login-wrapper">
        <div class="admin-login-card">
            <h1>Admin Panel Login</h1>
            <?php if (isset($login_error)) echo "<p style='color: var(--accent-red); margin-bottom: 1rem;'>$login_error</p>"; ?>
            <form method="POST">
                <div class="form-group" style="text-align: left;">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group" style="text-align: left;">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" name="login" class="btn btn-primary btn-block">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
<?php
    exit;
}

// Fetch Data
$storageDir = __DIR__ . DIRECTORY_SEPARATOR . 'storage';
$eventsFile = $storageDir . DIRECTORY_SEPARATOR . 'events.json';
$bookingsFile = $storageDir . DIRECTORY_SEPARATOR . 'game_bookings.json';
$usersFile = $storageDir . DIRECTORY_SEPARATOR . 'users.json';

// Initialize events file if it doesn't exist
if (!file_exists($eventsFile)) {
    if (!is_dir($storageDir)) {
        mkdir($storageDir, 0775, true);
    }
    // Default events to show initially
    $default_events = [
        [
            "id" => uniqid(),
            "name" => "Elite BGMI Masters Cup",
            "region" => "Online • Asia",
            "date" => "Starts 1 Aug 2026",
            "description" => "Tier-based lobbies, point-based scoring, and live leaderboard for 256 squads.",
            "prize" => "₹1,00,000"
        ],
        [
            "id" => uniqid(),
            "name" => "Free Fire Clash Royale",
            "region" => "Online • Global",
            "date" => "Starts 5 Aug 2026",
            "description" => "Fast-paced single-day events with knockout brackets and highlight reels.",
            "prize" => "$2,000"
        ],
        [
            "id" => uniqid(),
            "name" => "Valorant Premier Circuit",
            "region" => "Online • EU & Asia",
            "date" => "Starts 10 Aug 2026",
            "description" => "Best-of-3 series with map veto, production-ready lobbies, and observer slots.",
            "prize" => "$5,000"
        ]
    ];
    file_put_contents($eventsFile, json_encode($default_events, JSON_PRETTY_PRINT));
}

// Handle Add Event
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_event'])) {
    $newEvent = [
        "id" => uniqid(),
        "name" => htmlspecialchars($_POST['event_name'] ?? ''),
        "region" => htmlspecialchars($_POST['event_region'] ?? ''),
        "date" => htmlspecialchars($_POST['event_date'] ?? ''),
        "description" => htmlspecialchars($_POST['event_desc'] ?? ''),
        "prize" => htmlspecialchars($_POST['event_prize'] ?? '')
    ];
    
    $events = json_decode(file_get_contents($eventsFile), true) ?: [];
    array_unshift($events, $newEvent); // Add to beginning
    file_put_contents($eventsFile, json_encode($events, JSON_PRETTY_PRINT));
    
    header("Location: admin.php?success=Event added");
    exit;
}

// Handle Delete Event
if (isset($_GET['delete_event'])) {
    $idToDelete = $_GET['delete_event'];
    $events = json_decode(file_get_contents($eventsFile), true) ?: [];
    $events = array_filter($events, function($e) use ($idToDelete) {
        return $e['id'] !== $idToDelete;
    });
    file_put_contents($eventsFile, json_encode(array_values($events), JSON_PRETTY_PRINT));
    header("Location: admin.php?success=Event deleted");
    exit;
}

// Read Current Data
$events = json_decode(file_get_contents($eventsFile), true) ?: [];
$bookings = file_exists($bookingsFile) ? json_decode(file_get_contents($bookingsFile), true) ?: [] : [];
$users = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) ?: [] : [];

// Basic sorting to show newest first if created_at exists
usort($bookings, function($a, $b) {
    $timeA = isset($a['created_at']) ? strtotime($a['created_at']) : 0;
    $timeB = isset($b['created_at']) ? strtotime($b['created_at']) : 0;
    return $timeB - $timeA;
});
usort($users, function($a, $b) {
    $timeA = isset($a['created_at']) ? strtotime($a['created_at']) : 0;
    $timeB = isset($b['created_at']) ? strtotime($b['created_at']) : 0;
    return $timeB - $timeA;
});

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Elite Arena</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .admin-dashboard {
            padding: 4rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 3rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-color);
        }
        .admin-section {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius-lg);
            padding: 2rem;
            margin-bottom: 3rem;
        }
        .admin-section h2 {
            margin-bottom: 1.5rem;
            color: var(--primary-light);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .data-table-wrapper {
            overflow-x: auto;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }
        .data-table th, .data-table td {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
        }
        .data-table th {
            background: rgba(255, 255, 255, 0.05);
            font-weight: 600;
        }
        .data-table tr:hover {
            background: rgba(255, 255, 255, 0.02);
        }
        .btn-small { padding: 0.5rem 1rem; font-size: 0.875rem; }
        .btn-danger { background: var(--accent-red); color: white; }
        .btn-danger:hover { filter: brightness(1.2); }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    </style>
</head>
<body>
    <header class="site-header">
        <div class="container header-inner">
            <a href="index.php" class="brand">
                <img src="assets/logo-light.svg" alt="Elite Arena Esports logo" class="brand-logo" onerror="this.style.display='none'">
                <span class="brand-text">Elite Arena Admin</span>
            </a>
            <nav class="main-nav">
                <ul class="nav-menu">
                    <li><a href="index.php" target="_blank">View Site</a></li>
                    <li><a href="?logout=1" class="btn btn-primary btn-small">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="admin-dashboard">
        <?php if (isset($_GET['success'])): ?>
            <div class="form-message success" style="margin-bottom: 2rem;">
                <?php echo htmlspecialchars($_GET['success']); ?>
            </div>
        <?php endif; ?>

        <div class="admin-section">
            <h2>Add Upcoming Event</h2>
            <form method="POST" class="register-form">
                <div class="grid-2">
                    <div class="form-group">
                        <label>Event Name</label>
                        <input type="text" name="event_name" required placeholder="e.g., Summer BGMI Clash">
                    </div>
                    <div class="form-group">
                        <label>Date & Time</label>
                        <input type="text" name="event_date" required placeholder="e.g., Starts 25 Aug 2026">
                    </div>
                    <div class="form-group">
                        <label>Region/Location</label>
                        <input type="text" name="event_region" required placeholder="e.g., Online • Asia">
                    </div>
                    <div class="form-group">
                        <label>Prize Pool</label>
                        <input type="text" name="event_prize" required placeholder="e.g., ₹50,000">
                    </div>
                </div>
                <div class="form-group">
                    <label>Description (Short)</label>
                    <textarea name="event_desc" rows="2" required placeholder="Brief description of the event..."></textarea>
                </div>
                <button type="submit" name="add_event" class="btn btn-primary">Add Event</button>
            </form>
        </div>

        <div class="admin-section">
            <h2>Manage Events (Dynamic)</h2>
            <div class="data-table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Date</th>
                            <th>Region</th>
                            <th>Prize</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($events)): ?>
                            <tr><td colspan="5">No events found.</td></tr>
                        <?php else: foreach ($events as $event): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($event['name']); ?></strong></td>
                                <td><?php echo htmlspecialchars($event['date']); ?></td>
                                <td><?php echo htmlspecialchars($event['region']); ?></td>
                                <td><?php echo htmlspecialchars($event['prize']); ?></td>
                                <td>
                                    <a href="?delete_event=<?php echo urlencode($event['id']); ?>" class="btn btn-small btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="admin-section">
            <h2>Game Registrations (Team Bookings) <span class="badge" style="background: var(--primary-light); padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.8rem;"><?php echo count($bookings); ?></span></h2>
            <div class="data-table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Game</th>
                            <th>Team Name</th>
                            <th>Captain/Player</th>
                            <th>Email</th>
                            <th>Region</th>
                            <th>Mode</th>
                            <th>Date Registered</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($bookings)): ?>
                            <tr><td colspan="7">No bookings found.</td></tr>
                        <?php else: foreach ($bookings as $booking): ?>
                            <tr>
                                <td><span style="color: var(--primary-light);"><?php echo htmlspecialchars($booking['game'] ?? 'N/A'); ?></span></td>
                                <td><strong><?php echo htmlspecialchars($booking['team_name'] ?? 'N/A'); ?></strong></td>
                                <td><?php echo htmlspecialchars($booking['player_name'] ?? $booking['captain_name'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($booking['email'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($booking['region'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($booking['mode'] ?? $booking['team_size'] ?? 'N/A'); ?></td>
                                <td style="font-size: 0.9em; opacity: 0.8;"><?php echo isset($booking['created_at']) ? date('M j, Y, g:i a', strtotime($booking['created_at'])) : 'Unknown'; ?></td>
                            </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="admin-section">
            <h2>Registered Users <span class="badge" style="background: var(--primary-light); padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.8rem;"><?php echo count($users); ?></span></h2>
            <div class="data-table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Date Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($users)): ?>
                            <tr><td colspan="3">No users found.</td></tr>
                        <?php else: foreach ($users as $user): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($user['username'] ?? 'N/A'); ?></strong></td>
                                <td><?php echo htmlspecialchars($user['email'] ?? 'N/A'); ?></td>
                                <td style="font-size: 0.9em; opacity: 0.8;"><?php echo isset($user['created_at']) ? date('M j, Y, g:i a', strtotime($user['created_at'])) : 'Unknown'; ?></td>
                            </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>
