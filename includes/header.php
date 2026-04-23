<!DOCTYPE html>
<html>
<head>
    <title>Inventory System</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="../style.css">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
    <a class="navbar-brand fw-bold" href="/inventory_project/index.php">
        <i class="fas fa-boxes me-2"></i>Smart Inventory
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                <a class="nav-link" href="/inventory_project/index.php"><i class="fas fa-tachometer-alt me-1"></i>Dashboard</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="productsDropdown" role="button" data-bs-toggle="dropdown">
                    <i class="fas fa-cube me-1"></i>Products
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="/inventory_project/products/add_product.php">Add Product</a></li>
                    <li><a class="dropdown-item" href="/inventory_project/products/view_products.php">View Products</a></li>
                </ul>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="suppliersDropdown" role="button" data-bs-toggle="dropdown">
                    <i class="fas fa-truck me-1"></i>Suppliers
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="/inventory_project/suppliers/add_supplier.php">Add Supplier</a></li>
                    <li><a class="dropdown-item" href="/inventory_project/suppliers/view_suppliers.php">View Suppliers</a></li>
                </ul>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="purchasesDropdown" role="button" data-bs-toggle="dropdown">
                    <i class="fas fa-shopping-cart me-1"></i>Purchases
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="/inventory_project/purchases/add_purchase.php">Add Purchase</a></li>
                    <li><a class="dropdown-item" href="/inventory_project/purchases/view_purchase.php">View Purchases</a></li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/inventory_project/reports/low_stock.php"><i class="fas fa-chart-line me-1"></i>Reports</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container mt-4">