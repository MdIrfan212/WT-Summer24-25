<?php
session_start();

// Database connection (use same DB as other parts of the app)
$host = "localhost";
$user = "root";
$pass = "";
$db   = "grocery_ms"; // use consistent DB name

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$notice = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // ---- Add product ----------------------------------------------------
    if ($action === 'add_product') {
        $name        = $conn->real_escape_string($_POST['name'] ?? '');
        $category    = $conn->real_escape_string($_POST['category'] ?? '');
        $price       = (float)($_POST['price'] ?? 0);
        $stock       = (int)($_POST['stock'] ?? 0);
        $description = $conn->real_escape_string($_POST['description'] ?? '');
        $created_by  = $conn->real_escape_string($_SESSION['user'] ?? 'Employee');

        $sql = "INSERT INTO products (name, category, price, stock) VALUES ('$name', '$category', $price, $stock)";
        if ($conn->query($sql) === true) {
            $notice = "Product added successfully.";
        } else {
            $notice = "Error adding product: " . $conn->error;
        }
    }

    // ---- Update product -------------------------------------------------
    if ($action === 'update_product') {
        $id          = (int)($_POST['id'] ?? 0);
        $price       = (float)($_POST['price'] ?? 0);
        $stock       = (int)($_POST['stock'] ?? 0);
        $description = $conn->real_escape_string($_POST['description'] ?? '');

        $sql = "UPDATE products SET price = $price, stock = $stock WHERE id = $id";
        if ($conn->query($sql) === true) {
            $notice = "Product updated successfully.";
        } else {
            $notice = "Error updating product: " . $conn->error;
        }
    }

    // ---- Process order (mark as delivered) -----------------------------
    if ($action === 'process_order') {
        $orderId = (int)($_POST['order_id'] ?? 0);
        $sql = "UPDATE orders SET status = 'Delivered' WHERE id = $orderId";
        if ($conn->query($sql) === true) {
            $notice = "Order marked as delivered.";
        } else {
            $notice = "Error processing order: " . $conn->error;
        }
    }

    // ---- Add customer complaint -----------------------------------------
    if ($action === 'add_complaint') {
        $customer = $conn->real_escape_string($_POST['customer'] ?? '');
        $message  = $conn->real_escape_string($_POST['message'] ?? '');
        $sql = "INSERT INTO complaints (customer, message, status, created_at) " .
               "VALUES ('$customer', '$message', 'open', NOW())";
        if ($conn->query($sql) === true) {
            $notice = "Complaint recorded.";
        } else {
            $notice = "Error recording complaint: " . $conn->error;
        }
    }
}

// ---- Fetch data for display --------------------------------------------
$products          = $conn->query("SELECT * FROM products ORDER BY id DESC");
$productsForUpdate = $conn->query("SELECT * FROM products ORDER BY id DESC");
$orders            = $conn->query("SELECT * FROM orders WHERE LOWER(status) = 'pending'");
$complaints        = $conn->query("SELECT * FROM complaints ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Employee Dashboard</title>
    <style>
        body { background:#f0f2f5; margin:0; padding:0; font-family:Arial; }
        .panel { max-width:900px; margin:20px auto; padding:20px; background:#f9f9f9; border-radius:12px; box-shadow:0 2px 8px rgba(0,0,0,0.1); }
        .admin-card { background:#fff; border-radius:10px; padding:15px 20px; margin:20px 0; box-shadow:0 1px 5px rgba(0,0,0,0.08); }
        .admin-card h3 { margin-bottom:15px; font-size:18px; color:#333; }
        .notice { background:#e8f9e8; border:1px solid #b6e0b6; color:#256029; padding:10px 15px; margin-bottom:15px; border-radius:8px; font-weight:bold; }
        input, select, textarea, button { width:100%; padding:8px 10px; margin:5px 0; border:1px solid #ccc; border-radius:6px; font-size:14px; }
        button { background:#007bff; color:#fff; cursor:pointer; }
        button:hover { background:#0056b3; }
        table { width:100%; border-collapse:collapse; margin-top:10px; background:#fff; }
        table th, table td { padding:8px 12px; border:1px solid #ddd; text-align:left; }
        table th { background:#f2f2f2; }
    </style>
</head>
<body>
<div class="panel">
    <h3>Employee Dashboard</h3>
    <?php if ($notice): ?>
        <div class="notice" id="notice"><?=htmlspecialchars($notice)?></div>
    <?php endif; ?>

    <!-- Add Product -->
    <div class="admin-card">
        <h3>ADD New Product</h3>
        <form method="POST">
            <input type="hidden" name="action" value="add_product">
            <input type="text" name="name" placeholder="Product Name" required>
            <input type="text" name="category" placeholder="Category" required>
            <input type="number" step="0.01" name="price" placeholder="Price" required>
            <input type="number" name="stock" placeholder="Stock" required>
            <textarea name="description" placeholder="Description"></textarea>
            <button type="submit" class="btn">Add Product</button>
        </form>
    </div>

    <!-- Update Product -->
    <div class="admin-card">
        <h3>Update Product Info</h3>
        <form method="POST">
            <input type="hidden" name="action" value="update_product">
            <select name="id" required>
                <?php while ($p = $productsForUpdate->fetch_assoc()): ?>
                    <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['name']) ?></option>
                <?php endwhile; ?>
            </select>
            <input type="number" step="0.01" name="price" placeholder="New Price">
            <input type="number" name="stock" placeholder="New Stock">
            <textarea name="description" placeholder="New Description"></textarea>
            <button type="submit" class="btn">Update</button>
        </form>
    </div>

    <!-- Pending Orders -->
    <div class="admin-card">
        <h3>Pending Orders</h3>
        <?php if ($orders && $orders->num_rows > 0): ?>
            <?php while ($o = $orders->fetch_assoc()): ?>
                <div>
                    <p><b>Order ID:</b> <?= $o['id'] ?> | Total: ৳<?= $o['total'] ?></p>
                    <form method="POST" onsubmit="return confirmDelivery();">
                        <input type="hidden" name="action" value="process_order">
                        <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
                        <button type="submit" class="btn small">Mark as Delivered</button>
                    </form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No pending orders.</p>
        <?php endif; ?>
    </div>

    <!-- Stock Report -->
    <div class="admin-card">
        <h3>Stock Report</h3>
        <input type="text" id="search" placeholder="Search products..." onkeyup="searchTable()">
        <table id="stockTable">
            <tr><th>Product</th><th>Stock</th><th>Price</th></tr>
            <?php while ($p = $products->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($p['name']) ?></td>
                    <td><?= $p['stock'] ?></td>
                    <td>৳<?= $p['price'] ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <!-- Customer Complaints -->
    <div class="admin-card">
        <h3>Customer Complaints</h3>
        <form method="POST">
            <input type="hidden" name="action" value="add_complaint">
            <input type="text" name="customer" placeholder="Customer Name" required>
            <textarea name="message" placeholder="Complaint Details" required></textarea>
            <button type="submit" class="btn">Submit Complaint</button>
        </form>
        <h4>Previous Complaints</h4>
        <?php if ($complaints && $complaints->num_rows > 0): ?>
            <?php while ($c = $complaints->fetch_assoc()): ?>
                <div>
                    <p><b><?= htmlspecialchars($c['customer']) ?>:</b> <?= htmlspecialchars($c['message']) ?> (<?= $c['status'] ?>)</p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No complaints yet.</p>
        <?php endif; ?>
    </div>
</div>
<script>
function confirmDelivery(){
    return confirm('Are you sure you want to mark this order as delivered?');
}
// Auto‑hide notice after 3 seconds
setTimeout(()=>{ const n=document.getElementById('notice'); if(n)n.style.display='none'; },3000);
// Search filter for stock report
function searchTable(){
    const input=document.getElementById('search').value.toLowerCase();
    const rows=document.querySelectorAll('#stockTable tr');
    for(let i=1;i<rows.length;i++){
        const txt=rows[i].innerText.toLowerCase();
        rows[i].style.display = txt.includes(input) ? '' : 'none';
    }
}
</script>
</body>
</html>
