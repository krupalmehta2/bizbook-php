<?php
session_start();
include "db.php";

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password' AND role='customer'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $_SESSION['customer_id'] = $row['id'];
        $_SESSION['customer_name'] = $row['name'];
        header("Location: customerdashboard.php");
        exit;
    } else {
        $error = "Invalid Email or Password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Login</title>
</head>
<body>
    <h2>Customer Login</h2>
    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST">
        <input type="email" name="email" placeholder="Email" required><br><br>
        <input type="password" name="password" placeholder="Password" required><br><br>
        <button type="submit" name="login">Login</button>
    </form>
    <p>Don't have an account? <a href="customerregister.php">Register here</a></p>
</body>
</html>
