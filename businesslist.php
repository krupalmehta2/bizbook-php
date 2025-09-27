<?php
session_start();
include "db.php";

if (!isset($_SESSION['customer_id'])) {
    header("Location: customerlogin.php");
    exit;
}

$type = isset($_GET['type']) ? $_GET['type'] : 'order'; // default show order type
$customer_id = $_SESSION['customer_id'];

// Fetch businesses of selected type
$result = $conn->query("SELECT * FROM businesses WHERE type='$type' ORDER BY name ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Browse Businesses</title>
</head>
<body>
    <h1>Browse Businesses</h1>
    <a href="customerdashboard.php">⬅ Back to Dashboard</a> | 
    <a href="customerlogout.php">Logout</a>
    <hr>

    <h3>Filter by Type:</h3>
    <a href="businesslist.php?type=order">Order</a> | 
    <a href="businesslist.php?type=appointment">Appointment</a> | 
    <a href="businesslist.php?type=table">Table</a>
    <hr>

    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Type</th>
            <th>Description</th>
            <th>Action</th>
        </tr>
        <?php while($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo ucfirst($row['type']); ?></td>
            <td><?php echo substr($row['description'],0,50); ?>...</td>
            <td>
                <?php if($row['type']=='order'){ ?>
                    <a href="bookproduct.php?business_id=<?php echo $row['id']; ?>">View Products</a>
                <?php } elseif($row['type']=='appointment'){ ?>
                    <a href="bookservice.php?business_id=<?php echo $row['id']; ?>">View Services</a>
                <?php } else { ?>
                    <a href="booktable.php?business_id=<?php echo $row['id']; ?>">View Tables</a>
                <?php } ?>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
