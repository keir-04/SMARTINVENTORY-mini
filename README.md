# Smart Inventory Management System - Project Report

## Project Overview

This is a comprehensive PHP-based inventory management system designed to efficiently track products, suppliers, and purchases. The system provides a user-friendly web interface for managing inventory operations with real-time stock updates and reporting capabilities.

**Date Created:** April 23, 2026  
**Version:** 1.0  
**Technology Stack:** PHP, MySQL, HTML5, CSS3, Bootstrap 5, Font Awesome

## Technologies Used

### Backend
- **PHP 7.4+**: Server-side scripting for business logic and database operations
- **MySQL**: Relational database for data storage and management
- **Prepared Statements**: Secure database queries to prevent SQL injection

### Frontend
- **HTML5**: Semantic markup structure
- **CSS3**: Custom styling with gradients and animations
- **Bootstrap 5**: Responsive framework for UI components
- **Font Awesome 6**: Icon library for visual enhancement
- **JavaScript**: Client-side validation and interactivity

### Development Tools
- **XAMPP**: Local development environment (Apache, MySQL, PHP)
- **VS Code**: Code editor with PHP extensions

## Database Structure

### Tables and Attributes

#### 1. `products`
- `product_id` (INT, PRIMARY KEY, AUTO_INCREMENT): Unique product identifier
- `product_name` (VARCHAR): Name of the product
- `category_id` (INT, FOREIGN KEY): Reference to categories table
- `price` (DECIMAL): Unit price of the product
- `stock` (INT): Current stock quantity

#### 2. `categories`
- `category_id` (INT, PRIMARY KEY, AUTO_INCREMENT): Unique category identifier
- `category_name` (VARCHAR): Name of the product category

#### 3. `suppliers`
- `supplier_id` (INT, PRIMARY KEY, AUTO_INCREMENT): Unique supplier identifier
- `supplier_name` (VARCHAR): Name of the supplier
- `phone` (VARCHAR): Contact phone number
- `email` (VARCHAR): Contact email address

#### 4. `purchases`
- `purchase_id` (INT, PRIMARY KEY, AUTO_INCREMENT): Unique purchase identifier
- `supplier_id` (INT, FOREIGN KEY): Reference to suppliers table
- `purchase_date` (DATE): Date of purchase
- `total_amount` (DECIMAL): Total purchase amount

#### 5. `purchase_items`
- `purchase_id` (INT, FOREIGN KEY): Reference to purchases table
- `product_id` (INT, FOREIGN KEY): Reference to products table
- `quantity` (INT): Quantity purchased
- `price` (DECIMAL): Price per unit at time of purchase

## Main Functions and Features

### 1. Dashboard (`index.php`)
**Purpose:** Central hub displaying system overview and quick access to functions

**Key Functions:**
- Display real-time statistics (total products, suppliers, inventory value, low stock alerts)
- Navigation cards for all major operations
- Responsive grid layout with interactive hover effects

**Attributes Used:**
- Product count calculation
- Supplier count calculation
- Inventory value computation (SUM(price * stock))
- Low stock detection (stock < 5)

### 2. Product Management
#### Add Product (`products/add_product.php`)
**Functions:**
- Form validation and data sanitization
- Automatic category creation if not exists
- Database insertion with prepared statements

**Attributes:** product_name, category_name, price

#### View Products (`products/view_products.php`)
**Functions:**
- Display all products with category information
- Search functionality by product name
- Stock level indicators (color-coded badges)
- Delete product functionality with confirmation
- Pagination-ready structure

**Attributes:** product_id, product_name, category_name, price, stock

### 3. Supplier Management
#### Add Supplier (`suppliers/add_supplier.php`)
**Functions:**
- Form input validation
- Database insertion for supplier records

**Attributes:** supplier_name, phone, email

#### View Suppliers (`suppliers/view_suppliers.php`)
**Functions:**
- Display supplier list in tabular format
- Responsive table design

**Attributes:** supplier_name, phone, email

### 4. Purchase Management
#### Add Purchase (`purchases/add_purchase.php`)
**Functions:**
- Multi-step transaction processing
- Stock level updates upon purchase
- Total amount calculation
- Transaction rollback on failure

**Attributes:** supplier_id, product_id, quantity, price, total_amount

#### View Purchases (`purchases/view_purchase.php`)
**Functions:**
- Display purchase history with supplier details
- Formatted currency display

**Attributes:** purchase_id, supplier_name, purchase_date, total_amount

### 5. Reporting (`reports/low_stock.php`)
**Functions:**
- Identify products with low stock (threshold: 10 units)
- Alert system for inventory management
- Color-coded stock indicators

**Attributes:** product_name, stock

## File Structure

```
inventory_project/
├── index.php                    # Main dashboard
├── README.md                    # Project documentation
├── style.css                    # Custom CSS styles
├── config/
│   └── db.php                   # Database configuration
├── includes/
│   ├── header.php               # Common page header
│   └── footer.php               # Common page footer
├── products/
│   ├── add_product.php          # Product creation form
│   └── view_products.php        # Product listing with CRUD
├── suppliers/
│   ├── add_supplier.php         # Supplier creation form
│   └── view_suppliers.php       # Supplier listing
├── purchases/
│   ├── add_purchase.php         # Purchase creation form
│   └── view_purchase.php        # Purchase history
└── reports/
    └── low_stock.php            # Low stock alerts
```

## Key PHP Functions and Concepts Used

### Database Operations
- `mysqli_connect()`: Establish database connection
- `prepare()` / `bind_param()` / `execute()`: Prepared statements for security
- `query()`: Direct SQL queries for SELECT operations
- `fetch_assoc()`: Retrieve query results as associative arrays
- `begin_transaction()` / `commit()` / `rollback()`: Transaction management

### Data Processing
- `htmlspecialchars()`: XSS prevention through output sanitization
- `real_escape_string()`: SQL injection prevention
- `isset()` / `$_POST` / `$_GET`: Form data handling
- `header()`: HTTP redirects (not currently used but prepared for)

### Business Logic
- **Stock Management**: Automatic stock updates on purchases
- **Category Management**: Dynamic category creation
- **Search Functionality**: Real-time product search
- **Validation**: Client and server-side form validation

### UI/UX Features
- **Responsive Design**: Bootstrap grid system
- **Interactive Elements**: Hover effects, transitions
- **Visual Feedback**: Success/error alerts, loading states
- **Accessibility**: Semantic HTML, ARIA labels

## Security Measures

1. **SQL Injection Prevention**: All queries use prepared statements
2. **XSS Protection**: Output sanitization with htmlspecialchars()
3. **Input Validation**: Required fields and data type checking
4. **Error Handling**: Controlled error display without exposing sensitive information

## Setup Instructions

1. **Environment Setup:**
   - Install XAMPP or similar PHP/MySQL stack
   - Start Apache and MySQL services

2. **Database Setup:**
   - Create database: `inventory_db`
   - Import the provided SQL schema (if available) or create tables manually

3. **Project Deployment:**
   - Place project files in web server root (e.g., `htdocs/inventory_project/`)
   - Update database credentials in `config/db.php` if needed
   - Access via browser: `http://localhost/inventory_project/`

## Future Enhancements

- User authentication and role-based access
- Advanced reporting with charts (Chart.js integration)
- Inventory forecasting
- Barcode scanning integration
- API endpoints for mobile app integration
- Email notifications for low stock alerts

## Conclusion

This inventory management system demonstrates a solid foundation in PHP web development with proper database design, security practices, and user experience considerations. The modular structure allows for easy maintenance and future expansions, making it suitable for small to medium-sized businesses requiring inventory tracking capabilities.

**Total Lines of Code:** ~800+  
**Database Tables:** 5  
**Main Features:** 8 core functions  
**Technologies Integrated:** 6

---

*Report generated on April 23, 2026*
