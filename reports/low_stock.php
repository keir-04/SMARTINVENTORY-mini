<?php
include("../config/db.php");
include("../includes/header.php");

$result = $conn->query("SELECT product_name, stock FROM products WHERE stock < 10");
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
            <th>Product</th>
            <th>Stock</th>
        </tr>
    </thead>
    <tbody>

    <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['product_name']) ?></td>
            <td><span class="badge bg-danger"><?= htmlspecialchars($row['stock']) ?></span></td>
        </tr>
    <?php endwhile; ?>

    </tbody>
</table>

<?php endif; ?>

<?php include("../includes/footer.php"); ?>