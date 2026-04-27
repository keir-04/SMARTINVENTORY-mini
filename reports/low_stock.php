<?php
include("../config/db.php");
include("../includes/header.php");

function formatProductId($id) {
    return 'P' . str_pad($id, 3, '0', STR_PAD_LEFT);
}

$result = $conn->query("SELECT product_id, product_name, stock FROM products WHERE stock < 10");
?>

<h4 class="mb-3"><i class="fas fa-exclamation-triangle me-2"></i>Low Stock Products (Less than 10)</h4>

<a href="../index.php" class="btn btn-secondary mb-3">
    <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
</a>

<?php if ($result->num_rows == 0): ?>
    <div class="alert alert-success">All products are sufficiently stocked!</div>
<?php else: ?>

<table class="table table-hover table-striped shadow">
    <thead class="table-danger">
        <tr>
            <th>ID</th>
            <th>Product</th>
            <th>Stock</th>
        </tr>
    </thead>
    <tbody>

    <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars(formatProductId($row['product_id'])) ?></td>
            <td><?php echo htmlspecialchars($row['product_name']) ?></td>
            <td><span class="badge bg-danger"><?php echo htmlspecialchars($row['stock']) ?></span></td>
        </tr>
    <?php endwhile; ?>

    </tbody>
</table>

<?php endif; ?>

<?php include("../includes/footer.php"); ?>
