<?php
session_start();
include "db.php";

if (!isset($_SESSION['owner_id'])) {
    header("Location: ownerlogin.php");
    exit;
}

$owner_id = $_SESSION['owner_id'];

// Add business
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['add'])) {
    $name = $_POST['name'];
    $type = $_POST['type']; // order / appointment / table
    $desc = $_POST['description'];

    $conn->query("INSERT INTO businesses (user_id, name, type, description) VALUES ('$owner_id','$name','$type','$desc')");
    header("Location: ownerbusinesses.php");
    exit;
}

// Delete business
if (isset($_GET['delete'])) {
    $bid = intval($_GET['delete']);
    $conn->query("DELETE FROM businesses WHERE id=$bid AND user_id=$owner_id");
    header("Location: ownerbusinesses.php");
    exit;
}

// Fetch owner businesses
$result = $conn->query("SELECT * FROM businesses WHERE user_id=$owner_id ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Owner Businesses</title>
</head>
<body>
    <h1>Manage Your Businesses</h1>
    <a href="ownerdashboard.php">⬅ Back to Dashboard</a> | 
    <a href="ownerlogout.php">Logout</a>
    <hr>

    <!-- Add Business Form -->
    <h3>Add New Business</h3>
    <form method="POST">
        <input type="text" name="name" placeholder="Business Name" required><br><br>
        <select name="type" required>
            <option value="">Select Type</option>
            <option value="order">Order</option>
            <option value="appointment">Appointment</option>
            <option value="table">Table</option>
        </select><br><br>
        <textarea name="description" placeholder="Description"></textarea><br><br>
        <button type="submit" name="add">Add Business</button>
    </form>
    <hr>

    <!-- List Businesses -->
    <h3>Your Businesses</h3>
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
                <a href="ownerbusinesses.php?delete=<?php echo $row['id']; ?>" 
                   onclick="return confirm('Delete this business?');">Delete</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
