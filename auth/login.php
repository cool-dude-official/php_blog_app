<?php
session_start();
require_once "../config/db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Fetch user by email
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user["password"])) {
            $_SESSION["user"] = [
                    "id" => $user["id"],
                    "name" => $user["name"],
                    "email" => $user["email"],
                    "role" => $user["role"]
            ];

            // ✅ Redirect safely — NO ?id= in URL
            header("Location: ../posts/index.php");
            exit;
        } else {
            echo "<p style='color:red;text-align:center;'>❌ Invalid password!</p>";
        }
    } else {
        echo "<p style='color:red;text-align:center;'>❌ No account found with that email!</p>";
    }

    $stmt->close();
    $conn->close();
}
?>

<?php include "../includes/header.php"; ?>

<h2 style="text-align:center;">Login</h2>
<form method="POST" style="max-width:400px;margin:auto;">
    <input type="email" name="email" placeholder="Email" required style="width:100%;padding:8px;margin-bottom:10px;"><br>
    <input type="password" name="password" placeholder="Password" required style="width:100%;padding:8px;margin-bottom:10px;"><br>
    <button type="submit" style="width:100%;padding:10px;background:#2196F3;color:white;border:none;">Login</button>
</form>

<?php include "../includes/footer.php"; ?>
