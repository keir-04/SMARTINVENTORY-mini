<?php
session_start();
include("../config/db.php");

$message = '';

if (!isset($_SESSION['product_form_token'])) {
    $_SESSION['product_form_token'] = bin2hex(random_bytes(16));
}

if(isset($_POST['product_name'])) { // Changed from 'submit' to check field directly
    // More relaxed token check for debugging, but still present
    $token_valid = isset($_POST['form_token']) && isset($_SESSION['product_form_token']) && $_POST['form_token'] === $_SESSION['product_form_token'];
    
    if (!$token_valid) {
        $message = "<div class='alert alert-warning'>Session expired or invalid form submission. Please refresh and try again.</div>";
    } else {
        unset($_SESSION['product_form_token']);

        $name = trim($_POST['product_name']);
        $category = trim($_POST['category_name']);
        $price = $_POST['price'];

        try {
            // Insert category if not exists
            $stmt = $conn->prepare("INSERT IGNORE INTO categories(category_name) VALUES (?)");
            $stmt->bind_param("s", $category);
            if (!$stmt->execute()) {
                throw new Exception("Failed to insert category: " . $stmt->error);
            }

            $cat_id = $conn->insert_id;
            if($cat_id == 0) {
                $result = $conn->query("SELECT category_id FROM categories WHERE category_name='" . $conn->real_escape_string($category) . "'");
                if (!$result) {
                    throw new Exception("Failed to select category: " . $conn->error);
                }
                $row = $result->fetch_assoc();
                $cat_id = $row['category_id'];
            }

            // Prevent duplicate products
            $check_stmt = $conn->prepare("SELECT product_id FROM products WHERE product_name = ? AND category_id = ?");
            $check_stmt->bind_param("si", $name, $cat_id);
            if (!$check_stmt->execute()) {
                throw new Exception("Failed to check duplicate: " . $check_stmt->error);
            }
            $check_stmt->store_result();

            if ($check_stmt->num_rows > 0) {
                $message = "<div class='alert alert-warning'>This product already exists in the selected category.</div>";
            } else {
                $stmt = $conn->prepare("INSERT INTO products(product_name, category_id, price) VALUES (?, ?, ?)");
                $stmt->bind_param("sid", $name, $cat_id, $price);
                if (!$stmt->execute()) {
                    throw new Exception("Failed to insert product: " . $stmt->error);
                }

                header("Location: add_product.php?success=1");
                exit();
            }
        } catch (Exception $e) {
            $message = "<div class='alert alert-danger'>Error adding product: " . $e->getMessage() . "</div>";
        }
    }
}

if (isset($_GET['success']) && $_GET['success'] == '1') {
    $message = "<div class='alert alert-success'>
        <i class='fas fa-check-circle me-2'></i>Product Added Successfully!
        <div class='mt-2'>
            <a href='view_products.php' class='btn btn-sm btn-outline-success me-2'>View Products</a>
            <a href='add_product.php' class='btn btn-sm btn-outline-primary'>Add Another Product</a>
        </div>
    </div>";
}

include("../includes/header.php");
?>

<?php echo $message; ?>

<h4 class="mb-3"><i class="fas fa-plus-circle me-2"></i>Add Product</h4>

<a href="../index.php" class="btn btn-secondary mb-3">
    <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
</a>

<div class="d-flex gap-2 mb-3">
    <a href="view_products.php" class="btn btn-outline-primary">
        <i class="fas fa-list me-1"></i> View Products
    </a>
    <a href="add_product.php" class="btn btn-outline-success">
        <i class="fas fa-plus me-1"></i> Add Another Product
    </a>
</div>

<form method="POST" onsubmit="this.querySelector('button[type=submit]').disabled=true;">
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

    <input type="hidden" name="form_token" value="<?php echo htmlspecialchars($_SESSION['product_form_token']); ?>">

    <button type="submit" name="submit" class="btn btn-primary">Add Product</button>
</form>

<?php include("../includes/footer.php"); ?>
