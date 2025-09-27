<?php
session_start();
include "db.php";
if(!isset($_SESSION['admin_id'])) header("Location: admin_login.php");

// Fetch table-based businesses
$businesses = $conn->query("SELECT id,name FROM businesses WHERE type='table' ORDER BY name ASC");

// Add/Edit/Delete Table
if(isset($_POST['add_table'])){
    $business_id = intval($_POST['business_id']);
    $table_number = intval($_POST['table_number']);
    $capacity = intval($_POST['capacity']);
    $conn->query("INSERT INTO tables (business_id,table_number,capacity) VALUES ($business_id,$table_number,$capacity)");
    header("Location: admin_tables.php"); exit;
}

if(isset($_POST['edit_table'])){
    $id = intval($_POST['id']);
    $business_id = intval($_POST['business_id']);
    $table_number = intval($_POST['table_number']);
    $capacity = intval($_POST['capacity']);
    $conn->query("UPDATE tables SET business_id=$business_id,table_number=$table_number,capacity=$capacity WHERE id=$id");
    header("Location: admin_tables.php"); exit;
}

if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM tables WHERE id=$id");
    header("Location: admin_tables.php"); exit;
}

// Fetch all tables with business name
$result = $conn->query("SELECT t.*, b.name AS business_name FROM tables t 
                        JOIN businesses b ON t.business_id=b.id ORDER BY t.id DESC");

$edit_table = null;
if(isset($_GET['edit'])){
    $id = intval($_GET['edit']);
    $edit_table = $conn->query("SELECT * FROM tables WHERE id=$id")->fetch_assoc();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Tables</title>
</head>
<body>
<h1>Manage Tables</h1>
<a href="admin_dashboard.php">⬅ Dashboard</a> | <a href="admin_logout.php">Logout</a>
<hr>

<h2><?php echo $edit_table ? "Edit Table" : "Add Table"; ?></h2>
<form method="POST">
<?php if($edit_table){ ?><input type="hidden" name="id" value="<?php echo $edit_table['id']; ?>"><?php } ?>
Business: 
<select name="business_id" required>
<?php while($b=$businesses->fetch_assoc()){ ?>
<option value="<?php echo $b['id']; ?>" <?php if(isset($edit_table) && $edit_table['business_id']==$b['id']) echo 'selected'; ?>>
<?php echo $b['name']; ?></option>
<?php } ?>
</select><br>
Table Number: <input type="number" name="table_number" required value="<?php echo $edit_table['table_number'] ?? ''; ?>"><br>
Capacity: <input type="number" name="capacity" required value="<?php echo $edit_table['capacity'] ?? ''; ?>"><br><br>
<button type="submit" name="<?php echo $edit_table ? 'edit_table' : 'add_table'; ?>">
<?php echo $edit_table ? 'Update Table' : 'Add Table'; ?></button>
<?php if($edit_table){ ?><a href="admin_tables.php">Cancel</a><?php } ?>
</form>
<hr>

<table border="1" cellpadding="10">
<tr><th>ID</th><th>Business</th><th>Table No</th><th>Capacity</th><th>Action</th></tr>
<?php while($row=$result->fetch_assoc()){ ?>
<tr>
<td><?php echo $row['id']; ?></td>
<td><?php echo $row['business_name']; ?></td>
<td><?php echo $row['table_number']; ?></td>
<td><?php echo $row['capacity']; ?></td>
<td>
<a href="?edit=<?php echo $row['id']; ?>">Edit</a> | 
<a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete?');">Delete</a>
</td>
</tr>
<?php } ?>
</table>
</body>
</html>
