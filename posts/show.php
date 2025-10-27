<?php
include '../includes/header.php';
include '../config/db.php';

// ✅ Check for valid ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<p style='color:red;'>❌ Invalid post ID.</p>";
    include '../includes/footer.php';
    exit;
}

$id = intval($_GET['id']);

// ✅ Fetch post details
$sql = "SELECT posts.*, categories.name AS category_name, users.name AS author_name
        FROM posts
        LEFT JOIN categories ON posts.category_id = categories.id
        LEFT JOIN users ON posts.user_id = users.id
        WHERE posts.id = $id";

$result = $conn->query($sql);

if (!$result || $result->num_rows == 0) {
    echo "<p style='color:red;'>❌ Post not found.</p>";
    include '../includes/footer.php';
    exit;
}

$post = $result->fetch_assoc();
?>

<div style="max-width:700px;margin:auto;padding:20px;">
    <h2><?= htmlspecialchars($post['title']); ?></h2>
    <p><strong><?= htmlspecialchars($post['author_name']); ?></strong> |
        <em><?= htmlspecialchars($post['category_name']); ?></em> |
        <?= date("M d, Y", strtotime($post['created_at'])); ?></p>
    <p><?= nl2br(htmlspecialchars($post['content'])); ?></p>

    <?php if (isset($_SESSION['user']) && $_SESSION['user']['id'] == $post['user_id']): ?>
        <a href="edit.php?id=<?= $post['id']; ?>">✏️ Edit</a> |
        <a href="delete.php?id=<?= $post['id']; ?>"
           onclick="return confirm('Are you sure you want to delete this post?');">🗑️ Delete</a>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
