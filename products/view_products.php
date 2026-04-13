<?php
// 🔴 Show all errors (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("../config/db.php");
include("../includes/header.php");

// ✅ SQL Query (LEFT JOIN prevents missing category issue)
$sql = "
SELECT 
    p.product_id,
    p.product_name,
    c.category_name,
    p.price,
    p.stock
FROM products p
LEFT JOIN categories c 
    ON p.category_id = c.category_id
";

$result = $conn->query($sql);

// ❌ If query fails → show exact error
if (!$result) {
    die("<div class='alert alert-danger'>Query Failed: " . $conn->error . "</div>");
}
?>

<h4 class="mb-3">Product List</h4>

<?php if ($result->num_rows == 0): ?>
    <div class="alert alert-warning">No products found. Add products first.</div>
<?php else: ?>

<table class="table table-hover table-striped shadow">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Category</th>
            <th>Price</th>
            <th>Stock</th>
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
        </tr>
    <?php endwhile; ?>

    </tbody>
</table>

<?php endif; ?>

<?php include("../includes/footer.php"); ?>