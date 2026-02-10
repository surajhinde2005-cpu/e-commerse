<?php
session_start();
include "dbconne.php"; // adjust path if needed

error_reporting(E_ALL);
ini_set('display_errors', 1);

/* -------------------
   UPDATE ORDER STATUS
------------------- */
if (isset($_POST['order_id'], $_POST['status'])) {
    $orderId = (int)$_POST['order_id'];
    $status  = $_POST['status'];

    $stmt = $conn->prepare("UPDATE orders SET status=? WHERE id=?");
    $stmt->bind_param("si", $status, $orderId);
    $stmt->execute();
}

/* -------------------
   FETCH ORDERS
------------------- */
$orders = $conn->query(
    "SELECT id, fname, amount, status, created_at 
     FROM orders 
     ORDER BY id DESC"
);
?>
<!DOCTYPE html>
<html>
<head>
<title>Orders</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
body{margin:0;font-family:"Segoe UI",sans-serif;background:#f4f6fb}
.admin-wrapper{display:flex;height:100vh}

/* SIDEBAR */
.sidebar{width:240px;background:#111827;color:#fff;padding:20px}
.logo{text-align:center;margin-bottom:30px}
.sidebar ul{list-style:none;padding:0}
.sidebar li{margin-bottom:8px;border-radius:8px}
.sidebar li a{display:block;padding:12px;color:#fff;text-decoration:none}
.sidebar li:hover,.sidebar li.active{background:#2874f0}

/* MAIN */
.main{flex:1;padding:20px}
</style>
</head>

<body>

<div class="admin-wrapper">

<!-- SIDEBAR -->
<aside class="sidebar">
<h3 class="logo">ShopAdmin</h3>
<ul>
<li><a href="dashboard.php"><i class="fa fa-home"></i> Dashboard</a></li>
<li><a href="products.php"><i class="fa fa-box"></i> Products</a></li>
<li><a href="categories.php"><i class="fa fa-users"></i> Categories</a></li>
<li class="active"><a href="order.php"><i class="fa fa-shopping-cart"></i> Orders</a></li>
<li><a href="users.php"><i class="fa fa-users"></i> Users</a></li>
<li>
      <a href="setting.php"><i class="fa fa-cog"></i> Settings</a>
    </li>
    <li><a href="logout.php" class="text-danger"><i class="fa fa-sign-out"></i> Logout</a></li>
</ul>
</aside>

<!-- MAIN -->
<div class="main">

<h4 class="mb-4">Orders</h4>

<table class="table table-striped bg-white">
<thead>
<tr>
<th>ID</th>
<th>Customer</th>
<th>Amount</th>
<th>Status</th>
<th>Date</th>
</tr>
</thead>

<tbody>
<?php if ($orders && $orders->num_rows > 0): ?>
<?php while ($o = $orders->fetch_assoc()): ?>
<tr>
<td>#<?= $o['id'] ?></td>
<td><?= htmlspecialchars($o['fname']) ?></td>
<td>₹<?= number_format($o['amount'],2) ?></td>

<td>
<form method="POST">
<input type="hidden" name="order_id" value="<?= $o['id'] ?>">
<select name="status"
        class="form-select form-select-sm"
        onchange="this.form.submit()">

<option <?= $o['status']=='Pending'?'selected':'' ?>>Pending</option>
<option <?= $o['status']=='Delivered'?'selected':'' ?>>Delivered</option>
<option <?= $o['status']=='Cancelled'?'selected':'' ?>>Cancelled</option>

</select>
</form>
</td>

<td><?= date("d M Y", strtotime($o['created_at'])) ?></td>
</tr>
<?php endwhile; ?>
<?php else: ?>
<tr>
<td colspan="5" class="text-center">No orders found</td>
</tr>
<?php endif; ?>
</tbody>

</table>

</div>
</div>

</body>
</html>
