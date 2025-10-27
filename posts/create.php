<?php
// Always start session first — before includes or any output
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../config/db.php';
include '../includes/header.php';

// Redirect if not logged in
if (!isset($_SESSION['user'])) {
    header('Location: ../auth/login.php');
    exit;
}
?>

<h3>Create Post</h3>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Use prepared statements to prevent SQL injection
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $cat = $_POST['category'];
    $user_id = $_SESSION['user']['id'];

    $stmt = $conn->prepare("INSERT INTO posts (title, content, category_id, user_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssii", $title, $content, $cat, $user_id);

    if ($stmt->execute()) {
        echo "<p style='color:green;'>✅ Post created successfully!</p>";
    } else {
        echo "<p style='color:red;'>❌ Error: " . htmlspecialchars($stmt->error) . "</p>";
    }

    $stmt->close();
}
?>

<form method="POST">
    <input type="text" name="title" placeholder="Title" required><br><br>
    <textarea name="content" placeholder="Content" required></textarea><br><br>

    <select name="category" required>
        <option value="">-- Select Category --</option>
        <?php
        $cats = $conn->query("SELECT * FROM categories");
        while ($c = $cats->fetch_assoc()) {
            echo "<option value='{$c['id']}'>" . htmlspecialchars($c['name']) . "</option>";
        }
        ?>
    </select><br><br>

    <button type="submit">Create</button>
</form>

<?php include '../includes/footer.php'; ?>
