<?php
include("../config/db.php");

// Handle delete
if (isset($_POST['delete_supplier'])) {
    $supplier_id = $_POST['supplier_id'];
    
    // Check if supplier has purchases
    $check_stmt = $conn->prepare("SELECT COUNT(*) as count FROM purchases WHERE supplier_id = ?");
    $check_stmt->bind_param("i", $supplier_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    $count = $check_result->fetch_assoc()['count'];

    if ($count > 0) {
        echo "<div class='alert alert-warning'>Cannot delete supplier: They have associated purchases. Remove purchases first.</div>";
    } else {
        $stmt = $conn->prepare("DELETE FROM suppliers WHERE supplier_id = ?");
        $stmt->bind_param("i", $supplier_id);
        if ($stmt->execute()) {
            if ($conn->query("SELECT COUNT(*) as count FROM suppliers")->fetch_assoc()['count'] == 0) {
                $conn->query("ALTER TABLE suppliers AUTO_INCREMENT = 1");
            }
            echo "<div class='alert alert-success'>Supplier deleted successfully!</div>";
            header("Location: view_suppliers.php");
            exit();
        } else {
            echo "<div class='alert alert-danger'>Error deleting supplier: " . $conn->error . "</div>";
        }
    }
}

include("../includes/header.php");

$result = $conn->query("
SELECT 
    s.*,
    GROUP_CONCAT(DISTINCT p.product_name SEPARATOR ', ') as products
FROM suppliers s
LEFT JOIN purchases pu ON s.supplier_id = pu.supplier_id
LEFT JOIN purchase_items pi ON pu.purchase_id = pi.purchase_id
LEFT JOIN products p ON pi.product_id = p.product_id
GROUP BY s.supplier_id, s.supplier_name, s.phone, s.email
ORDER BY s.supplier_name
");
?>

<h4 class="mb-3"><i class="fas fa-users me-2"></i>Supplier List</h4>

<a href="../index.php" class="btn btn-secondary mb-3">
    <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
</a>

<?php if ($result->num_rows == 0): ?>
    <div class="alert alert-warning">No suppliers found. Add suppliers first.</div>
<?php else: ?>

<table class="table table-hover table-striped shadow">
    <thead class="table-dark">
        <tr>
            <th>Name</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Products</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>

    <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['supplier_name']) ?></td>
            <td><?= htmlspecialchars($row['phone']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td>
                <?php if(!empty($row['products'])): ?>
                    <small class="text-muted"><?= htmlspecialchars($row['products']) ?></small>
                <?php else: ?>
                    <small class="text-muted">No products</small>
                <?php endif; ?>
            </td>
            <td>
                <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this supplier?');">
                    <input type="hidden" name="supplier_id" value="<?= $row['supplier_id'] ?>">
                    <button type="submit" name="delete_supplier" class="btn btn-danger btn-sm">
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