<?php
include("../config/db.php");
include("../includes/header.php");

$result = $conn->query("SELECT * FROM suppliers");
?>

<table class="table table-bordered">
<tr>
    <th>Name</th>
    <th>Phone</th>
    <th>Email</th>
</tr>

<?php while($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= $row['supplier_name'] ?></td>
    <td><?= $row['phone'] ?></td>
    <td><?= $row['email'] ?></td>
</tr>
<?php endwhile; ?>

</table>

<?php include("../includes/footer.php"); ?>