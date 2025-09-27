<?php
session_start();
include "db.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

// Fetch all businesses for dropdown
$businesses = $conn->query("SELECT id,name FROM businesses WHERE type='order' ORDER BY name ASC");

// Add Product
if(isset($_POST['add_product'])){
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $business_id = intval($_POST['business_id']);

    $conn->query("INSERT INTO products (business_id,name,description,price,stock) 
                  VALUES ($business_id,'$name','$description',$price,$stock)");
    header("Location: admin_products.php");
    exit;
}

// Edit Product
if(isset($_POST['edit_product'])){
    $id = intval($_POST['id']);
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $business_id = intval($_POST['business_id']);

    $conn->query("UPDATE products SET business_id=$business_id,name='$name',description='$description',
                  price=$price, stock=$stock WHERE id=$id");
    header("Location: admin_products.php");
    exit;
}

// Delete Product
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM products WHERE id=$id");
    header("Location: admin_products.php");
    exit;
}

// Fetch all products with business name
$result = $conn->query("SELECT p.*, b.name AS business_name FROM products p 
                        JOIN businesses b ON p.business_id=b.id ORDER BY p.id DESC");

// For edit form
$edit_product = null;
if(isset($_GET['edit'])){
    $id = intval($_GET['edit']);
    $edit_product = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Products</title>
</head>
<body>
    <h1>Manage Products</h1>
    <a href="admin_dashboard.php">⬅ Back to Dashboard</a> | 
    <a href="admin_logout.php">Logout</a>
    <hr>

    <!-- Add/Edit Product -->
    <h2><?php echo $edit_product ? "Edit Product" : "Add Product"; ?></h2>
    <form method="POST">
        <?php if($edit_product){ ?>
            <input type="hidden" name="id" value="<?php echo $edit_product['id']; ?>">
        <?php } ?>
        Name: <input type="text" name="name" required value="<?php echo $edit_product['name'] ?? ''; ?>"><br>
        Description:<br>
        <textarea name="description" rows="4" cols="50"><?php echo $edit_product['description'] ?? ''; ?></textarea><br>
        Price: <input type="number" step="0.01" name="price" required value="<?php echo $edit_product['price'] ?? ''; ?>"><br>
        Stock: <input type="number" name="stock" required value="<?php echo $edit_product['stock'] ?? ''; ?>"><br>
        Business: 
        <select name="business_id" required>
            <?php while($b = $businesses->fetch_assoc()){ ?>
                <option value="<?php echo $b['id']; ?>" 
                    <?php if(isset($edit_product) && $edit_product['business_id']==$b['id']) echo 'selected'; ?>>
                    <?php echo $b['name']; ?>
                </option>
            <?php } ?>
        </select><br><br>
        <button type="submit" name="<?php echo $edit_product ? 'edit_product' : 'add_product'; ?>">
            <?php echo $edit_product ? 'Update Product' : 'Add Product'; ?>
        </button>
        <?php if($edit_product){ ?><a href="admin_products.php">Cancel</a><?php } ?>
    </form>
    <hr>

    <!-- Products Table -->
    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th><th>Name</th><th>Description</th><th>Price</th><th>Stock</th><th>Business</th><th>Action</th>
        </tr>
        <?php while($row=$result->fetch_assoc()){ ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo substr($row['description'],0,50); ?>...</td>
            <td><?php echo $row['price']; ?></td>
            <td><?php echo $row['stock']; ?></td>
            <td><?php echo $row['business_name']; ?></td>
            <td>
                <a href="?edit=<?php echo $row['id']; ?>">Edit</a> | 
                <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete this product?');">Delete</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
