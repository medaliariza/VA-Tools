<?php
include('../config/database.php');
include('../includes/app.php');

app_require_login();

$pageTitle = 'Inventory';
$activeNav = 'inventory';
$message = '';

if (isset($_POST['add'])) {
    $name = trim($_POST['name'] ?? '');
    $qty = (int) ($_POST['qty'] ?? 0);
    $dept = trim($_POST['dept'] ?? '');

    if ($name !== '' && $dept !== '' && $qty >= 0) {
        $safeName = mysqli_real_escape_string($conn, $name);
        $safeDept = mysqli_real_escape_string($conn, $dept);
        mysqli_query($conn, "INSERT INTO inventory(name,qty,department) VALUES('{$safeName}',{$qty},'{$safeDept}')");
        $message = 'Inventory item added successfully.';
    } else {
        $message = 'Please complete all inventory fields.';
    }
}

$res = mysqli_query($conn, "SELECT * FROM inventory ORDER BY id DESC");
$inventoryCount = $res ? mysqli_num_rows($res) : 0;

include('../includes/header.php');
?>

<section class="page-hero">
    <div>
        <h1>Inventory System</h1>
        <p>Track item quantities, departments, and updates in one organized inventory view.</p>
    </div>
    <span class="status-badge status-neutral"><?php echo app_escape($inventoryCount); ?> items tracked</span>
</section>

<section class="split-grid">
    <div class="card">
        <h3>Add Inventory Item</h3>
        <p class="section-intro">Capture stock details so your records stay current and easy to review.</p>

        <?php if ($message !== ''): ?>
            <div class="flash <?php echo strpos($message, 'successfully') !== false ? 'flash-success' : 'flash-error'; ?>">
                <?php echo app_escape($message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="form-grid">
            <div class="field">
                <label for="name">Item Name</label>
                <input id="name" name="name" placeholder="Laptop, Headset, Printer ink">
            </div>
            <div class="field">
                <label for="qty">Quantity</label>
                <input id="qty" name="qty" type="number" min="0" placeholder="0">
            </div>
            <div class="field-full">
                <label for="dept">Department</label>
                <input id="dept" name="dept" placeholder="Operations, Admin, Sales">
            </div>
            <div class="field-full">
                <button class="btn btn-primary" type="submit" name="add">Add Inventory</button>
            </div>
        </form>
    </div>

    <div class="card">
        <h3>Inventory Snapshot</h3>
        <p class="section-intro">Use this module to keep shared resources visible for the whole team.</p>
        <ul class="clean-list">
            <li><span>Total records</span><strong><?php echo app_escape($inventoryCount); ?></strong></li>
            <li><span>Real-time organization</span><strong>Enabled</strong></li>
            <li><span>Shared visibility</span><strong>Workspace-wide</strong></li>
        </ul>
    </div>
</section>

<section class="table-card">
    <h3>Current Inventory</h3>
    <?php if ($inventoryCount > 0): ?>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Quantity</th>
                        <th>Department</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($r = mysqli_fetch_assoc($res)): ?>
                        <tr>
                            <td><?php echo app_escape($r['name']); ?></td>
                            <td><?php echo app_escape($r['qty']); ?></td>
                            <td><?php echo app_escape($r['department']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="empty-state">No inventory items have been added yet.</p>
    <?php endif; ?>
</section>

<?php include('../includes/footer.php'); ?>
