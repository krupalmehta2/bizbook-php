<?php
session_start();
include "db.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

// Fetch owners for dropdown
$owners = $conn->query("SELECT id,name FROM users WHERE role='owner' ORDER BY name ASC");

// Add Business
if(isset($_POST['add_business'])){
    $name = $conn->real_escape_string($_POST['name']);
    $type = $_POST['type'];
    $description = $conn->real_escape_string($_POST['description']);
    $user_id = intval($_POST['user_id']);

    $conn->query("INSERT INTO businesses (user_id,name,type,description) 
                  VALUES ($user_id,'$name','$type','$description')");
    header("Location: admin_businesses.php");
    exit;
}

// Edit Business
if(isset($_POST['edit_business'])){
    $id = intval($_POST['id']);
    $name = $conn->real_escape_string($_POST['name']);
    $type = $_POST['type'];
    $description = $conn->real_escape_string($_POST['description']);
    $user_id = intval($_POST['user_id']);

    $conn->query("UPDATE businesses SET user_id=$user_id, name='$name', type='$type', description='$description' 
                  WHERE id=$id");
    header("Location: admin_businesses.php");
    exit;
}

// Delete Business
if(isset($_GET['delete'])){
    $bid = intval($_GET['delete']);
    $conn->query("DELETE FROM businesses WHERE id=$bid");
    header("Location: admin_businesses.php");
    exit;
}

// Fetch all businesses with owner name
$result = $conn->query("SELECT b.*, u.name AS owner_name FROM businesses b 
                        JOIN users u ON b.user_id=u.id ORDER BY b.id DESC");

// For edit form
$edit_business = null;
if(isset($_GET['edit'])){
    $id = intval($_GET['edit']);
    $edit_business = $conn->query("SELECT * FROM businesses WHERE id=$id")->fetch_assoc();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Businesses</title>
</head>
<body>
    <h1>Manage Businesses</h1>
    <a href="admin_dashboard.php">⬅ Back to Dashboard</a> | 
    <a href="admin_logout.php">Logout</a>
    <hr>

    <!-- Add/Edit Form -->
    <h2><?php echo $edit_business ? "Edit Business" : "Add Business"; ?></h2>
    <form method="POST">
        <?php if($edit_business){ ?>
            <input type="hidden" name="id" value="<?php echo $edit_business['id']; ?>">
        <?php } ?>
        Name: <input type="text" name="name" required value="<?php echo $edit_business['name'] ?? ''; ?>"><br>
        Type: 
        <select name="type" required>
            <option value="order" <?php if(isset($edit_business)&&$edit_business['type']=='order') echo 'selected'; ?>>Order</option>
            <option value="appointment" <?php if(isset($edit_business)&&$edit_business['type']=='appointment') echo 'selected'; ?>>Appointment</option>
            <option value="table" <?php if(isset($edit_business)&&$edit_business['type']=='table') echo 'selected'; ?>>Table</option>
        </select><br>
        Owner: 
        <select name="user_id" required>
            <?php while($owner = $owners->fetch_assoc()){ ?>
                <option value="<?php echo $owner['id']; ?>" 
                    <?php if(isset($edit_business) && $edit_business['user_id']==$owner['id']) echo 'selected'; ?>>
                    <?php echo $owner['name']; ?>
                </option>
            <?php } ?>
        </select><br>
        Description:<br>
        <textarea name="description" rows="4" cols="50"><?php echo $edit_business['description'] ?? ''; ?></textarea><br><br>
        <button type="submit" name="<?php echo $edit_business ? 'edit_business' : 'add_business'; ?>">
            <?php echo $edit_business ? 'Update Business' : 'Add Business'; ?>
        </button>
        <?php if($edit_business){ ?>
            <a href="admin_businesses.php">Cancel</a>
        <?php } ?>
    </form>
    <hr>

    <!-- Businesses Table -->
    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th><th>Name</th><th>Owner</th><th>Type</th><th>Description</th><th>Action</th>
        </tr>
        <?php while($row=$result->fetch_assoc()){ ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['owner_name']; ?></td>
            <td><?php echo ucfirst($row['type']); ?></td>
            <td><?php echo substr($row['description'],0,50); ?>...</td>
            <td>
                <a href="?edit=<?php echo $row['id']; ?>">Edit</a> | 
                <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete this business?');">Delete</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
