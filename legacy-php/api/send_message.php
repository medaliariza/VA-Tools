<?php
include('../config/database.php');
include('../includes/app.php');

header('Content-Type: application/json');

if (!app_is_logged_in()) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'message' => 'Unauthorized']);
    exit;
}

$id = (int) $_SESSION['user_id'];
$msg = trim($_POST['message'] ?? '');

if ($msg === '') {
    http_response_code(422);
    echo json_encode(['ok' => false, 'message' => 'Message cannot be empty']);
    exit;
}

$safeMsg = mysqli_real_escape_string($conn, $msg);
$result = mysqli_query($conn, "INSERT INTO messages(sender_id,message) VALUES($id,'{$safeMsg}')");

if (!$result) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => 'Failed to send message']);
    exit;
}

echo json_encode(['ok' => true]);

