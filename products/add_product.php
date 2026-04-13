<?php
include("../config/db.php");
include("../includes/header.php");

if(isset($_POST['submit'])) {

    $name = $_POST['product_name'];
    $category = $_POST['category_name'];
    $price = $_POST['price'];

    // Insert category if not exists
    $stmt = $conn->prepare("INSERT IGNORE INTO categories(category_name) VALUES (?)");
    $stmt->bind_param("s", $category);
    $stmt->execute();

    $cat_id = $conn->insert_id;
    if($cat_id == 0) {
        $result = $conn->query("SELECT category_id FROM categories WHERE category_name='$category'");
        $row = $result->fetch_assoc();
        $cat_id = $row['category_id'];
    }

    $stmt = $conn->prepare("INSERT INTO products(product_name, category_id, price) VALUES (?, ?, ?)");
    $stmt->bind_param("sid", $name, $cat_id, $price);
    $stmt->execute();

    echo "<div class='alert alert-success'>Product Added Successfully</div>";
}
?>

<form method="POST">
    <div class="mb-3">
        <label>Product Name</label>
        <input type="text" name="product_name" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Category Name</label>
        <input type="text" name="category_name" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Price</label>
        <input type="number" step="0.01" name="price" class="form-control" required>
    </div>

    <button type="submit" name="submit" class="btn btn-primary">Add Product</button>
</form>

<?php include("../includes/footer.php"); ?>