<?php
// 🔴 Show all errors (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("../config/db.php");
include("../includes/header.php");

// Handle delete
if (isset($_POST['delete_product'])) {
    $product_id = $_POST['product_id'];
    
    // Check if product has purchases
    $check_stmt = $conn->prepare("SELECT COUNT(*) as count FROM purchase_items WHERE product_id = ?");
    $check_stmt->bind_param("i", $product_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    $count = $check_result->fetch_assoc()['count'];
    
    if ($count > 0) {
        echo "<div class='alert alert-warning'>Cannot delete product: It has associated purchases. Remove purchases first.</div>";
    } else {
        $stmt = $conn->prepare("DELETE FROM products WHERE product_id = ?");
        $stmt->bind_param("i", $product_id);
        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Product deleted successfully!</div>";
            // Redirect to prevent re-submission
            header("Location: view_products.php");
            exit();
        } else {
            echo "<div class='alert alert-danger'>Error deleting product: " . $conn->error . "</div>";
        }
    }
}

// Handle search
$search = isset($_GET['search']) ? $_GET['search'] : '';

// ✅ SQL Query (LEFT JOIN prevents missing category issue)
$sql = "
SELECT 
    p.product_id,
    p.product_name,
    c.category_name,
    p.price,
    p.stock,
    GROUP_CONCAT(DISTINCT s.supplier_name SEPARATOR ', ') as suppliers
FROM products p
LEFT JOIN categories c 
    ON p.category_id = c.category_id
LEFT JOIN purchase_items pi ON p.product_id = pi.product_id
LEFT JOIN purchases pr ON pi.purchase_id = pr.purchase_id
LEFT JOIN suppliers s ON pr.supplier_id = s.supplier_id
GROUP BY p.product_id, p.product_name, c.category_name, p.price, p.stock
";

if (!empty($search)) {
    $sql .= " HAVING p.product_name LIKE '%" . $conn->real_escape_string($search) . "%'";
}

$result = $conn->query($sql);

// ❌ If query fails → show exact error
if (!$result) {
    die("<div class='alert alert-danger'>Query Failed: " . $conn->error . "</div>");
}
?>

<h4 class="mb-3"><i class="fas fa-list me-2"></i>Products & Suppliers Overview</h4>

<a href="../index.php" class="btn btn-secondary mb-3">
    <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
</a>

<!-- Search Form -->
<form method="GET" class="mb-3">
    <div class="input-group">
        <input type="text" name="search" class="form-control" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>">
        <button class="btn btn-outline-primary" type="submit"><i class="fas fa-search"></i></button>
        <?php if (!empty($search)): ?>
            <a href="view_products.php" class="btn btn-outline-secondary">Clear</a>
        <?php endif; ?>
    </div>
</form>

<?php if ($result->num_rows == 0): ?>
    <div class="alert alert-warning">No products found. <?php if (!empty($search)) echo 'Try a different search.'; else echo 'Add products first.'; ?></div>
<?php else: ?>

<table class="table table-hover table-striped shadow">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Category</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Suppliers</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>

    <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['product_id']) ?></td>
            <td><?= htmlspecialchars($row['product_name']) ?></td>
            <td><?= htmlspecialchars($row['category_name'] ?? 'N/A') ?></td>
            <td>₹ <?= htmlspecialchars($row['price']) ?></td>
            <td>
                <?php if($row['stock'] < 5): ?>
                    <span class="badge bg-danger"><?= $row['stock'] ?></span>
                <?php else: ?>
                    <span class="badge bg-success"><?= $row['stock'] ?></span>
                <?php endif; ?>
            </td>
            <td>
                <?php if(!empty($row['suppliers'])): ?>
                    <small class="text-muted"><?= htmlspecialchars($row['suppliers']) ?></small>
                <?php else: ?>
                    <small class="text-muted">No suppliers yet</small>
                <?php endif; ?>
            </td>
            <td>
                <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this product?');">
                    <input type="hidden" name="product_id" value="<?= $row['product_id'] ?>">
                    <button type="submit" name="delete_product" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </form>
            </td>
        </tr>
    <?php endwhile; ?>

    </tbody>
</table>

<?php endif; ?>

<hr class="my-5">

<h4 class="mb-3"><i class="fas fa-users me-2"></i>Suppliers List</h4>

<?php
$suppliers_result = $conn->query("SELECT * FROM suppliers ORDER BY supplier_name");
if ($suppliers_result->num_rows == 0): ?>
    <div class="alert alert-info">No suppliers found. Add suppliers first.</div>
<?php else: ?>

<div class="row">
    <?php while($supplier = $suppliers_result->fetch_assoc()): ?>
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="fas fa-truck me-2 text-primary"></i>
                        <?= htmlspecialchars($supplier['supplier_name']) ?>
                    </h6>
                    <p class="card-text mb-1">
                        <i class="fas fa-phone me-2"></i>
                        <?= htmlspecialchars($supplier['phone'] ?: 'N/A') ?>
                    </p>
                    <p class="card-text">
                        <i class="fas fa-envelope me-2"></i>
                        <?= htmlspecialchars($supplier['email'] ?: 'N/A') ?>
                    </p>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<?php endif; ?>

<?php include("../includes/footer.php"); ?>