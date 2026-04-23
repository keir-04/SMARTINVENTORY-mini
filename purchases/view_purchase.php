<?php
include("../config/db.php");
include("../includes/header.php");

// Handle delete
if (isset($_POST['delete_purchase'])) {
    $purchase_id = $_POST['purchase_id'];
    
    $conn->begin_transaction();
    
    try {
        // Get purchase items to update stock
        $items_result = $conn->query("SELECT product_id, quantity FROM purchase_items WHERE purchase_id = $purchase_id");
        
        // Update product stock (subtract back)
        while ($item = $items_result->fetch_assoc()) {
            $update_stmt = $conn->prepare("UPDATE products SET stock = stock - ? WHERE product_id = ?");
            $update_stmt->bind_param("ii", $item['quantity'], $item['product_id']);
            $update_stmt->execute();
        }
        
        // Delete purchase items
        $conn->query("DELETE FROM purchase_items WHERE purchase_id = $purchase_id");
        
        // Delete purchase
        $delete_stmt = $conn->prepare("DELETE FROM purchases WHERE purchase_id = ?");
        $delete_stmt->bind_param("i", $purchase_id);
        $delete_stmt->execute();
        
        $conn->commit();
        echo "<div class='alert alert-success'>Purchase deleted successfully!</div>";
        header("Location: view_purchase.php");
        exit();
        
    } catch (Exception $e) {
        $conn->rollback();
        echo "<div class='alert alert-danger'>Error deleting purchase: " . $e->getMessage() . "</div>";
    }
}

$result = $conn->query("
SELECT 
    p.purchase_id, 
    s.supplier_name, 
    p.purchase_date, 
    p.total_amount,
    GROUP_CONCAT(CONCAT(pr.product_name, ' (Qty: ', pi.quantity, ')') SEPARATOR ', ') as products
FROM purchases p
JOIN suppliers s ON p.supplier_id = s.supplier_id
LEFT JOIN purchase_items pi ON p.purchase_id = pi.purchase_id
LEFT JOIN products pr ON pi.product_id = pr.product_id
GROUP BY p.purchase_id, s.supplier_name, p.purchase_date, p.total_amount
ORDER BY p.purchase_date DESC
");
?>

<h4 class="mb-3"><i class="fas fa-shopping-cart me-2"></i>Purchase List</h4>

<a href="../index.php" class="btn btn-secondary mb-3">
    <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
</a>

<?php if ($result->num_rows == 0): ?>
    <div class="alert alert-warning">No purchases found. Add purchases first.</div>
<?php else: ?>

<table class="table table-hover table-striped shadow">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Supplier</th>
            <th>Date</th>
            <th>Products</th>
            <th>Total Amount</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>

    <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['purchase_id']) ?></td>
            <td><?= htmlspecialchars($row['supplier_name']) ?></td>
            <td><?= htmlspecialchars($row['purchase_date']) ?></td>
            <td>
                <?php if(!empty($row['products'])): ?>
                    <small class="text-muted"><?= htmlspecialchars($row['products']) ?></small>
                <?php else: ?>
                    <small class="text-muted">No products</small>
                <?php endif; ?>
            </td>
            <td>₹ <?= htmlspecialchars($row['total_amount']) ?></td>
            <td>
                <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this purchase? This will also reduce product stock.');">
                    <input type="hidden" name="purchase_id" value="<?= $row['purchase_id'] ?>">
                    <button type="submit" name="delete_purchase" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </form>
            </td>
        </tr>
    <?php endwhile; ?>

    </tbody>
</table>

<?php endif; ?>

<?php include("../includes/footer.php"); ?>