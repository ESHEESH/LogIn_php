<?php
require 'includes/functions.php';
if (!isset($_SESSION['user'])) { header('Location: login.php'); exit; }
$success = getFlash();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <main class="card">
    <?php if ($success): ?><div class="alert success"><?= clean($success) ?></div><?php endif; ?>
    <h1>Hello, <?= clean($_SESSION['user']['name']) ?>!</h1>
    <p class="subtitle">You are logged in as <?= clean($_SESSION['user']['email']) ?></p>
    <a class="btn-link" href="logout.php">Log Out</a>
  </main>
</body>
</html>
