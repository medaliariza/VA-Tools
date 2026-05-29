<?php
include('../config/database.php');
include('../includes/app.php');

if (app_is_logged_in()) {
    app_redirect_by_role();
}

$error = '';
$success = isset($_GET['registered']) ? 'Account created successfully. You can log in now.' : '';
$email = '';

if (isset($_POST['login'])) {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error = 'Please enter your email and password.';
    } else {
        $safeEmail = mysqli_real_escape_string($conn, $email);
        $res = mysqli_query($conn, "SELECT * FROM users WHERE email='{$safeEmail}' LIMIT 1");
        $user = $res ? mysqli_fetch_assoc($res) : null;

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['user_name'] = $user['fullname'];

            app_redirect_by_role();
        } else {
            $error = 'Invalid email or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In | VA Tools</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="auth-page">
        <div class="auth-card">
            <div class="auth-header">
                <a class="brand" href="../index.php">
                    <span class="brand-mark">VA</span>
                    <span>VA Tools</span>
                </a>
                <h1>Welcome back</h1>
                <p class="helper-text">Log in to manage your dashboard, tasks, communication, and records from one place.</p>
            </div>

            <?php if ($error !== ''): ?>
                <div class="flash flash-error"><?php echo app_escape($error); ?></div>
            <?php endif; ?>

            <?php if ($success !== ''): ?>
                <div class="flash flash-success"><?php echo app_escape($success); ?></div>
            <?php endif; ?>

            <form method="POST" class="form-grid">
                <div class="field-full">
                    <label for="email">Email Address</label>
                    <input id="email" name="email" type="email" value="<?php echo app_escape($email); ?>" placeholder="you@example.com">
                </div>

                <div class="field-full">
                    <label for="password">Password</label>
                    <input id="password" name="password" type="password" placeholder="Enter your password">
                </div>

                <div class="field-full">
                    <button class="btn btn-primary" type="submit" name="login">Log In</button>
                </div>
            </form>

            <p class="auth-links">
                New here?
                <a href="register.php"><strong>Create an account</strong></a>
            </p>
        </div>
    </div>
</body>
</html>
