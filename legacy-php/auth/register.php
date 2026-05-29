<?php
include('../config/database.php');
include('../includes/app.php');

if (app_is_logged_in()) {
    app_redirect_by_role();
}

$error = '';
$name = '';
$email = '';

if (isset($_POST['register'])) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($name === '' || $email === '' || $password === '') {
        $error = 'Please fill in all required fields.';
    } else {
        $safeName = mysqli_real_escape_string($conn, $name);
        $safeEmail = mysqli_real_escape_string($conn, $email);
        $check = mysqli_query($conn, "SELECT id FROM users WHERE email='{$safeEmail}' LIMIT 1");

        if ($check && mysqli_num_rows($check) > 0) {
            $error = 'That email is already registered.';
        } else {
            $pass = password_hash($password, PASSWORD_DEFAULT);

            mysqli_query(
                $conn,
                "INSERT INTO users(fullname,email,password) VALUES('{$safeName}','{$safeEmail}','{$pass}')"
            );

            header('Location: login.php?registered=1');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account | VA Tools</title>
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
                <h1>Create your workspace</h1>
                <p class="helper-text">Set up your account and start managing tasks, data, files, and communication in one system.</p>
            </div>

            <?php if ($error !== ''): ?>
                <div class="flash flash-error"><?php echo app_escape($error); ?></div>
            <?php endif; ?>

            <form method="POST" class="form-grid">
                <div class="field-full">
                    <label for="name">Full Name</label>
                    <input id="name" name="name" value="<?php echo app_escape($name); ?>" placeholder="Your full name">
                </div>

                <div class="field-full">
                    <label for="email">Email Address</label>
                    <input id="email" name="email" type="email" value="<?php echo app_escape($email); ?>" placeholder="you@example.com">
                </div>

                <div class="field-full">
                    <label for="password">Password</label>
                    <input id="password" name="password" type="password" placeholder="Create a secure password">
                </div>

                <div class="field-full">
                    <button class="btn btn-primary" type="submit" name="register">Create Account</button>
                </div>
            </form>

            <p class="auth-links">
                Already have an account?
                <a href="login.php"><strong>Log in</strong></a>
            </p>
        </div>
    </div>
</body>
</html>
