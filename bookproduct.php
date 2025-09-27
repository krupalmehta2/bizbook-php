<?php
session_start();
include "db.php";

if (!isset($_SESSION['customer_id'])) {
    header("Location: customerlogin.php");
    exit;
}

$customer_id = $_SESSION['customer_id'];
$business_id = intval($_GET['business_id']);

// Fetch products
$result = $conn->query("SELECT * FROM products WHERE business_id=$business_id");

// Book product
if(isset($_POST['book'])){
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $booking_date = date('Y-m-d H:i:s');

    $conn->query("INSERT INTO bookings (user_id,business_id,type,item_id,quantity,status,booking_date)
                  VALUES ($customer_id,$business_id,'product',$product_id,$quantity,'pending','$booking_date')");
    $success = "Product booked successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Book Products</title>
</head>
<body>
    <h1>Products</h1>
    <a href="businesslist.php">⬅ Back to Businesses</a> | 
    <a href="customerdashboard.php">Dashboard</a>
    <hr>
    <?php if(!empty($success)) echo "<p style='color:green;'>$success</p>"; ?>

    <table border="1" cellpadding="10">
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Action</th>
        </tr>
        <?php while($row=$result->fetch_assoc()){ ?>
        <tr>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo substr($row['description'],0,50); ?>...</td>
            <td><?php echo $row['price']; ?></td>
            <td><?php echo $row['stock']; ?></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                    <input type="number" name="quantity" value="1" min="1" max="<?php echo $row['stock']; ?>" required>
                    <button type="submit" name="book">Book</button>
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
