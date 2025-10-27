<?php
session_start();
require_once "../config/db.php";

if (!isset($_SESSION['user'])) {
    die("Access denied: Please login first.");
}

$user = $_SESSION['user'];

if ($user['role'] !== 'admin') {
    die("Access denied: Only admins can delete posts.");
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $conn->query("DELETE FROM posts WHERE id = $id");
    header("Location: index.php");
    exit;
} else {
    die("Invalid request.");
}
?>
