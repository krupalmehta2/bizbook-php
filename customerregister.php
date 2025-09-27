<?php
session_start();
include "db.php";

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = md5($_POST['password']); // store as MD5
    $contact = $_POST['contact'];

    // Check if email already exists
    $check = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($check->num_rows > 0) {
        $error = "Email already registered!";
    } else {
        $conn->query("INSERT INTO users (name,email,password,contact,role) 
                      VALUES ('$name','$email','$password','$contact','customer')");
        $success = "Registration successful! <a href='customerlogin.php'>Login here</a>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Registration</title>
</head>
<body>
    <h2>Customer Registration</h2>
    <?php 
        if (!empty($error)) echo "<p style='color:red;'>$error</p>"; 
        if (!empty($success)) echo "<p style='color:green;'>$success</p>";
    ?>
    <form method="POST">
        <input type="text" name="name" placeholder="Full Name" required><br><br>
        <input type="email" name="email" placeholder="Email" required><br><br>
        <input type="password" name="password" placeholder="Password" required><br><br>
        <input type="text" name="contact" placeholder="Contact Number"><br><br>
        <button type="submit" name="register">Register</button>
    </form>
    <p>Already have an account? <a href="customerlogin.php">Login here</a></p>
</body>
</html>
