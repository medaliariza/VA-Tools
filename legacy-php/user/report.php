<?php
include('../config/database.php');
include('../includes/app.php');

app_require_login();

$pageTitle = 'Reports';
$activeNav = 'reports';
$id = (int) $_SESSION['user_id'];
$message = '';

if (isset($_POST['send'])) {
    $content = trim($_POST['content'] ?? '');

    if ($content !== '') {
        $safeContent = mysqli_real_escape_string($conn, $content);
        $file = '';

        if (!empty($_FILES['file']['name'])) {
            $fileName = time() . '_' . basename($_FILES['file']['name']);
            $file = '../uploads/' . $fileName;

            if (!move_uploaded_file($_FILES['file']['tmp_name'], $file)) {
                $file = '';
            }
        }

        $safeFile = mysqli_real_escape_string($conn, $file);
        mysqli_query($conn, "INSERT INTO reports(user_id,content,file) VALUES($id,'{$safeContent}','{$safeFile}')");
        $message = 'Report submitted successfully.';
    } else {
        $message = 'Please write your report before sending it.';
    }
}

$reports = mysqli_query($conn, "SELECT * FROM reports WHERE user_id=$id ORDER BY id DESC");
$reportCount = $reports ? mysqli_num_rows($reports) : 0;

include('../includes/header.php');
?>

<section class="page-hero">
    <div>
        <h1>Reports</h1>
        <p>Submit updates, attach supporting files, and keep a record of work that needs review.</p>
    </div>
    <span class="status-badge status-neutral"><?php echo app_escape($reportCount); ?> reports submitted</span>
</section>

<section class="split-grid">
    <div class="card">
        <h3>Create a Report</h3>
        <p class="section-intro">Share progress updates, summaries, or supporting documentation with your team.</p>

        <?php if ($message !== ''): ?>
            <div class="flash <?php echo strpos($message, 'successfully') !== false ? 'flash-success' : 'flash-error'; ?>">
                <?php echo app_escape($message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="form-grid">
            <div class="field-full">
                <label for="content">Report Content</label>
                <textarea id="content" name="content" placeholder="Write your update, summary, or findings here."></textarea>
            </div>
            <div class="field-full">
                <label for="file">Attachment</label>
                <input id="file" type="file" name="file">
            </div>
            <div class="field-full">
                <button class="btn btn-primary" type="submit" name="send">Send Report</button>
            </div>
        </form>
    </div>

    <div class="card">
        <h3>Submission Guide</h3>
        <ul class="clean-list">
            <li><span>Content required</span><strong>Yes</strong></li>
            <li><span>Attachment optional</span><strong>Yes</strong></li>
            <li><span>Status tracking</span><strong>Pending or approved</strong></li>
        </ul>
    </div>
</section>

<section class="table-card">
    <h3>My Reports</h3>
    <?php if ($reportCount > 0): ?>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Content</th>
                        <th>Attachment</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($report = mysqli_fetch_assoc($reports)): ?>
                        <tr>
                            <td><?php echo app_escape(app_excerpt($report['content'], 100)); ?></td>
                            <td>
                                <?php if (!empty($report['file'])): ?>
                                    <a href="<?php echo app_escape($report['file']); ?>" target="_blank" rel="noopener noreferrer">View file</a>
                                <?php else: ?>
                                    <span class="meta-text">No attachment</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="status-badge <?php echo $report['status'] === 'approved' ? 'status-success' : 'status-warning'; ?>">
                                    <?php echo app_escape(ucfirst($report['status'])); ?>
                                </span>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="empty-state">No reports submitted yet.</p>
    <?php endif; ?>
</section>

<?php include('../includes/footer.php'); ?>
