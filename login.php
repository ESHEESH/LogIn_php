<?php
require 'includes/functions.php';
if (isset($_SESSION['user'])) { header('Location: dashboard.php'); exit; }

$errors = [];
$email = '';
$success = getFlash();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '') $errors['email'] = 'Email is required.';
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Please enter a valid email address.';

    if ($password === '') $errors['password'] = 'Password is required.';

    if (!$errors) {
        $user = findUser($email);
        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user'] = ['name' => $user['name'], 'email' => $user['email']];
            setFlash('Login successful! Welcome back, ' . $user['name'] . '.');
            header('Location: dashboard.php'); exit;
        }
        $errors['general'] = 'Incorrect email or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <main class="card">
    <h1>Welcome Back</h1>
    <p class="subtitle">Log in to your account</p>

    <?php if ($success): ?><div class="alert success"><?= clean($success) ?></div><?php endif; ?>
    <?php if (isset($errors['general'])): ?><div class="alert error"><?= $errors['general'] ?></div><?php endif; ?>

    <form method="POST" action="login.php" novalidate>
      <label for="email">Email</label>
      <input type="email" id="email" name="email" placeholder="you@example.com" value="<?= clean($email) ?>" class="<?= isset($errors['email']) ? 'invalid' : '' ?>">
      <?php if (isset($errors['email'])): ?><span class="msg"><?= $errors['email'] ?></span><?php endif; ?>

      <label for="password">Password</label>
      <input type="password" id="password" name="password" placeholder="Enter your password" class="<?= isset($errors['password']) ? 'invalid' : '' ?>">
      <?php if (isset($errors['password'])): ?><span class="msg"><?= $errors['password'] ?></span><?php endif; ?>

      <button type="submit">Log In</button>
    </form>

    <p class="switch">Don't have an account? <a href="register.php">Register</a></p>
  </main>
  <script src="assets/js/script.js"></script>
</body>
</html>
