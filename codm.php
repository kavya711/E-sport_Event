<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php?need_login=1");
    exit;
}
$page_title = "COD Mobile Tournaments | Elite Arena Esports";
$page_description = "Book online Call of Duty Mobile tournaments including Search & Destroy and Hardpoint formats.";
$page_url = "https://www.example.com/codm.php";
$status = $_GET["status"] ?? "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo htmlspecialchars($page_title); ?></title>
  <meta name="description" content="<?php echo htmlspecialchars($page_description); ?>" />
  <meta name="robots" content="index, follow" />
  <link rel="canonical" href="<?php echo htmlspecialchars($page_url); ?>" />
  <link rel="icon" type="image/png" href="assets/favicon.png" />
  <link rel="stylesheet" href="styles.css" />
</head>
<body>
  <header class="site-header" id="top">
    <div class="container header-inner">
      <a href="index.php" class="brand" aria-label="Elite Arena Esports home">
        <img src="assets/logo-light.svg" alt="Elite Arena Esports logo" class="brand-logo" />
        <span class="brand-text">Elite Arena</span>
      </a>
      <nav class="main-nav" aria-label="Main navigation">
        <button class="nav-toggle" aria-expanded="false" aria-controls="nav-menu">
          <span class="nav-toggle-bar"></span>
          <span class="nav-toggle-bar"></span>
          <span class="nav-toggle-bar"></span>
          <span class="visually-hidden">Toggle navigation</span>
        </button>
        <ul id="nav-menu" class="nav-menu">
          <li><a href="index.php">Home</a></li>
          <li><a href="bgmi.php">BGMI</a></li>
          <li><a href="freefire.php">Free Fire</a></li>
          <li><a href="codm.php">COD Mobile</a></li>
          <li><a href="valorant.php">Valorant</a></li>
          <li><a href="logout.php">Logout</a></li>
        </ul>
      </nav>
    </div>
  </header>
  <main>
    <section class="section">
      <div class="container register-grid">
        <div class="register-copy">
          <header class="section-header align-left">
            <h2>Call of Duty Mobile Online Tournament Booking</h2>
            <p>Reserve slots for competitive COD Mobile lobbies across Search & Destroy, Hardpoint, and Domination.</p>
          </header>
          <ul class="checklist">
            <li>Ranked-ready competitive rulesets</li>
            <li>Map veto and series formats</li>
            <li>Ping-balanced matchmaking</li>
            <li>Observer and casting support</li>
          </ul>
        </div>
        <div class="register-form-wrapper">
          <?php if ($status === "success"): ?>
            <div class="form-message success" role="status">
              <strong>Booking confirmed.</strong> Check your email for COD Mobile lobby information.
            </div>
          <?php elseif ($status === "error"): ?>
            <div class="form-message error" role="alert">
              <strong>Booking failed.</strong> Please verify all fields and try again.
            </div>
          <?php endif; ?>
          <form class="register-form" action="game-booking.php" method="POST" novalidate>
            <input type="hidden" name="game" value="Call of Duty Mobile" />
            <div class="form-group">
              <label for="codm-team-name">Team Name</label>
              <input type="text" id="codm-team-name" name="team_name" required maxlength="80" />
            </div>
            <div class="form-group">
              <label for="codm-player-name">Captain Name</label>
              <input type="text" id="codm-player-name" name="player_name" required maxlength="80" />
            </div>
            <div class="form-group form-grid-2">
              <div>
                <label for="codm-email">Contact Email</label>
                <input type="email" id="codm-email" name="email" required maxlength="120" value="<?php echo htmlspecialchars($_SESSION["user"]["email"] ?? ""); ?>" />
              </div>
              <div>
                <label for="codm-region">Region</label>
                <select id="codm-region" name="region" required>
                  <option value="">Select region</option>
                  <option value="Asia">Asia</option>
                  <option value="Europe">Europe</option>
                  <option value="Middle East">Middle East</option>
                  <option value="North America">North America</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label for="codm-mode">Mode</label>
              <select id="codm-mode" name="mode" required>
                <option value="">Select Mode</option>
                <option value="Search & Destroy">Search & Destroy</option>
                <option value="Hardpoint">Hardpoint</option>
                <option value="Domination">Domination</option>
                <option value="Mixed Series">Mixed Series</option>
              </select>
            </div>
            <div class="form-group">
              <label for="codm-notes">IGN / UID / Extra Notes</label>
              <textarea id="codm-notes" name="notes" rows="4" maxlength="400"></textarea>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Book COD Mobile Slot</button>
            <p class="form-footnote">Bookings are reviewed. You will receive a confirmation email if your slot is approved.</p>
          </form>
        </div>
      </div>
    </section>
  </main>
  <footer class="site-footer">
    <div class="container footer-grid">
      <div>
        <a href="index.php" class="brand footer-brand">
          <img src="assets/logo-dark.svg" alt="Elite Arena Esports logo" class="brand-logo" />
          <span class="brand-text">Elite Arena</span>
        </a>
        <p class="footer-text">
          Elite Arena Esports is a tournament operations platform for BGMI, Free Fire, COD Mobile, and Valorant events.
        </p>
      </div>
      <div>
        <h3 class="footer-heading">Games</h3>
        <ul class="footer-links">
          <li><a href="bgmi.php">BGMI</a></li>
          <li><a href="freefire.php">Free Fire</a></li>
          <li><a href="codm.php">COD Mobile</a></li>
          <li><a href="valorant.php">Valorant</a></li>
        </ul>
      </div>
      <div>
        <h3 class="footer-heading">Account</h3>
        <ul class="footer-links">
          <li><a href="logout.php">Logout</a></li>
        </ul>
      </div>
      <div>
        <h3 class="footer-heading">Contact</h3>
        <ul class="footer-links">
          <li><a href="mailto:contact@elitearena.gg">contact@elitearena.gg</a></li>
        </ul>
      </div>
    </div>
    <div class="container footer-bottom">
      <p>© <?php echo date("Y"); ?> Elite Arena Esports. All rights reserved.</p>
      <p class="footer-bottom-meta">Designed for SEO-friendly esports event organization.</p>
    </div>
  </footer>
  <script src="script.js"></script>
</body>
</html>

