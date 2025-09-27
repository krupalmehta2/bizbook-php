<?php
session_start();
include "db.php";

if (!isset($_SESSION['admin_id'])) header("Location: admin_login.php");

// Fetch appointment-based businesses
$businesses = $conn->query("SELECT id,name FROM businesses WHERE type='appointment' ORDER BY name ASC");

// Add/Edit/Delete Service
if(isset($_POST['add_service'])){
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $duration = intval($_POST['duration']);
    $price = floatval($_POST['price']);
    $business_id = intval($_POST['business_id']);

    $conn->query("INSERT INTO services (business_id,name,description,duration,price)
                  VALUES ($business_id,'$name','$description',$duration,$price)");
    header("Location: admin_services.php"); exit;
}

if(isset($_POST['edit_service'])){
    $id = intval($_POST['id']);
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $duration = intval($_POST['duration']);
    $price = floatval($_POST['price']);
    $business_id = intval($_POST['business_id']);

    $conn->query("UPDATE services SET business_id=$business_id,name='$name',description='$description',
                  duration=$duration, price=$price WHERE id=$id");
    header("Location: admin_services.php"); exit;
}

if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM services WHERE id=$id");
    header("Location: admin_services.php"); exit;
}

// Fetch all services with business name
$result = $conn->query("SELECT s.*, b.name AS business_name FROM services s
                        JOIN businesses b ON s.business_id=b.id ORDER BY s.id DESC");

$edit_service = null;
if(isset($_GET['edit'])){
    $id = intval($_GET['edit']);
    $edit_service = $conn->query("SELECT * FROM services WHERE id=$id")->fetch_assoc();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Services</title>
</head>
<body>
<h1>Manage Services</h1>
<a href="admin_dashboard.php">⬅ Dashboard</a> | <a href="admin_logout.php">Logout</a>
<hr>

<h2><?php echo $edit_service ? "Edit Service" : "Add Service"; ?></h2>
<form method="POST">
    <?php if($edit_service){ ?><input type="hidden" name="id" value="<?php echo $edit_service['id']; ?>"><?php } ?>
    Name: <input type="text" name="name" required value="<?php echo $edit_service['name'] ?? ''; ?>"><br>
    Description:<br><textarea name="description" rows="4"><?php echo $edit_service['description'] ?? ''; ?></textarea><br>
    Duration (minutes): <input type="number" name="duration" required value="<?php echo $edit_service['duration'] ?? ''; ?>"><br>
    Price: <input type="number" step="0.01" name="price" required value="<?php echo $edit_service['price'] ?? ''; ?>"><br>
    Business: 
    <select name="business_id" required>
        <?php while($b = $businesses->fetch_assoc()){ ?>
            <option value="<?php echo $b['id']; ?>" <?php if(isset($edit_service) && $edit_service['business_id']==$b['id']) echo 'selected'; ?>>
                <?php echo $b['name']; ?>
            </option>
        <?php } ?>
    </select><br><br>
    <button type="submit" name="<?php echo $edit_service ? 'edit_service' : 'add_service'; ?>">
        <?php echo $edit_service ? 'Update Service' : 'Add Service'; ?>
    </button>
    <?php if($edit_service){ ?><a href="admin_services.php">Cancel</a><?php } ?>
</form>
<hr>

<table border="1" cellpadding="10">
<tr><th>ID</th><th>Name</th><th>Business</th><th>Duration</th><th>Price</th><th>Action</th></tr>
<?php while($row=$result->fetch_assoc()){ ?>
<tr>
<td><?php echo $row['id']; ?></td>
<td><?php echo $row['name']; ?></td>
<td><?php echo $row['business_name']; ?></td>
<td><?php echo $row['duration']; ?> min</td>
<td><?php echo $row['price']; ?></td>
<td>
<a href="?edit=<?php echo $row['id']; ?>">Edit</a> | 
<a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete?');">Delete</a>
</td>
</tr>
<?php } ?>
</table>
</body>
</html>
