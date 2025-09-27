<?php
session_start();
include "db.php";

if (!isset($_SESSION['owner_id'])) {
    header("Location: ownerlogin.php");
    exit;
}

$owner_id = $_SESSION['owner_id'];

// Count of owner businesses
$total_businesses = $conn->query("SELECT COUNT(*) AS total FROM businesses WHERE user_id=$owner_id")->fetch_assoc()['total'];
$total_products = $conn->query("SELECT COUNT(*) AS total FROM products p JOIN businesses b ON p.business_id=b.id WHERE b.user_id=$owner_id")->fetch_assoc()['total'];
$total_services = $conn->query("SELECT COUNT(*) AS total FROM services s JOIN businesses b ON s.business_id=b.id WHERE b.user_id=$owner_id")->fetch_assoc()['total'];
$total_bookings = $conn->query("SELECT COUNT(*) AS total FROM bookings b JOIN businesses bs ON b.business_id=bs.id WHERE bs.user_id=$owner_id")->fetch_assoc()['total'];
$total_reviews = $conn->query("SELECT COUNT(*) AS total FROM reviews r JOIN businesses bs ON r.business_id=bs.id WHERE bs.user_id=$owner_id")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Owner Dashboard</title>
</head>
<body>
    <h1>Welcome, <?php echo $_SESSION['owner_name']; ?> (Owner)</h1>
    <a href="ownerlogout.php">Logout</a>
    <hr>

    <h2>Dashboard Stats</h2>
    <ul>
        <li>Total Businesses: <?php echo $total_businesses; ?> (<a href="ownerbusinesses.php">Manage</a>)</li>
        <li>Total Products: <?php echo $total_products; ?> (<a href="ownerproducts.php">Manage</a>)</li>
        <li>Total Services: <?php echo $total_services; ?> (<a href="ownerservices.php">Manage</a>)</li>
        <li>Total Bookings: <?php echo $total_bookings; ?> (<a href="ownerbookings.php">Manage</a>)</li>
        <li>Total Reviews: <?php echo $total_reviews; ?> (<a href="ownerreviews.php">View</a>)</li>
    </ul>
</body>
</html>
