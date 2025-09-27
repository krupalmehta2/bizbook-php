<?php
session_start();
include "db.php";

if (!isset($_SESSION['owner_id'])) {
    header("Location: ownerlogin.php");
    exit;
}

$owner_id = $_SESSION['owner_id'];

// Fetch order-type businesses of owner
$biz_result = $conn->query("SELECT * FROM businesses WHERE user_id=$owner_id AND type='order' ORDER BY name ASC");

// Add product
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['add'])) {
    $business_id = $_POST['business_id'];
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    $conn->query("INSERT INTO products (business_id, name, description, price, stock) 
                  VALUES ('$business_id','$name','$desc','$price','$stock')");
    header("Location: ownerproducts.php");
    exit;
}

// Delete product
if (isset($_GET['delete'])) {
    $pid = intval($_GET['delete']);
    // Ensure product belongs to owner
    $conn->query("DELETE p FROM products p 
                  JOIN businesses b ON p.business_id=b.id 
                  WHERE p.id=$pid AND b.user_id=$owner_id");
    header("Location: ownerproducts.php");
    exit;
}

// Fetch owner products
$result = $conn->query("SELECT p.*, b.name AS business_name 
                        FROM products p 
                        JOIN businesses b ON p.business_id=b.id 
                        WHERE b.user_id=$owner_id ORDER BY p.id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Owner Products</title>
</head>
<body>
    <h1>Manage Products (Order-Based Businesses)</h1>
    <a href="ownerdashboard.php">⬅ Back to Dashboard</a> | 
    <a href="ownerlogout.php">Logout</a>
    <hr>

    <!-- Add Product Form -->
    <h3>Add New Product</h3>
    <form method="POST">
        <select name="business_id" required>
            <option value="">Select Business</option>
            <?php while($biz = $biz_result->fetch_assoc()) { ?>
                <option value="<?php echo $biz['id']; ?>"><?php echo $biz['name']; ?></option>
            <?php } ?>
        </select><br><br>
        <input type="text" name="name" placeholder="Product Name" required><br><br>
        <textarea name="description" placeholder="Description"></textarea><br><br>
        <input type="number" step="0.01" name="price" placeholder="Price" required><br><br>
        <input type="number" name="stock" placeholder="Stock" required><br><br>
        <button type="submit" name="add">Add Product</button>
    </form>
    <hr>

    <!-- List Products -->
    <h3>Your Products</h3>
    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Business</th>
            <th>Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Action</th>
        </tr>
        <?php while($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['business_name']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo substr($row['description'],0,50); ?>...</td>
            <td><?php echo $row['price']; ?></td>
            <td><?php echo $row['stock']; ?></td>
            <td>
                <a href="ownerproducts.php?delete=<?php echo $row['id']; ?>" 
                   onclick="return confirm('Delete this product?');">Delete</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
