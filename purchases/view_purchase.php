<?php
include("../config/db.php");
include("../includes/header.php");

$result = $conn->query("
SELECT p.purchase_id, s.supplier_name, p.purchase_date, p.total_amount
FROM purchases p
JOIN suppliers s ON p.supplier_id = s.supplier_id
");
?>

<table class="table table-bordered">
<tr>
    <th>ID</th>
    <th>Supplier</th>
    <th>Date</th>
    <th>Total</th>
</tr>

<?php while($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= $row['purchase_id'] ?></td>
    <td><?= $row['supplier_name'] ?></td>
    <td><?= $row['purchase_date'] ?></td>
    <td><?= $row['total_amount'] ?></td>
</tr>
<?php endwhile; ?>

</table>

<?php include("../includes/footer.php"); ?>