<?php
session_start();
include "db.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

// ✅ Delete review
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM reviews WHERE id=$id");
    header("Location: admin_reviews.php");
    exit;
}

// ✅ Fetch reviews with user + business info
$sql = "SELECT r.*, u.name AS customer_name, b.name AS business_name
        FROM reviews r
        JOIN users u ON r.user_id = u.id
        JOIN businesses b ON r.business_id = b.id
        ORDER BY r.id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Reviews</title>
</head>
<body>
    <h1>Manage Reviews</h1>
    <a href="admin_dashboard.php">⬅ Back to Dashboard</a> | 
    <a href="admin_logout.php">Logout</a>
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
