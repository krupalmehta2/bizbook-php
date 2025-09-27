<?php
session_start();
include "db.php";

if (!isset($_SESSION['customer_id'])) {
    header("Location: customerlogin.php");
    exit;
}

$customer_id = $_SESSION['customer_id'];
$customer_name = $_SESSION['customer_name'];

// Fetch some stats
$total_bookings = $conn->query("SELECT COUNT(*) AS total FROM bookings WHERE user_id=$customer_id")->fetch_assoc()['total'];
$total_reviews = $conn->query("SELECT COUNT(*) AS total FROM reviews WHERE user_id=$customer_id")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Dashboard</title>
</head>
<body>
    <h1>Welcome, <?php echo $customer_name; ?> (Customer)</h1>
    <a href="customerlogout.php">Logout</a>
    <hr>

    <h2>Quick Links</h2>
    <ul>
        <li><a href="businesslist.php">Browse Businesses</a></li>
        <li><a href="mybookings.php">My Bookings (<?php echo $total_bookings; ?>)</a></li>
        <li><a href="myreviews.php">My Reviews (<?php echo $total_reviews; ?>)</a></li>
    </ul>
</body>
</html>
