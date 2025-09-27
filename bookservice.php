<?php
session_start();
include "db.php";

if (!isset($_SESSION['customer_id'])) {
    header("Location: customerlogin.php");
    exit;
}

$customer_id = $_SESSION['customer_id'];
$business_id = intval($_GET['business_id']);

// Fetch services
$result = $conn->query("SELECT * FROM services WHERE business_id=$business_id");

// Book service
if(isset($_POST['book'])){
    $service_id = $_POST['service_id'];
    $booking_date = $_POST['booking_date']; // chosen by customer

    $conn->query("INSERT INTO bookings (user_id,business_id,type,item_id,quantity,status,booking_date)
                  VALUES ($customer_id,$business_id,'service',$service_id,1,'pending','$booking_date')");
    $success = "Service booked successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Book Services</title>
</head>
<body>
    <h1>Services</h1>
    <a href="businesslist.php">⬅ Back to Businesses</a> | 
    <a href="customerdashboard.php">Dashboard</a>
    <hr>
    <?php if(!empty($success)) echo "<p style='color:green;'>$success</p>"; ?>

    <table border="1" cellpadding="10">
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Duration (min)</th>
            <th>Price</th>
            <th>Book</th>
        </tr>
        <?php while($row=$result->fetch_assoc()){ ?>
        <tr>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo substr($row['description'],0,50); ?>...</td>
            <td><?php echo $row['duration']; ?></td>
            <td><?php echo $row['price']; ?></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="service_id" value="<?php echo $row['id']; ?>">
                    <input type="datetime-local" name="booking_date" required>
                    <button type="submit" name="book">Book</button>
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
