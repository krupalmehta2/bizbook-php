<?php
session_start();
include "db.php";

if (!isset($_SESSION['owner_id'])) {
    header("Location: ownerlogin.php");
    exit;
}

$owner_id = $_SESSION['owner_id'];

// Fetch table-type businesses of owner
$biz_result = $conn->query("SELECT * FROM businesses WHERE user_id=$owner_id AND type='table' ORDER BY name ASC");

// Add table
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['add'])) {
    $business_id = $_POST['business_id'];
    $table_number = $_POST['table_number'];
    $capacity = $_POST['capacity'];
    $status = $_POST['status'];

    $conn->query("INSERT INTO tables (business_id, table_number, capacity, status) 
                  VALUES ('$business_id','$table_number','$capacity','$status')");
    header("Location: ownertables.php");
    exit;
}

// Delete table
if (isset($_GET['delete'])) {
    $tid = intval($_GET['delete']);
    $conn->query("DELETE t FROM tables t 
                  JOIN businesses b ON t.business_id=b.id 
                  WHERE t.id=$tid AND b.user_id=$owner_id");
    header("Location: ownertables.php");
    exit;
}

// Fetch owner tables
$result = $conn->query("SELECT t.*, b.name AS business_name 
                        FROM tables t 
                        JOIN businesses b ON t.business_id=b.id 
                        WHERE b.user_id=$owner_id ORDER BY t.id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Owner Tables</title>
</head>
<body>
    <h1>Manage Tables (Table-Based Businesses)</h1>
    <a href="ownerdashboard.php">⬅ Back to Dashboard</a> | 
    <a href="ownerlogout.php">Logout</a>
    <hr>

    <!-- Add Table Form -->
    <h3>Add New Table</h3>
    <form method="POST">
        <select name="business_id" required>
            <option value="">Select Business</option>
            <?php while($biz = $biz_result->fetch_assoc()) { ?>
                <option value="<?php echo $biz['id']; ?>"><?php echo $biz['name']; ?></option>
            <?php } ?>
        </select><br><br>
        <input type="number" name="table_number" placeholder="Table Number" required><br><br>
        <input type="number" name="capacity" placeholder="Capacity" required><br><br>
        <select name="status" required>
            <option value="available">Available</option>
            <option value="reserved">Reserved</option>
        </select><br><br>
        <button type="submit" name="add">Add Table</button>
    </form>
    <hr>

    <!-- List Tables -->
    <h3>Your Tables</h3>
    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Business</th>
            <th>Table Number</th>
            <th>Capacity</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php while($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['business_name']; ?></td>
            <td><?php echo $row['table_number']; ?></td>
            <td><?php echo $row['capacity']; ?></td>
            <td><?php echo ucfirst($row['status']); ?></td>
            <td>
                <a href="ownertables.php?delete=<?php echo $row['id']; ?>" 
                   onclick="return confirm('Delete this table?');">Delete</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
