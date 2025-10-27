<?php
session_start();
include '../includes/header.php';
include '../config/db.php';

echo "<h2 style='text-align:center;'>All Posts</h2>";

// If logged in, show create button
if (isset($_SESSION['user'])) {
    echo "<div style='text-align:center;margin-bottom:20px;'>
            <a href='create.php' style='background:#2196F3;color:white;padding:8px 15px;border-radius:5px;text-decoration:none;'>✍️ Create New Post</a>
          </div>";
}

$sql = "SELECT posts.*, categories.name AS category_name, users.name AS author_name
        FROM posts
        LEFT JOIN categories ON posts.category_id = categories.id
        LEFT JOIN users ON posts.user_id = users.id
        ORDER BY posts.id DESC";

$result = $conn->query($sql);

if ($result && $result->num_rows > 0):
    while ($row = $result->fetch_assoc()):
        ?>
        <div style="border:1px solid #ddd; padding:15px; margin:10px auto; max-width:700px;">
            <h3>
                <a href="show.php?id=<?= $row['id']; ?>" style="color:#333; text-decoration:none;">
                    <?= htmlspecialchars($row['title']); ?>
                </a>
            </h3>
            <small>
                <?= htmlspecialchars($row['author_name']); ?> |
                <?= htmlspecialchars($row['category_name']); ?> |
                <?= date("M d, Y", strtotime($row['created_at'])); ?>
            </small>
            <br><br>
            <a href="show.php?id=<?= $row['id']; ?>" style="color:#2196F3;">👁 View</a>

            <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
                | <a href="edit.php?id=<?= $row['id']; ?>" style="color:#4CAF50;">✏️ Edit</a>
                | <a href="delete.php?id=<?= $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this post?')" style="color:red;">🗑 Delete</a>
            <?php endif; ?>
        </div>
    <?php
    endwhile;
else:
    echo "<p style='text-align:center;'>No posts found.</p>";
endif;

include '../includes/footer.php';
?>
