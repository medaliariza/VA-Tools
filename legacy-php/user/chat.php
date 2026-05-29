<?php
include('../config/database.php');
include('../includes/app.php');

app_require_login();

$pageTitle = 'Live Chat';
$activeNav = 'chat';
$id = (int) $_SESSION['user_id'];

$res = mysqli_query(
    $conn,
    "SELECT m.*, u.fullname
     FROM messages m
     LEFT JOIN users u ON u.id = m.sender_id
     ORDER BY m.id DESC"
);

$messageCount = $res ? mysqli_num_rows($res) : 0;

include('../includes/header.php');
?>

<section class="page-hero">
    <div>
        <h1>Live Chat</h1>
        <p>Keep team communication moving with a shared conversation space where users can reach admins with requests, concerns, and updates.</p>
    </div>
    <span class="status-badge status-neutral"><?php echo app_escape($messageCount); ?> total messages</span>
</section>

<section class="split-grid">
    <div class="card">
        <h3>Conversation Feed</h3>
        <?php if ($messageCount > 0): ?>
            <div class="chat-stream">
                <?php while ($m = mysqli_fetch_assoc($res)): ?>
                    <div class="chat-message">
                        <strong><?php echo app_escape($m['fullname'] ?: 'Workspace User'); ?></strong>
                        <div><?php echo nl2br(app_escape($m['message'])); ?></div>
                        <small><?php echo (int) $m['sender_id'] === $id ? 'Sent by you' : 'Team message'; ?></small>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p class="empty-state">No messages yet. Start the conversation below.</p>
        <?php endif; ?>
    </div>

    <div class="card">
        <h3>Send a Message</h3>
        <p class="section-intro">Use the chat panel to share quick updates and contact admins directly when you need support.</p>
        <div class="form-grid">
            <div class="field-full">
                <label for="msg">Message</label>
                <textarea id="msg" placeholder="Type your message here"></textarea>
            </div>
            <div class="field-full">
                <button class="btn btn-primary" type="button" onclick="sendMessage()">Send Message</button>
            </div>
        </div>
    </div>
</section>

<?php include('../includes/footer.php'); ?>
