<?php
include '../includes/header.php';
include '../config/db.php';

// Ensure session is active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ✅ Redirect if user not logged in
if (!isset($_SESSION['user'])) {
    header('Location: ../auth/login.php');
    exit;
}

// ✅ Validate ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("❌ Invalid post ID");
}

$id = (int)$_GET['id'];

// ✅ Fetch post using prepared statement
$stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$post = $stmt->get_result()->fetch_assoc();

if (!$post) {
    die("❌ Post not found");
}

// ✅ Check permission: allow admin or post owner only
if ($_SESSION['user']['role'] != 'admin' && $_SESSION['user']['id'] != $post['user_id']) {
    die("❌ Access denied");
}

// ✅ Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $cat = (int)$_POST['category'];

    $stmt = $conn->prepare("UPDATE posts SET title = ?, content = ?, category_id = ? WHERE id = ?");
    $stmt->bind_param("ssii", $title, $content, $cat, $id);

    if ($stmt->execute()) {
        header('Location: index.php');
        exit;
    } else {
        echo "<p>Error updating post: " . htmlspecialchars($stmt->error) . "</p>";
    }
}
?>

<h3>Edit Post</h3>

<form method="POST">
    <input type="text" name="title" value="<?= htmlspecialchars($post['title']); ?>" required><br><br>
    <textarea name="content" required><?= htmlspecialchars($post['content']); ?></textarea><br><br>

    <select name="category" required>
        <?php
        $cats = $conn->query("SELECT * FROM categories");
        while ($c = $cats->fetch_assoc()) {
            $sel = ($c['id'] == $post['category_id']) ? "selected" : "";
            echo "<option value='{$c['id']}' $sel>" . htmlspecialchars($c['name']) . "</option>";
        }
        ?>
    </select><br><br>

    <button type="submit">Update</button>
</form>

<?php include '../includes/footer.php'; ?>
