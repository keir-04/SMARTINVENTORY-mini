<?php
include("../config/db.php");
include("../includes/header.php");

if(isset($_POST['submit'])) {

    $name = $_POST['supplier_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];

    $stmt = $conn->prepare("INSERT INTO suppliers(supplier_name, phone, email) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $phone, $email);
    $stmt->execute();

    echo "<div class='alert alert-success'>Supplier Added</div>";
}
?>

<form method="POST">
    <input type="text" name="supplier_name" class="form-control mb-3" placeholder="Supplier Name" required>
    <input type="text" name="phone" class="form-control mb-3" placeholder="Phone">
    <input type="email" name="email" class="form-control mb-3" placeholder="Email">
    <button type="submit" name="submit" class="btn btn-success">Add Supplier</button>
</form>

<?php include("../includes/footer.php"); ?>