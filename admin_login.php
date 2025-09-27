<?php
session_start();
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = md5($_POST['password']); // since default admin is MD5 stored

    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password' AND role='admin'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $_SESSION['admin_id'] = $row['id'];
        $_SESSION['admin_name'] = $row['name'];
        header("Location: admin_dashboard.php");
        exit;
    } else {
        $error = "Invalid Email or Password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
</head>
<body>
    <h2>Admin Login</h2>
    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST">
        <input type="email" name="email" placeholder="Admin Email" required><br><br>
        <input type="password" name="password" placeholder="Password" required><br><br>
        <button type="submit">Login</button>
    </form>
</body>
</html>
