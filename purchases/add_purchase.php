<?php
session_start();
include("../config/db.php");

function formatProductId($id) {
    return 'P' . str_pad($id, 3, '0', STR_PAD_LEFT);
}

$message = '';

if (!isset($_SESSION['purchase_form_token'])) {
    $_SESSION['purchase_form_token'] = bin2hex(random_bytes(16));
}

if (isset($_POST['product_id'])) {
    $token_valid = isset($_POST['form_token']) && isset($_SESSION['purchase_form_token']) && $_POST['form_token'] === $_SESSION['purchase_form_token'];
    
    if (!$token_valid) {
        $message = "<div class='alert alert-warning'>Session expired or invalid form submission. Please refresh and try again.</div>";
    } else {
        unset($_SESSION['purchase_form_token']);

        $supplier = $_POST['supplier_id'];
        $product = $_POST['product_id'];
        $quantity = $_POST['quantity'];
        $price = $_POST['price'];

        $conn->begin_transaction();

        try {
            error_log("Starting purchase transaction for product $product, quantity $quantity");
            
            $stmt = $conn->prepare("INSERT INTO purchases(supplier_id, purchase_date, total_amount) VALUES (?, CURDATE(), ?)");
            $total = $quantity * $price;
            $stmt->bind_param("id", $supplier, $total);
            if (!$stmt->execute()) {
                throw new Exception("Failed to insert purchase: " . $stmt->error);
            }

            $purchase_id = $conn->insert_id;
            error_log("Purchase inserted with ID: $purchase_id");

            $stmt2 = $conn->prepare("INSERT INTO purchase_items(purchase_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            $stmt2->bind_param("iiid", $purchase_id, $product, $quantity, $price);
            if (!$stmt2->execute()) {
                throw new Exception("Failed to insert purchase item: " . $stmt2->error);
            }
            error_log("Purchase item inserted");

            // Update product stock
            $stmt3 = $conn->prepare("UPDATE products SET stock = stock + ? WHERE product_id = ?");
            $stmt3->bind_param("ii", $quantity, $product);
            if (!$stmt3->execute()) {
                throw new Exception("Failed to update product stock: " . $stmt3->error);
            }
            error_log("Product stock updated");

            $conn->commit();
            error_log("Transaction committed");

            header("Location: add_purchase.php?success=1");
            exit();
        } catch (Exception $e) {
            $conn->rollback();
            error_log("Transaction failed: " . $e->getMessage());
            $message = "<div class='alert alert-danger'>Error adding purchase: " . $e->getMessage() . "</div>";
        }
    }
}

if (isset($_GET['success']) && $_GET['success'] == '1') {
    $message = "<div class='alert alert-success'>
        <i class='fas fa-check-circle me-2'></i>Purchase Added Successfully! Stock has been updated.
        <div class='mt-2'>
            <a href='view_purchase.php' class='btn btn-sm btn-outline-success me-2'>View Purchases</a>
            <a href='../products/view_products.php' class='btn btn-sm btn-outline-primary me-2'>Check Stock Levels</a>
            <a href='add_purchase.php' class='btn btn-sm btn-outline-info'>Add Another Purchase</a>
        </div>
    </div>";
}

$products = $conn->query("SELECT * FROM products ORDER BY product_name");
$suppliers = $conn->query("SELECT * FROM suppliers ORDER BY supplier_name");
$products_all = $conn->query("SELECT p.product_id, p.product_name, c.category_name, p.price, p.stock FROM products p LEFT JOIN categories c ON p.category_id = c.category_id ORDER BY p.product_name");

include("../includes/header.php");
?>

<?php echo $message; ?>

<?php if ($products->num_rows == 0): ?>
    <div class="alert alert-warning">No products available. Please add products first before adding purchases.</div>
<?php endif; ?>

<?php if ($suppliers->num_rows == 0): ?>
    <div class="alert alert-warning">No suppliers available. Please add suppliers first.</div>
<?php endif; ?>

<h4 class="mb-3"><i class="fas fa-cart-plus me-2"></i>Add Purchase</h4>

<a href="../index.php" class="btn btn-secondary mb-3">
    <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
</a>

<div class="d-flex gap-2 mb-3">
    <a href="view_purchase.php" class="btn btn-outline-primary">
        <i class="fas fa-shopping-cart me-1"></i> View Purchases
    </a>
    <a href="../products/view_products.php" class="btn btn-outline-info">
        <i class="fas fa-cube me-1"></i> Check Stock Levels
    </a>
    <a href="add_purchase.php" class="btn btn-outline-success">
        <i class="fas fa-plus me-1"></i> Add Another Purchase
    </a>
</div>

<form method="POST" onsubmit="this.querySelector('button[type=submit]').disabled=true; return true;">
    <div class="mb-3">
        <label class="form-label">Supplier</label>
        <select name="supplier_id" class="form-control" required>
            <option value="">Select Supplier</option>
            <?php while($s = $suppliers->fetch_assoc()): ?>
                <option value="<?php echo $s['supplier_id'] ?>"><?php echo htmlspecialchars($s['supplier_name']) ?></option>
            <?php endwhile; ?>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Product</label>
        <select name="product_id" class="form-control" required>
            <option value="">Select Product</option>
            <?php while($p = $products->fetch_assoc()): ?>
                <option value="<?php echo $p['product_id'] ?>"><?php echo htmlspecialchars(formatProductId($p['product_id']) . ' - ' . $p['product_name']) ?></option>
            <?php endwhile; ?>
        </select>
    </div>

    <input type="hidden" name="form_token" value="<?php echo htmlspecialchars($_SESSION['purchase_form_token']) ?>">

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
