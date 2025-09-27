<?php
session_start();
include "db.php";

if (!isset($_SESSION['owner_id'])) {
    header("Location: ownerlogin.php");
    exit;
}

$owner_id = $_SESSION['owner_id'];

// Delete review (optional)
if (isset($_GET['delete'])) {
    $rid = intval($_GET['delete']);
    $conn->query("DELETE r FROM reviews r 
                  JOIN businesses b ON r.business_id=b.id 
                  WHERE r.id=$rid AND b.user_id=$owner_id");
    header("Location: ownerreviews.php");
    exit;
}

// Fetch reviews for owner businesses
$sql = "SELECT r.*, u.name AS customer_name, b.name AS business_name
        FROM reviews r
        JOIN users u ON r.user_id = u.id
        JOIN businesses b ON r.business_id = b.id
        WHERE b.user_id=$owner_id
        ORDER BY r.id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Owner Reviews</title>
</head>
<body>
    <h1>Reviews for Your Businesses</h1>
    <a href="ownerdashboard.php">⬅ Back to Dashboard</a> | 
    <a href="ownerlogout.php">Logout</a>
    <hr>

    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Business</th>
            <th>Customer</th>
            <th>Rating</th>
            <th>Comment</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
        <?php while($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['business_name']; ?></td>
            <td><?php echo $row['customer_name']; ?></td>
            <td><?php echo $row['rating']; ?>/5</td>
            <td><?php echo substr($row['comment'],0,50); ?>...</td>
            <td><?php echo $row['created_at']; ?></td>
            <td>
                <a href="?delete=<?php echo $row['id']; ?>" 
                   onclick="return confirm('Delete this review?');">Delete</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
