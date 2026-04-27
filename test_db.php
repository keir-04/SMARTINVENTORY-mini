<?php
include("config/db.php");

echo "<h2>Database Connection Test</h2>";
echo "Host: $host<br>";
echo "Port: $port<br>";
echo "Database: $db<br>";

$tables = ['categories', 'suppliers', 'products', 'purchases', 'purchase_items'];

foreach ($tables as $table) {
    try {
        $result = $conn->query("SHOW TABLES LIKE '$table'");
        if ($result->num_rows > 0) {
            $count = $conn->query("SELECT COUNT(*) as total FROM $table")->fetch_assoc()['total'];
            echo "✅ Table <b>$table</b> exists. Total records: $count<br>";
        } else {
            echo "❌ Table <b>$table</b> does NOT exist.<br>";
        }
    } catch (Exception $e) {
        echo "❌ Error checking table <b>$table</b>: " . $e->getMessage() . "<br>";
    }
}

echo "<br><a href='index.php'>Go to Dashboard</a>";
?>