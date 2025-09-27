<?php
session_start();
include "db.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

// ✅ Update booking status
if (isset($_GET['update']) && isset($_GET['status'])) {
    $id = intval($_GET['update']);
    $status = $_GET['status'];
    $allowed = ['pending','confirmed','completed','cancelled'];
    if (in_array($status, $allowed)) {
        $conn->query("UPDATE bookings SET status='$status' WHERE id=$id");
    }
    header("Location: admin_bookings.php");
    exit;
}

// ✅ Delete booking
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM bookings WHERE id=$id");
    header("Location: admin_bookings.php");
    exit;
}

// ✅ Fetch all bookings with user + business info
$sql = "SELECT b.*, u.name AS customer_name, bs.name AS business_name
        FROM bookings b
        JOIN users u ON b.user_id = u.id
        JOIN businesses bs ON b.business_id = bs.id
        ORDER BY b.id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Bookings</title>
</head>
<body>
    <h1>Manage Bookings</h1>
    <a href="admin_dashboard.php">⬅ Back to Dashboard</a> | 
    <a href="admin_logout.php">Logout</a>
    <hr>

    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Customer</th>
            <th>Business</th>
            <th>Type</th>
            <th>Item ID</th>
            <th>Qty</th>
            <th>Status</th>
            <th>Booking Date</th>
            <th>Action</th>
        </tr>
        <?php while($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['customer_name']; ?></td>
            <td><?php echo $row['business_name']; ?></td>
            <td><?php echo ucfirst($row['type']); ?></td>
            <td><?php echo $row['item_id']; ?></td>
            <td><?php echo $row['quantity']; ?></td>
            <td><?php echo ucfirst($row['status']); ?></td>
            <td><?php echo $row['booking_date']; ?></td>
            <td>
                <!-- Status update links -->
                <?php if ($row['status'] != 'confirmed') { ?>
                    <a href="?update=<?php echo $row['id']; ?>&status=confirmed">Confirm</a> | 
                <?php } ?>
                <?php if ($row['status'] != 'completed') { ?>
                    <a href="?update=<?php echo $row['id']; ?>&status=completed">Complete</a> | 
                <?php } ?>
                <?php if ($row['status'] != 'cancelled') { ?>
                    <a href="?update=<?php echo $row['id']; ?>&status=cancelled">Cancel</a> | 
                <?php } ?>
                <a href="?delete=<?php echo $row['id']; ?>" 
                   onclick="return confirm('Delete this booking?');">Delete</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
