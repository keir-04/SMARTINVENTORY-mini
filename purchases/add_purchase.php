<?php
include("../config/db.php");
include("../includes/header.php");

$products = $conn->query("SELECT * FROM products ORDER BY product_name");
$suppliers = $conn->query("SELECT * FROM suppliers ORDER BY supplier_name");
$products_all = $conn->query("SELECT p.product_id, p.product_name, c.category_name, p.price, p.stock FROM products p LEFT JOIN categories c ON p.category_id = c.category_id ORDER BY p.product_name");

if(isset($_POST['submit'])) {

    $supplier = $_POST['supplier_id'];
    $product = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];

    $conn->begin_transaction();

    $stmt = $conn->prepare("INSERT INTO purchases(supplier_id, purchase_date, total_amount) VALUES (?, CURDATE(), ?)");
    $total = $quantity * $price;
    $stmt->bind_param("id", $supplier, $total);
    $stmt->execute();

    $purchase_id = $conn->insert_id;

    $stmt2 = $conn->prepare("INSERT INTO purchase_items(purchase_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    $stmt2->bind_param("iiid", $purchase_id, $product, $quantity, $price);
    $stmt2->execute();

    // Update product stock
    $stmt3 = $conn->prepare("UPDATE products SET stock = stock + ? WHERE product_id = ?");
    $stmt3->bind_param("ii", $quantity, $product);
    $stmt3->execute();

    $conn->commit();

    echo "<div class='alert alert-success'>Purchase Added Successfully</div>";
}
?>

<h4 class="mb-3"><i class="fas fa-cart-plus me-2"></i>Add Purchase</h4>

<a href="../index.php" class="btn btn-secondary mb-3">
    <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
</a>

<form method="POST">
    <div class="mb-3">
        <label class="form-label">Supplier</label>
        <select name="supplier_id" class="form-control" required>
            <option value="">Select Supplier</option>
            <?php while($s = $suppliers->fetch_assoc()): ?>
                <option value="<?= $s['supplier_id'] ?>"><?= htmlspecialchars($s['supplier_name']) ?></option>
            <?php endwhile; ?>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Product</label>
        <select name="product_id" class="form-control" required>
            <option value="">Select Product</option>
            <?php while($p = $products->fetch_assoc()): ?>
                <option value="<?= $p['product_id'] ?>"><?= htmlspecialchars($p['product_name']) ?></option>
            <?php endwhile; ?>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Quantity</label>
        <input type="number" name="quantity" class="form-control" placeholder="Enter quantity" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Price per Unit</label>
        <input type="number" step="0.01" name="price" class="form-control" placeholder="Enter price" required>
    </div>
    <button type="submit" name="submit" class="btn btn-dark">Add Purchase</button>
</form>

<hr class="my-5">

<h4 class="mb-3"><i class="fas fa-cube me-2"></i>Available Products</h4>

<?php if ($products_all->num_rows == 0): ?>
    <div class="alert alert-info">No products found. Add products first.</div>
<?php else: ?>

<table class="table table-hover table-striped shadow">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Category</th>
            <th>Price</th>
            <th>Current Stock</th>
        </tr>
    </thead>
    <tbody>

    <?php while($product = $products_all->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($product['product_id']) ?></td>
            <td><?= htmlspecialchars($product['product_name']) ?></td>
            <td><?= htmlspecialchars($product['category_name'] ?? 'N/A') ?></td>
            <td>₹ <?= htmlspecialchars($product['price']) ?></td>
            <td>
                <?php if($product['stock'] < 5): ?>
                    <span class="badge bg-danger"><?= $product['stock'] ?></span>
                <?php else: ?>
                    <span class="badge bg-success"><?= $product['stock'] ?></span>
                <?php endif; ?>
            </td>
        </tr>
    <?php endwhile; ?>

    </tbody>
</table>

<?php endif; ?>

<?php include("../includes/footer.php"); ?>