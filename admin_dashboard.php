<?php
session_start();
include "db.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: adminlogin.php"); // ensure this is correct
    exit;
}

// Fetch counts
$total_users = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];
$total_businesses = $conn->query("SELECT COUNT(*) AS total FROM businesses")->fetch_assoc()['total'];
$total_products = $conn->query("SELECT COUNT(*) AS total FROM products")->fetch_assoc()['total'];
$total_services = $conn->query("SELECT COUNT(*) AS total FROM services")->fetch_assoc()['total'];
$total_tables = $conn->query("SELECT COUNT(*) AS total FROM tables")->fetch_assoc()['total'];
$total_bookings = $conn->query("SELECT COUNT(*) AS total FROM bookings")->fetch_assoc()['total'];
$total_reviews = $conn->query("SELECT COUNT(*) AS total FROM reviews")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; background:#f4f6f9; margin:0; padding:0;}
        header { background:#007bff; color:white; padding:20px; text-align:center;}
        header h1 { margin:0; font-size:28px;}
        .container { max-width:1200px; margin:20px auto; padding:0 20px;}
        .cards { display:flex; flex-wrap:wrap; gap:20px; }
        .card { background:white; padding:20px; border-radius:10px; flex:1; min-width:200px; box-shadow:0 2px 6px rgba(0,0,0,0.2);}
        .card h2 { margin-top:0; color:#007bff;}
        .card a { text-decoration:none; color:#28a745; font-weight:bold;}
        .top-links { margin:10px 0; text-align:right; }
        .top-links a { margin-left:15px; text-decoration:none; color:#007bff; font-weight:bold;}
    </style>
</head>
<body>
    <header>
        <h1>Welcome, <?php echo $_SESSION['admin_name']; ?> (Admin)</h1>
    </header>

    <div class="container">
        <div class="top-links">
            <a href="admin_logout.php">Logout</a>
            <a href="ownerlogin.php">Go to Owner Panel</a>
        </div>

        <div class="cards">
            <div class="card">
                <h2>Users</h2>
                <p>Total Users: <?php echo $total_users; ?></p>
                <a href="admin_users.php">Manage Users</a>
            </div>
            <div class="card">
                <h2>Businesses</h2>
                <p>Total Businesses: <?php echo $total_businesses; ?></p>
                <a href="admin_businesses.php">Manage Businesses</a>
            </div>
            <div class="card">
                <h2>Products</h2>
                <p>Total Products: <?php echo $total_products; ?></p>
                <a href="admin_products.php">Manage Products</a>
            </div>
            <div class="card">
                <h2>Services</h2>
                <p>Total Services: <?php echo $total_services; ?></p>
                <a href="admin_services.php">Manage Services</a>
            </div>
            <div class="card">
                <h2>Tables</h2>
                <p>Total Tables: <?php echo $total_tables; ?></p>
                <a href="admin_tables.php">Manage Tables</a>
            </div>
            <div class="card">
                <h2>Bookings</h2>
                <p>Total Bookings: <?php echo $total_bookings; ?></p>
                <a href="admin_bookings.php">Manage Bookings</a>
            </div>
            <div class="card">
                <h2>Reviews</h2>
                <p>Total Reviews: <?php echo $total_reviews; ?></p>
                <a href="admin_reviews.php">Manage Reviews</a>
            </div>
        </div>
    </div>
</body>
</html>
