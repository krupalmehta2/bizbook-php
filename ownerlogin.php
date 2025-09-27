<?php
session_start();
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']); // manually entered password

    // Fetch owner
    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password' AND role='owner' LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $_SESSION['owner_id'] = $row['id'];
        $_SESSION['owner_name'] = $row['name'];
        header("Location: ownerdashboard.php");
        exit;
    } else {
        $error = "Invalid Email or Password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Owner Login - BizBook</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f9; text-align: center; padding-top: 80px; }
        form { display: inline-block; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 0 15px rgba(0,0,0,0.1); }
        input { display: block; width: 300px; margin: 10px auto; padding: 10px; border-radius: 5px; border: 1px solid #ccc; }
        button { padding: 10px 20px; border: none; border-radius: 5px; background: #28a745; color: white; cursor: pointer; }
        button:hover { background: #218838; }
        .error { color: red; margin-bottom: 15px; }
        a { text-decoration: none; color: #007bff; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <h2>Owner Login</h2>
    <?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>
    <form method="POST">
        <input type="email" name="email" placeholder="Owner Email" required>
        <input type="password" name="password" placeholder="Enter Password" required>
        <button type="submit">Login</button>
    </form>
    <p><a href="index.php">⬅ Back to Home</a></p>
</body>
</html>
