<?php
session_start();
include "db.php";

if (!isset($_SESSION['customer_id'])) {
    header("Location: customerlogin.php");
    exit;
}

$customer_id = $_SESSION['customer_id'];

// Cancel booking
if(isset($_GET['cancel'])){
    $id = intval($_GET['cancel']);
    $conn->query("UPDATE bookings SET status='cancelled' WHERE id=$id AND user_id=$customer_id");
    header("Location: mybookings.php");
    exit;
}

// Fetch bookings
$result = $conn->query("SELECT b.*, bs.name AS business_name 
                        FROM bookings b 
                        JOIN businesses bs ON b.business_id = bs.id 
                        WHERE b.user_id=$customer_id 
                        ORDER BY b.id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Bookings</title>
</head>
<body>
    <h1>My Bookings</h1>
    <a href="customerdashboard.php">⬅ Back to Dashboard</a>
    <hr>

    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Business</th>
            <th>Type</th>
            <th>Item ID</th>
            <th>Quantity</th>
            <th>Status</th>
            <th>Booking Date</th>
            <th>Action</th>
        </tr>
        <?php while($row=$result->fetch_assoc()){ ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['business_name']; ?></td>
            <td><?php echo ucfirst($row['type']); ?></td>
            <td><?php echo $row['item_id']; ?></td>
            <td><?php echo $row['quantity']; ?></td>
            <td><?php echo ucfirst($row['status']); ?></td>
            <td><?php echo $row['booking_date']; ?></td>
            <td>
                <?php if($row['status']=='pending'){ ?>
                <a href="?cancel=<?php echo $row['id']; ?>" onclick="return confirm('Cancel booking?');">Cancel</a>
                <?php } ?>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
