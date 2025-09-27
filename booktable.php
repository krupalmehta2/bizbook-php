<?php
session_start();
include "db.php";

if (!isset($_SESSION['customer_id'])) {
    header("Location: customerlogin.php");
    exit;
}

$customer_id = $_SESSION['customer_id'];
$business_id = intval($_GET['business_id']);

// Fetch available tables
$result = $conn->query("SELECT * FROM tables WHERE business_id=$business_id AND status='available'");

// Book table
if(isset($_POST['book'])){
    $table_id = $_POST['table_id'];
    $booking_date = $_POST['booking_date'];

    $conn->query("INSERT INTO bookings (user_id,business_id,type,item_id,quantity,status,booking_date)
                  VALUES ($customer_id,$business_id,'table',$table_id,1,'pending','$booking_date')");
    $conn->query("UPDATE tables SET status='reserved' WHERE id=$table_id"); // mark table as reserved
    $success = "Table booked successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Book Tables</title>
</head>
<body>
    <h1>Tables</h1>
    <a href="businesslist.php">⬅ Back to Businesses</a> | 
    <a href="customerdashboard.php">Dashboard</a>
    <hr>
    <?php if(!empty($success)) echo "<p style='color:green;'>$success</p>"; ?>

    <table border="1" cellpadding="10">
        <tr>
            <th>Table Number</th>
            <th>Capacity</th>
            <th>Status</th>
            <th>Book</th>
        </tr>
        <?php while($row=$result->fetch_assoc()){ ?>
        <tr>
            <td><?php echo $row['table_number']; ?></td>
            <td><?php echo $row['capacity']; ?></td>
            <td><?php echo ucfirst($row['status']); ?></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="table_id" value="<?php echo $row['id']; ?>">
                    <input type="datetime-local" name="booking_date" required>
                    <button type="submit" name="book">Book</button>
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
