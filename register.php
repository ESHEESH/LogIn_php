<?php
require 'includes/functions.php';
if (isset($_SESSION['user'])) { header('Location: dashboard.php'); exit; }

$errors = [];
$name = $email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    if ($name === '') $errors['name'] = 'Full name is required.';
    elseif (strlen($name) < 2) $errors['name'] = 'Name must be at least 2 characters.';

    if ($email === '') $errors['email'] = 'Email is required.';
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Please enter a valid email address.';
    elseif (findUser($email)) $errors['email'] = 'This email is already registered.';

    if ($password === '') $errors['password'] = 'Password is required.';
    elseif (strlen($password) < 8) $errors['password'] = 'Password must be at least 8 characters.';
    elseif (!preg_match('/[A-Za-z]/', $password) || !preg_match('/\d/', $password))
        $errors['password'] = 'Password must contain at least one letter and one number.';

    if ($confirm === '') $errors['confirm'] = 'Please confirm your password.';
    elseif ($password !== $confirm) $errors['confirm'] = 'Passwords do not match.';

    if (!$errors) {
        $users = getUsers();
        $users[] = [
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'created' => date('c'),
        ];
        saveUsers($users);
        setFlash('Registration successful! You can now log in.');
        header('Location: login.php'); exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Register</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <main class="card">
    <h1>Create Account</h1>
    <p class="subtitle">Sign up to get started</p>

    <form method="POST" action="register.php" novalidate>
      <label for="name">Full Name</label>
      <input type="text" id="name" name="name" placeholder="Dan Francis" value="<?= clean($name) ?>" class="<?= isset($errors['name']) ? 'invalid' : '' ?>">
      <?php if (isset($errors['name'])): ?><span class="msg"><?= $errors['name'] ?></span><?php endif; ?>

      <label for="email">Email</label>
      <input type="email" id="email" name="email" placeholder="you@example.com" value="<?= clean($email) ?>" class="<?= isset($errors['email']) ? 'invalid' : '' ?>">
      <?php if (isset($errors['email'])): ?><span class="msg"><?= $errors['email'] ?></span><?php endif; ?>

      <label for="password">Password</label>
      <input type="password" id="password" name="password" placeholder="At least 8 characters" class="<?= isset($errors['password']) ? 'invalid' : '' ?>">
      <?php if (isset($errors['password'])): ?><span class="msg"><?= $errors['password'] ?></span><?php endif; ?>

      <label for="confirm">Confirm Password</label>
      <input type="password" id="confirm" name="confirm" placeholder="Re-enter your password" class="<?= isset($errors['confirm']) ? 'invalid' : '' ?>">
      <?php if (isset($errors['confirm'])): ?><span class="msg"><?= $errors['confirm'] ?></span><?php endif; ?>

      <button type="submit">Register</button>
    </form>

    <p class="switch">Already have an account? <a href="login.php">Log in</a></p>
  </main>
  <script src="assets/js/script.js"></script>
</body>
</html>
