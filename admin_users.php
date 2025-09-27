<?php
session_start();
include "db.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

// Add User
if(isset($_POST['add_user'])){
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = md5($_POST['password']);
    $role = $_POST['role'];
    $contact = $_POST['contact'];

    $conn->query("INSERT INTO users (name,email,password,role,contact) 
                  VALUES ('$name','$email','$password','$role','$contact')");
    header("Location: admin_users.php");
    exit;
}

// Edit User
if(isset($_POST['edit_user'])){
    $id = intval($_POST['id']);
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $role = $_POST['role'];
    $contact = $_POST['contact'];

    $conn->query("UPDATE users SET name='$name', email='$email', role='$role', contact='$contact' WHERE id=$id");
    header("Location: admin_users.php");
    exit;
}

// Delete User
if(isset($_GET['delete'])){
    $uid = intval($_GET['delete']);
    $conn->query("DELETE FROM users WHERE id=$uid");
    header("Location: admin_users.php");
    exit;
}

// Fetch all users
$result = $conn->query("SELECT * FROM users ORDER BY id DESC");

// For edit form
$edit_user = null;
if(isset($_GET['edit'])){
    $id = intval($_GET['edit']);
    $edit_user = $conn->query("SELECT * FROM users WHERE id=$id")->fetch_assoc();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
</head>
<body>
    <h1>Manage Users</h1>
    <a href="admin_dashboard.php">⬅ Back to Dashboard</a> | 
    <a href="admin_logout.php">Logout</a>
    <hr>

    <!-- Add / Edit User Form -->
    <h2><?php echo $edit_user ? "Edit User" : "Add User"; ?></h2>
    <form method="POST">
        <?php if($edit_user){ ?>
            <input type="hidden" name="id" value="<?php echo $edit_user['id']; ?>">
        <?php } ?>
        Name: <input type="text" name="name" required value="<?php echo $edit_user['name'] ?? ''; ?>"><br>
        Email: <input type="email" name="email" required value="<?php echo $edit_user['email'] ?? ''; ?>"><br>
        <?php if(!$edit_user){ ?>
            Password: <input type="password" name="password" required><br>
        <?php } ?>
        Contact: <input type="text" name="contact" value="<?php echo $edit_user['contact'] ?? ''; ?>"><br>
        Role: 
        <select name="role">
            <option value="customer" <?php if(isset($edit_user)&&$edit_user['role']=='customer') echo 'selected'; ?>>Customer</option>
            <option value="owner" <?php if(isset($edit_user)&&$edit_user['role']=='owner') echo 'selected'; ?>>Owner</option>
            <option value="admin" <?php if(isset($edit_user)&&$edit_user['role']=='admin') echo 'selected'; ?>>Admin</option>
        </select><br><br>
        <button type="submit" name="<?php echo $edit_user ? 'edit_user' : 'add_user'; ?>">
            <?php echo $edit_user ? 'Update User' : 'Add User'; ?>
        </button>
        <?php if($edit_user){ ?>
            <a href="admin_users.php">Cancel</a>
        <?php } ?>
    </form>
    <hr>

    <!-- Users Table -->
    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Contact</th><th>Action</th>
        </tr>
        <?php while($row=$result->fetch_assoc()){ ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo ucfirst($row['role']); ?></td>
            <td><?php echo $row['contact']; ?></td>
            <td>
                <?php if($row['role']!='admin'){ ?>
                    <a href="?edit=<?php echo $row['id']; ?>">Edit</a> | 
                    <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete this user?');">Delete</a>
                <?php } else { echo "Super Admin"; } ?>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
