<?php 
include("includes/header.php");
include("config/db.php");

// Get counts
$product_count = $conn->query("SELECT COUNT(*) as count FROM products")->fetch_assoc()['count'];
$supplier_count = $conn->query("SELECT COUNT(*) as count FROM suppliers")->fetch_assoc()['count'];
$low_stock_count = $conn->query("SELECT COUNT(*) as count FROM products WHERE stock < 10")->fetch_assoc()['count'];
$total_value = $conn->query("SELECT SUM(price * stock) as total FROM products")->fetch_assoc()['total'];
?>

<h3 class="mb-4"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</h3>

<!-- Stats Row -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title text-primary"><i class="fas fa-cube fa-2x"></i></h5>
                <h3><?php echo $product_count; ?></h3>
                <p class="card-text">Total Products</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title text-success"><i class="fas fa-users fa-2x"></i></h5>
                <h3><?php echo $supplier_count; ?></h3>
                <p class="card-text">Total Suppliers</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title text-warning"><i class="fas fa-rupee-sign fa-2x"></i></h5>
                <h3>₹<?php echo number_format($total_value, 2); ?></h3>
                <p class="card-text">Inventory Value</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card dashboard-card bg-primary text-white">
            <div class="card-body text-center">
                <i class="fas fa-plus-circle fa-2x mb-2"></i>
                <h5>Add Product</h5>
                <a href="products/add_product.php" class="btn btn-light btn-sm mt-2">Go</a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card dashboard-card bg-info text-white">
            <div class="card-body text-center">
                <i class="fas fa-list fa-2x mb-2"></i>
                <h5>View Products</h5>
                <a href="products/view_products.php" class="btn btn-light btn-sm mt-2">Go</a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card dashboard-card bg-success text-white">
            <div class="card-body text-center">
                <i class="fas fa-user-plus fa-2x mb-2"></i>
                <h5>Add Supplier</h5>
                <a href="suppliers/add_supplier.php" class="btn btn-light btn-sm mt-2">Go</a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card dashboard-card bg-warning text-dark">
            <div class="card-body text-center">
                <i class="fas fa-users fa-2x mb-2"></i>
                <h5>View Suppliers</h5>
                <a href="suppliers/view_suppliers.php" class="btn btn-dark btn-sm mt-2">Go</a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card dashboard-card bg-secondary text-white">
            <div class="card-body text-center">
                <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                <h5>View Purchases</h5>
                <a href="purchases/view_purchase.php" class="btn btn-light btn-sm mt-2">Go</a>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card dashboard-card bg-dark text-white">
            <div class="card-body text-center">
                <i class="fas fa-cart-plus fa-2x mb-2"></i>
                <h5>Add Purchase</h5>
                <a href="purchases/add_purchase.php" class="btn btn-light btn-sm mt-2">Go</a>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card dashboard-card bg-danger text-white">
            <div class="card-body text-center">
                <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                <h5>Low Stock Report</h5>
                <p class="mb-1">Items: <?php echo $low_stock_count; ?></p>
                <a href="reports/low_stock.php" class="btn btn-light btn-sm mt-2">Go</a>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <a href="products/add_product.php" class="btn btn-outline-primary w-100">
                            <i class="fas fa-plus-circle me-2"></i>Add Product
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="purchases/add_purchase.php" class="btn btn-outline-success w-100">
                            <i class="fas fa-cart-plus me-2"></i>Add Purchase
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="suppliers/add_supplier.php" class="btn btn-outline-info w-100">
                            <i class="fas fa-user-plus me-2"></i>Add Supplier
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="reports/low_stock.php" class="btn btn-outline-warning w-100">
                            <i class="fas fa-exclamation-triangle me-2"></i>Low Stock Alert
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>
