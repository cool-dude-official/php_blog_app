<?php
session_start();
require_once "../config/db.php";

// Handle form submit
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);

    // Check if email already exists
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "<p style='color:red;text-align:center;'>❌ Email already registered. <a href='login.php'>Login here</a>.</p>";
    } else {
        // Insert user safely
        $sql = "INSERT INTO users (name, email, password, role_id) VALUES (?, ?, ?, 2)"; // 2 = contributor role
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $name, $email, $password);

        if ($stmt->execute()) {
            echo "<p style='color:green;text-align:center;'>✅ Registration successful! <a href='login.php'>Login now</a></p>";
        } else {
            echo "<p style='color:red;text-align:center;'>❌ Registration failed: " . htmlspecialchars($conn->error) . "</p>";
        }
        $stmt->close();
    }

    $check->close();
}
?>

<?php include "../includes/header.php"; ?>

<h2 style="text-align:center;">Create a New Account</h2>
<form method="POST" action="" style="max-width:400px;margin:auto;">
    <input type="text" name="name" placeholder="Full Name" required style="width:100%;padding:8px;margin-bottom:10px;"><br>
    <input type="email" name="email" placeholder="Email" required style="width:100%;padding:8px;margin-bottom:10px;"><br>
    <input type="password" name="password" placeholder="Password" required style="width:100%;padding:8px;margin-bottom:10px;"><br>
    <button type="submit" style="width:100%;padding:10px;background:#4CAF50;color:white;border:none;">Register</button>
</form>

<?php include "../includes/footer.php"; ?>
