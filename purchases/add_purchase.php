<?php
include("../config/db.php");
include("../includes/header.php");

$products = $conn->query("SELECT * FROM products");
$suppliers = $conn->query("SELECT * FROM suppliers");

if(isset($_POST['submit'])) {

    $supplier = $_POST['supplier_id'];
    $product = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];

    $conn->begin_transaction();

    $stmt = $conn->prepare("INSERT INTO purchases(supplier_id, purchase_date, total_amount) VALUES (?, CURDATE(), ?)");
    $total = $quantity * $price;
    $stmt->bind_param("id", $supplier, $total);
    $stmt->execute();

    $purchase_id = $conn->insert_id;

    $stmt2 = $conn->prepare("INSERT INTO purchase_items(purchase_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    $stmt2->bind_param("iiid", $purchase_id, $product, $quantity, $price);
    $stmt2->execute();

    $conn->commit();

    echo "<div class='alert alert-success'>Purchase Added</div>";
}
?>

<form method="POST">
    <select name="supplier_id" class="form-control mb-3" required>
        <option value="">Select Supplier</option>
        <?php while($s = $suppliers->fetch_assoc()): ?>
            <option value="<?= $s['supplier_id'] ?>"><?= $s['supplier_name'] ?></option>
        <?php endwhile; ?>
    </select>

    <select name="product_id" class="form-control mb-3" required>
        <option value="">Select Product</option>
        <?php while($p = $products->fetch_assoc()): ?>
            <option value="<?= $p['product_id'] ?>"><?= $p['product_name'] ?></option>
        <?php endwhile; ?>
    </select>

    <input type="number" name="quantity" class="form-control mb-3" placeholder="Quantity" required>
    <input type="number" step="0.01" name="price" class="form-control mb-3" placeholder="Price" required>

    <button type="submit" name="submit" class="btn btn-dark">Add Purchase</button>
</form>

<?php include("../includes/footer.php"); ?>