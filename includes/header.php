<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Blog App</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
        nav { background: #333; padding: 10px; display: flex; align-items: center; }
        nav a { color: white; margin: 0 10px; text-decoration: none; }
        nav a:hover { text-decoration: underline; }
        .container { padding: 20px; }
        .right { margin-left: auto; }
        span.username { color: #ddd; margin-right: 10px; }
    </style>
</head>
<body>
<nav>
    <!-- Always visible links -->
    <a href="/index.php">Home</a>
    <a href="/posts/index.php">Posts</a>
    <a href="/auth/login.php">Login</a>
    <a href="/auth/register.php">Register</a>


    <?php if (!empty($_SESSION['user'])): ?>
        <!-- Yahan se start hota hai: jab user login hai -->
        <a href="/posts/create.php">Create Post</a>
        <div class="right">
            <span class="username">👤 <?php echo htmlspecialchars($_SESSION['user']['name']); ?></span>
            <a href="/auth/logout.php">Logout</a>
        </div>
    <?php else: ?>
        <!-- Yahan se start hota hai: jab user login nahi hai -->
        <div class="right">
            <a href="/auth/login.php">Login</a>
            <a href="/auth/register.php">Register</a>
        </div>
    <?php endif; ?>
</nav>
<div class="container">
