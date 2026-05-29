<?php

$conn = mysqli_connect('localhost', 'root', '', 'va_enterprise');

if (!$conn) {
    die('Connection failed');
}

mysqli_set_charset($conn, 'utf8mb4');

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

