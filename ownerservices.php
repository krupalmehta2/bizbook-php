<?php
session_start();
include "db.php";

if (!isset($_SESSION['owner_id'])) {
    header("Location: ownerlogin.php");
    exit;
}

$owner_id = $_SESSION['owner_id'];

// Fetch appointment-type businesses of owner
$biz_result = $conn->query("SELECT * FROM businesses WHERE user_id=$owner_id AND type='appointment' ORDER BY name ASC");

// Add service
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['add'])) {
    $business_id = $_POST['business_id'];
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $duration = $_POST['duration']; // in minutes
    $price = $_POST['price'];

    $conn->query("INSERT INTO services (business_id, name, description, duration, price) 
                  VALUES ('$business_id','$name','$desc','$duration','$price')");
    header("Location: ownerservices.php");
    exit;
}

// Delete service
if (isset($_GET['delete'])) {
    $sid = intval($_GET['delete']);
    $conn->query("DELETE s FROM services s 
                  JOIN businesses b ON s.business_id=b.id 
                  WHERE s.id=$sid AND b.user_id=$owner_id");
    header("Location: ownerservices.php");
    exit;
}

// Fetch owner services
$result = $conn->query("SELECT s.*, b.name AS business_name 
                        FROM services s 
                        JOIN businesses b ON s.business_id=b.id 
                        WHERE b.user_id=$owner_id ORDER BY s.id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Owner Services</title>
</head>
<body>
    <h1>Manage Services (Appointment-Based Businesses)</h1>
    <a href="ownerdashboard.php">⬅ Back to Dashboard</a> | 
    <a href="ownerlogout.php">Logout</a>
    <hr>

    <!-- Add Service Form -->
    <h3>Add New Service</h3>
    <form method="POST">
        <select name="business_id" required>
            <option value="">Select Business</option>
            <?php while($biz = $biz_result->fetch_assoc()) { ?>
                <option value="<?php echo $biz['id']; ?>"><?php echo $biz['name']; ?></option>
            <?php } ?>
        </select><br><br>
        <input type="text" name="name" placeholder="Service Name" required><br><br>
        <textarea name="description" placeholder="Description"></textarea><br><br>
        <input type="number" name="duration" placeholder="Duration (minutes)" required><br><br>
        <input type="number" step="0.01" name="price" placeholder="Price" required><br><br>
        <button type="submit" name="add">Add Service</button>
    </form>
    <hr>

    <!-- List Services -->
    <h3>Your Services</h3>
    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Business</th>
            <th>Name</th>
            <th>Description</th>
            <th>Duration (min)</th>
            <th>Price</th>
            <th>Action</th>
        </tr>
        <?php while($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['business_name']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo substr($row['description'],0,50); ?>...</td>
            <td><?php echo $row['duration']; ?></td>
            <td><?php echo $row['price']; ?></td>
            <td>
                <a href="ownerservices.php?delete=<?php echo $row['id']; ?>" 
                   onclick="return confirm('Delete this service?');">Delete</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
