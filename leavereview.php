<?php
session_start();
include "db.php";

if (!isset($_SESSION['customer_id'])) {
    header("Location: customerlogin.php");
    exit;
}

$customer_id = $_SESSION['customer_id'];
$business_id = intval($_GET['business_id']);

// Submit review
if(isset($_POST['submit'])){
    $rating = intval($_POST['rating']);
    $comment = $_POST['comment'];

    $conn->query("INSERT INTO reviews (business_id,user_id,rating,comment)
                  VALUES ($business_id,$customer_id,$rating,'$comment')");
    $success = "Review submitted!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Leave Review</title>
</head>
<body>
    <h1>Leave Review</h1>
    <a href="customerdashboard.php">⬅ Back to Dashboard</a>
    <hr>
    <?php if(!empty($success)) echo "<p style='color:green;'>$success</p>"; ?>

    <form method="POST">
        <label>Rating (1-5):</label>
        <input type="number" name="rating" min="1" max="5" required><br><br>
        <label>Comment:</label><br>
        <textarea name="comment" rows="4" cols="50" required></textarea><br><br>
        <button type="submit" name="submit">Submit Review</button>
    </form>
</body>
</html>
