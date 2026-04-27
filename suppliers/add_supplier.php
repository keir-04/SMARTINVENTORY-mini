<?php
session_start();
include("../config/db.php");

$message = '';

if (!isset($_SESSION['supplier_form_token'])) {
    $_SESSION['supplier_form_token'] = bin2hex(random_bytes(16));
}

if(isset($_POST['supplier_name'])) {
    $token_valid = isset($_POST['form_token']) && isset($_SESSION['supplier_form_token']) && $_POST['form_token'] === $_SESSION['supplier_form_token'];
    
    if (!$token_valid) {
        $message = "<div class='alert alert-warning'>Session expired or invalid form submission. Please refresh and try again.</div>";
    } else {
        unset($_SESSION['supplier_form_token']);

        $name = $_POST['supplier_name'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];

        try {
            $stmt = $conn->prepare("INSERT INTO suppliers(supplier_name, phone, email) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $phone, $email);
            if (!$stmt->execute()) {
                throw new Exception("Failed to insert supplier: " . $stmt->error);
            }

            header("Location: add_supplier.php?success=1");
            exit();
        } catch (Exception $e) {
            $message = "<div class='alert alert-danger'>Error adding supplier: " . $e->getMessage() . "</div>";
        }
    }
}

if (isset($_GET['success']) && $_GET['success'] == '1') {
    $message = "<div class='alert alert-success'>
        <i class='fas fa-check-circle me-2'></i>Supplier Added Successfully!
        <div class='mt-2'>
            <a href='view_suppliers.php' class='btn btn-sm btn-outline-success me-2'>View Suppliers</a>
            <a href='add_supplier.php' class='btn btn-sm btn-outline-primary'>Add Another Supplier</a>
        </div>
    </div>";
}

$products = $conn->query("SELECT p.product_id, p.product_name, c.category_name, p.price, p.stock FROM products p LEFT JOIN categories c ON p.category_id = c.category_id ORDER BY p.product_name");

include("../includes/header.php");
?>

<?php echo $message; ?>


<h4 class="mb-3"><i class="fas fa-user-plus me-2"></i>Add Supplier</h4>

<a href="../index.php" class="btn btn-secondary mb-3">
    <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
</a>

<div class="d-flex gap-2 mb-3">
    <a href="view_suppliers.php" class="btn btn-outline-primary">
        <i class="fas fa-users me-1"></i> View Suppliers
    </a>
    <a href="add_supplier.php" class="btn btn-outline-success">
        <i class="fas fa-plus me-1"></i> Add Another Supplier
    </a>
</div>

<form method="POST" onsubmit="this.querySelector('button[type=submit]').disabled=true; return true;">
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
    <input type="hidden" name="form_token" value="<?php echo htmlspecialchars($_SESSION['supplier_form_token']) ?>">
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
            <td><?php echo htmlspecialchars($product['product_id']) ?></td>
            <td><?php echo htmlspecialchars($product['product_name']) ?></td>
            <td><?php echo htmlspecialchars($product['category_name'] ?? 'N/A') ?></td>
            <td>₹ <?php echo htmlspecialchars($product['price']) ?></td>
            <td>
                <?php if($product['stock'] < 10): ?>
                    <span class="badge bg-danger"><?php echo $product['stock'] ?></span>
                <?php else: ?>
                    <span class="badge bg-success"><?php echo $product['stock'] ?></span>
                <?php endif; ?>
            </td>
        </tr>
    <?php endwhile; ?>

    </tbody>
</table>

<?php endif; ?>

<?php include("../includes/footer.php"); ?>
