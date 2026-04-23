<?php
include("../config/db.php");
include("../includes/header.php");

$products = $conn->query("SELECT p.product_id, p.product_name, c.category_name, p.price, p.stock FROM products p LEFT JOIN categories c ON p.category_id = c.category_id ORDER BY p.product_name");

if(isset($_POST['submit'])) {

    $name = $_POST['supplier_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];

    $stmt = $conn->prepare("INSERT INTO suppliers(supplier_name, phone, email) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $phone, $email);
    $stmt->execute();

    echo "<div class='alert alert-success'>Supplier Added Successfully</div>";
}
?>

<h4 class="mb-3"><i class="fas fa-user-plus me-2"></i>Add Supplier</h4>

<a href="../index.php" class="btn btn-secondary mb-3">
    <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
</a>

<form method="POST">
    <div class="mb-3">
        <label class="form-label">Supplier Name</label>
        <input type="text" name="supplier_name" class="form-control" placeholder="Enter supplier name" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Phone</label>
        <input type="text" name="phone" class="form-control" placeholder="Enter phone number">
    </div>
    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" placeholder="Enter email address">
    </div>
    <button type="submit" name="submit" class="btn btn-success">Add Supplier</button>
</form>

<hr class="my-5">

<h4 class="mb-3"><i class="fas fa-cube me-2"></i>Products List</h4>

<?php if ($products->num_rows == 0): ?>
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

    <?php while($product = $products->fetch_assoc()): ?>
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