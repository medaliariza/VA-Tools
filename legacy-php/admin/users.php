<?php
include('../config/database.php');
include('../includes/app.php');

app_require_role(['admin']);

$pageTitle = 'User Access';
$activeNav = 'users';
$message = '';

if (isset($_POST['set'])) {
    $id = (int) ($_POST['id'] ?? 0);
    $role = $_POST['role'] ?? 'employee';
    $allowedRoles = ['employee', 'hr', 'gm', 'admin'];

    if ($id > 0 && in_array($role, $allowedRoles, true)) {
        $safeRole = mysqli_real_escape_string($conn, $role);
        mysqli_query($conn, "UPDATE users SET role='{$safeRole}' WHERE id={$id}");
        $message = 'User role updated successfully.';
    } else {
        $message = 'Unable to update role.';
    }
}

$res = mysqli_query($conn, "SELECT * FROM users ORDER BY fullname ASC");
$userCount = $res ? mysqli_num_rows($res) : 0;

include('../includes/header.php');
?>

<section class="page-hero">
    <div>
        <h1>User Access Control</h1>
        <p>Assign roles, manage permissions, and keep administrative oversight in one place for users, staff, and organization teams.</p>
    </div>
    <span class="status-badge status-neutral"><?php echo app_escape($userCount); ?> registered users</span>
</section>

<section class="split-grid">
    <div class="card">
        <h3>Role-Based Permissions</h3>
        <p class="section-intro">
            Different user levels help keep data secure by making sure admins and regular users do not see the same controls.
        </p>
        <ul class="clean-list">
            <li><span>Admin access</span><strong>Manage users and permissions</strong></li>
            <li><span>Regular users</span><strong>Use assigned workspace tools</strong></li>
            <li><span>Data control</span><strong>Based on assigned role</strong></li>
        </ul>
    </div>

    <div class="card">
        <h3>VIP Organization Use</h3>
        <p class="section-intro">
            VIP accounts are intended for companies and management-level users who need broader employee and department oversight.
        </p>
        <ul class="clean-list">
            <li><span>Management roles</span><strong>CEO, COO, HR, GM</strong></li>
            <li><span>Company structure</span><strong>Employees and departments</strong></li>
            <li><span>Visibility model</span><strong>Company and department scoped</strong></li>
        </ul>
    </div>
</section>

<section class="table-card">
    <h3>Manage Roles</h3>
    <?php if ($message !== ''): ?>
        <div class="flash <?php echo strpos($message, 'successfully') !== false ? 'flash-success' : 'flash-error'; ?>">
            <?php echo app_escape($message); ?>
        </div>
    <?php endif; ?>

    <?php if ($userCount > 0): ?>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Current Role</th>
                        <th>Change Role</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($u = mysqli_fetch_assoc($res)): ?>
                        <tr>
                            <td><?php echo app_escape($u['fullname']); ?></td>
                            <td><?php echo app_escape($u['email']); ?></td>
                            <td><span class="pill"><?php echo app_escape($u['role']); ?></span></td>
                            <td>
                                <form method="POST" class="inline-actions">
                                    <input type="hidden" name="id" value="<?php echo app_escape($u['id']); ?>">
                                    <select name="role">
                                        <?php foreach (['employee', 'hr', 'gm', 'admin'] as $role): ?>
                                            <option value="<?php echo app_escape($role); ?>" <?php echo $u['role'] === $role ? 'selected' : ''; ?>>
                                                <?php echo app_escape(strtoupper($role)); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button class="btn btn-primary" type="submit" name="set">Save</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="empty-state">No users found.</p>
    <?php endif; ?>
</section>

<?php include('../includes/footer.php'); ?>
