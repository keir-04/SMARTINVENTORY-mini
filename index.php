<?php include("includes/header.php"); ?>

<h3 class="mb-4">Dashboard</h3>

<div class="row g-4">

    <div class="col-md-3">
        <div class="card dashboard-card bg-primary text-white">
            <div class="card-body text-center">
                <h5>Add Product</h5>
                <a href="products/add_product.php" class="btn btn-light btn-sm mt-2">Go</a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card dashboard-card bg-info text-white">
            <div class="card-body text-center">
                <h5>View Products</h5>
                <a href="products/view_products.php" class="btn btn-light btn-sm mt-2">Go</a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card dashboard-card bg-success text-white">
            <div class="card-body text-center">
                <h5>Add Supplier</h5>
                <a href="suppliers/add_supplier.php" class="btn btn-light btn-sm mt-2">Go</a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card dashboard-card bg-warning text-dark">
            <div class="card-body text-center">
                <h5>View Suppliers</h5>
                <a href="suppliers/view_suppliers.php" class="btn btn-dark btn-sm mt-2">Go</a>
            </div>
        </div>
    </div>

</div>

<br>

<div class="row g-4">

    <div class="col-md-6">
        <div class="card dashboard-card bg-dark text-white">
            <div class="card-body text-center">
                <h5>Add Purchase</h5>
                <a href="purchases/add_purchase.php" class="btn btn-light btn-sm mt-2">Go</a>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card dashboard-card bg-danger text-white">
            <div class="card-body text-center">
                <h5>Low Stock Report</h5>
                <a href="reports/low_stock.php" class="btn btn-light btn-sm mt-2">Go</a>
            </div>
        </div>
    </div>

</div>

<?php include("includes/footer.php"); ?>