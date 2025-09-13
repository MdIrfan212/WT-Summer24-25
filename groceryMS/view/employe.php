<?php
session_start();

// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$db = "grocery_management";

$conn =  new mysqli($host, $user,$pass,$db);
if ($conn->connect_error) {
    die("Database connection failed:". $conn->connect_error);
}


//Notifications
$notice = "";

// Handle from submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // Add product
    if ($action === 'add_product') {
       $name = $conn->real_escape_string($_POST['name']);
       $categories = $conn->real_escape_string($_POST['categories']);
       $price = (float) $_POST['price'];
       $stock = (int) $_POST['stock'];
       $description = $conn->real_escape_string($_POST['description']);
        $created_by = $_SESSION['user']?? 'Employee';

$sql = "INSERT INTO products (name, category, price, stock, description, created_by) 
                VALUES ('$name', '$category', $price, $stock, '$description', '$created_by')";
        if ($conn->query($sql)) {
            $notice = " Product added successfully.";
        } else {
            $notice = " Error: " . $conn->error;
        }
    }
}

    // update product info
    if ($action==='update_product') {
        $id =(int) $_POST['id'];
        $price =(float) $_POST['price'] ?? 0;
        $stock =(int) $_POST['stock'] ?? 0;
        $description = $conn->real_escape_string($_POST['despription']);

        $sql = "UPDATE products
                 SET price=$price, stock=$stock, description='$description' 
                WHERE id=$id";
        if ($conn->query($sql)) {
            $notice = " Product updated successfully.";
        } else {
            $notice = " Error: " . $conn->error;
        }
    }
 // process order (mark as delivered)
 if ($action=== 'process order'){
    $orderId = (int) $_POST['order_id'];
    $sql = "UPDATE orders
    set status='delivered', delivered_at=NOW()
    WHERE id=$orderId";
    if ($conn->query($sql)) {
        $notice =" order processed successfully.";
    } else {
        $notice = "Error:". $conn->error;
    }
    }

 // Add customer complaint
 if ($action === 'add_complaint') {
        $customer = $conn->real_escape_string($_POST['customer']);
        $message  = $conn->real_escape_string($_POST['message']);

        $sql = "INSERT INTO complaints (customer, message, status, created_at) 
                VALUES ('$customer', '$message', 'open', NOW())";
        if ($conn->query($sql)) {
            $notice = " Complaint recorded.";
        } else {
            $notice = " Error: " . $conn->error;
        }
    }
  
    // Fetch data for display
    $products          = $conn->query("SELECT * From Products ORDER BY id DESC");
    $productsForUpdate = $conn->query("SELECT * From Products ORDER BY id DESC");
    $orders            =  $conn->query("SELECT * From orders Where status=' pending'");
    $complaints        = $conn->query("SELECT * From complaints ORDER BY created_at DESC");

 ?>
<!Doctype html>
<html>
    <head>
 <title>Employee Dashboard</title>
 <style>
    body { background: #f0f2f5; margin: 0; padding: 0; }
        .panel { max-width: 900px; margin: 20px auto; padding: 20px; background: #f9f9f9;
                 border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); font-family: Arial; }
        .admin-card { background: #fff; border-radius: 10px; padding: 15px 20px; margin: 20px 0;
                      box-shadow: 0 1px 5px rgba(0,0,0,0.08); }
        .admin-card h3 { margin-bottom: 15px; font-size: 18px; color: #333; }
        .notice { background: #e8f9e8; border: 1px solid #b6e0b6; color: #256029;
                  padding: 10px 15px; margin-bottom: 15px; border-radius: 8px; font-weight: bold; }
        input, select, textarea { width: 100%; padding: 8px 10px; margin: 5px 0;
                                  border: 1px solid #ccc; border-radius: 6px; font-size: 14px; }
        textarea { min-height: 80px; resize: vertical; }
        .btn { display: inline-block; background: #007bff; color: #fff; border: none;
               padding: 8px 16px; margin-top: 8px; border-radius: 6px; cursor: pointer; }
        .btn:hover { background: #0056b3; }
        .btn.small { padding: 5px 12px; font-size: 13px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; background: #fff; }
        table th, table td { padding: 8px 12px; border: 1px solid #ddd; text-align: left; }
        table th { background: #f2f2f2; font-weight: bold; }
    </style>
</head>
<body>
<div class="panel">
    <h3>Employee Dashboard</h3>
    <?php if ($notice): ?>
        <div class="notice" id="notice"><?=htmlspecialchars($notice)?></div>
        <?php endif;?>


        <!--Add Product-->
        <div class="admin-card">
            <h3>ADD New Product</h3>
            <from method="POST">
                <input type="hidden" name="action" value="add_product">
                <input type ="test" name="name" placeholder="product Name" required>
                <input type ="test" name="category" placeholder="category" required>
                <input type ="number" step="0.01" name="price"placeholder="price" required>
                <input type ="number" name="stock" placeholder="stock" required>
                <textarea name="description" placeholder="Description"></textarea>
                <button type="submit"class="bth">Add Product</button>
            </from>
        </div>

        <!-- Update Product-->
         <div class="admin-card">
            <h3>Update Product Info</h3>
            <from method="POST">
                <input type="hidden" name="action" value="update_product">
                <select name="id" required>
                    <?php While ($p=$productsForUpdate->fetch_assoc()):?>
                        <option value="<?=$p['id']?>"><?=htmlspecialchars($p['name'])?></option>
                <?php endwhile; ?>
                </select>
                <input type="number" step="0.01" name="price" placeholder="New Price">
                <input type="number" name="stock" placeholder="New Stick">
                <textarea name="description" placeholder="New Description"></textarea>
                <button type ="submit" class="btn">Update</button>

            </from>
         </div>

         <!--Pending orders-->
         <div class ="admin-card">
            <h3>Pending Orders</h3>
            <?php if($orders->num_rows===0):?>
                <p>No Pending orders.</p>
            <?php else:?>
                <?php while($o=$orders->fetch_assoc()) :?>
                    <div>
                        <p><b>order ID:</b><?=$o['id']?>|Total:৳<?=$o['total']?></p>
                        <from method="POST" onsubmit="return confirmDelivery()"></from>
                       <input type="hidden" name="action" value="process_order">
                        <input type="hidden" name="order_id" value="<?=$o['id'] ?>">
                        <button type="submit" class="btn small">Mark as Delivered</button>
                    </form>
                    </div>
                    <?php endwhile; ?>
                    <?php endif; ?>
         </div>
       <!-- Stock Report -->
    <div class="admin-card">
        <h3>Stock Report</h3>
        <input type="text"id="search" placeholder="search products..."onkeyup="searchTable()">
        <table id="stockTable"></table>
            <tr><th>Product</th><th>Stock</th><th>Price</th></tr>
            <?php while ($p=$products->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($p['name']) ?></td>
                          <td><?= p['stock'] ?></td>
                    <td><?= htmlspecialchars($p['stock']) ?></td>
                    <td>৳<?= $p['price'] ?></td>
                </tr>
            <?php endWhile; ?>
        </table>
    </div>

    <!-- Customer complaints-->
    <div class="admin-card">
        <h3>Customer Complaints</h3>
        <form method="POST">
            <input type="hidden" name="action" value="add_complaint">
            <input type="text" name="customer" placeholder="Customer Name" required>
            <textarea name="message" placeholder="Complaint Details" required></textarea>
            <button type="submit" class="btn">Submit Complaint</button>
        </form>

        <h4>Previous Complaints</h4>
        <?php if (complaints->num_row===0): ?>
            <p>No complaints yet.</p>
        <?php else: ?>
            <?php While ($c=$complaints->fetch_assoc()): ?>
                <div>
                    <p><b><?= htmlspecialchars($c['customer']) ?>:</b> <?= htmlspecialchars($c['message']) ?> (<?= $c['status'] ?>)</p>
                </div>
            <?php endWhile; ?>
        <?php endif; ?>
    </div>
</div>
<!--JavaScript-->
<script>
function confrimDelivery(){
    return confirm("Are you sure you want to mark this order as deliverd?");
}
// Auto-hide notice after 3 seconds
setTimeout(()=>{
    const notice = document.getElementById("notice");
    if(notice)notice.style.display="none";
},3000);
// Search filter for stock report
function searchTable(){
    const input=document.getElementById("search").value.toLowercase();
    const rows =document.queryselectorAll("#stockTable tr");
    for(let i=1;i<rows.lenght;i++){
        const text = rows[i].innerText.toLowercase();
        rows[i].style.display=text.includes(input)?"":"none";
    }
}

</script>
</body>
</html>