<?php
include("../config/db.php");
include("../includes/header.php");

$result = $conn->query("SELECT product_name, stock FROM products WHERE stock < 10");
?>

<h4>Low Stock Products (Less than 10)</h4>

<table class="table table-danger">
<tr>
    <th>Product</th>
    <th>Stock</th>
</tr>

<?php while($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= $row['product_name'] ?></td>
    <td><?= $row['stock'] ?></td>
</tr>
<?php endwhile; ?>

</table>

<?php include("../includes/footer.php"); ?>