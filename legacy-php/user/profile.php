<?php
include('../config/database.php');
include('../includes/app.php');

app_require_login();

$pageTitle = 'Profile';
$activeNav = 'profile';
$id = (int) $_SESSION['user_id'];
$message = '';

if (isset($_POST['save'])) {
    $bio = trim($_POST['bio'] ?? '');
    $safeBio = mysqli_real_escape_string($conn, $bio);

    if (!empty($_FILES['img']['name'])) {
        $fileName = time() . '_' . basename($_FILES['img']['name']);
        $path = '../uploads/' . $fileName;

        if (move_uploaded_file($_FILES['img']['tmp_name'], $path)) {
            $safePath = mysqli_real_escape_string($conn, $path);
            mysqli_query($conn, "UPDATE users SET avatar='{$safePath}' WHERE id=$id");
        }
    }

    mysqli_query($conn, "UPDATE users SET bio='{$safeBio}' WHERE id=$id");
    $message = 'Profile updated successfully.';
}

$res = mysqli_query($conn, "SELECT * FROM users WHERE id=$id LIMIT 1");
$u = $res ? mysqli_fetch_assoc($res) : null;

if ($u) {
    $_SESSION['user_name'] = $u['fullname'];
}

$initials = '';
if ($u && !empty($u['fullname'])) {
    foreach (explode(' ', trim($u['fullname'])) as $part) {
        if ($part !== '') {
            $initials .= strtoupper($part[0]);
        }
    }
}
$initials = substr($initials ?: 'VA', 0, 2);

include('../includes/header.php');
?>

<section class="page-hero">
    <div>
        <h1>My Profile</h1>
        <p>Manage your personal details, workspace identity, and profile information in one place.</p>
    </div>
    <span class="status-badge status-neutral"><?php echo app_escape($_SESSION['role'] ?? 'employee'); ?> account</span>
</section>

<section class="split-grid">
    <div class="card">
        <div class="profile-summary">
            <?php if (!empty($u['avatar'])): ?>
                <img class="avatar" src="<?php echo app_escape($u['avatar']); ?>" alt="Profile avatar">
            <?php else: ?>
                <div class="avatar"><?php echo app_escape($initials); ?></div>
            <?php endif; ?>

            <div>
                <h3><?php echo app_escape($u['fullname'] ?? 'Workspace User'); ?></h3>
                <p class="meta-text"><?php echo app_escape($u['email'] ?? ''); ?></p>
            </div>
        </div>

        <?php if ($message !== ''): ?>
            <div class="flash flash-success"><?php echo app_escape($message); ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="form-grid">
            <div class="field-full">
                <label for="bio">Bio</label>
                <textarea id="bio" name="bio" placeholder="Tell your team a little about your role and focus."><?php echo app_escape($u['bio'] ?? ''); ?></textarea>
            </div>
            <div class="field-full">
                <label for="img">Profile Image</label>
                <input id="img" type="file" name="img" accept="image/*">
            </div>
            <div class="field-full">
                <button class="btn btn-primary" type="submit" name="save">Save Profile</button>
            </div>
        </form>
    </div>

    <div class="card">
        <h3>Account Summary</h3>
        <ul class="clean-list">
            <li><span>Full name</span><strong><?php echo app_escape($u['fullname'] ?? ''); ?></strong></li>
            <li><span>Email</span><strong><?php echo app_escape($u['email'] ?? ''); ?></strong></li>
            <li><span>Role</span><strong><?php echo app_escape($u['role'] ?? 'employee'); ?></strong></li>
        </ul>
    </div>
</section>

<?php include('../includes/footer.php'); ?>
