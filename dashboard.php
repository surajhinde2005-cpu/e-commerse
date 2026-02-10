<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include "dbconne.php"; // MUST BE CORRECT PATH

if (!isset($conn)) {
    die("❌ dbconne.php did not create \$conn");
}

/* ------------------
   DEFAULT VALUES
------------------ */
$totalProducts = 0;
$totalOrders   = 0;
$totalUsers    = 0;
$revenue       = 0;

/* ------------------
   PRODUCTS
------------------ */
$res = $conn->query("SHOW TABLES LIKE 'product'");
if ($res && $res->num_rows > 0) {
    $q = $conn->query("SELECT COUNT(*) AS c FROM product");
    $totalProducts = $q->fetch_assoc()['c'];
}

/* ------------------
   ORDERS
------------------ */
$res = $conn->query("SHOW TABLES LIKE 'orders'");
if ($res && $res->num_rows > 0) {
    $q = $conn->query("SELECT COUNT(*) AS c FROM orders");
    $totalOrders = $q->fetch_assoc()['c'];

    $q2 = $conn->query("SELECT SUM(amount) AS s FROM orders");
    $revenue = $q2->fetch_assoc()['s'] ?? 0;
}

/* ------------------
   USERS (OPTIONAL)
------------------ */
$res = $conn->query("SHOW TABLES LIKE 'users'");
if ($res && $res->num_rows > 0) {
    $q = $conn->query("SELECT COUNT(*) AS c FROM users");
    $totalUsers = $q->fetch_assoc()['c'];
}

/* ------------------
   RECENT ORDERS
------------------ */
$recentOrders = null;
$res = $conn->query("SHOW TABLES LIKE 'orders'");
if ($res && $res->num_rows > 0) {
    $recentOrders = $conn->query(
        "SELECT id, fname, amount FROM orders ORDER BY id DESC LIMIT 5"
    );
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
body{background:#f4f6fb;font-family:Segoe UI}
.card{border-radius:16px}
</style>
</head>

<body>

<style>
body{
  margin:0;
  font-family:"Segoe UI",sans-serif;
  background:#f4f6fb;
}

.admin-wrapper{
  display:flex;
  height:100vh;
}

/* SIDEBAR */
.sidebar{
  width:240px;
  background:#111827;
  color:#fff;
  padding:20px;
}
.logo{
  text-align:center;
  margin-bottom:30px;
}
.sidebar ul{
  list-style:none;
  padding:0;
}
.sidebar li{
  margin-bottom:8px;
  border-radius:8px;
}

.sidebar li a{
  display:block;
  padding:12px;
  color:#fff;
  text-decoration:none;
}

.sidebar li:hover,
.sidebar li.active{
  background:#2874f0;
}


/* MAIN */
.main{
  flex:1;
}

/* TOPBAR */
.topbar{
  height:60px;
  background:#fff;
  display:flex;
  justify-content:space-between;
  align-items:center;
  padding:0 20px;
  box-shadow:0 2px 10px rgba(0,0,0,.08);
}

/* STAT CARDS */
.stat-card{
  padding:20px;
  border-radius:18px;
  color:#fff;
  box-shadow:0 10px 30px rgba(0,0,0,.15);
}
.stat-card h6{
  font-size:14px;
  opacity:.9;
}
.stat-card h2{
  font-weight:800;
}

.blue{background:#2874f0}
.green{background:#16a34a}
.orange{background:#f97316}
.red{background:#dc2626}

/* DARK MODE */
body.dark{
  background:#0f172a;
  color:#fff;
}
body.dark .topbar{
  background:#111827;
  color:#fff;
}
</style>


<div class="admin-wrapper">

<aside class="sidebar">
  <h3 class="logo">ShopAdmin</h3>
  <ul>
    <li class="active">
     <a href="dashboard.php"><i class="fa fa-home"></i> Dashboard</a>
    </li>

    <li>
      <a href="products.php"><i class="fa fa-box"></i> Products</a>
    </li>

    <li>
      <a href="categories.php"><i class="fa fa-list"></i> Categories</a>
    </li>

    <li>
      <a href="order.php"><i class="fa fa-shopping-cart"></i> Orders</a>
    </li>

    <li>
      <a href="users.php"><i class="fa fa-users"></i> Users</a>
    </li>

    <li>
      <a href="setting.php"><i class="fa fa-cog"></i> Settings</a>
    </li>
    <li><a href="logout.php" class="text-danger"><i class="fa fa-sign-out"></i> Logout</a></li>
  </ul>
</aside>
<div class="container mt-5">
<h3 class="mb-4">Admin Dashboard</h3>

<div class="row g-4">

<div class="col-md-3">
  <div class="card p-3 bg-primary text-white">
    <h6>Total Products</h6>
    <h2><?= $totalProducts ?></h2>
  </div>
</div>

<div class="col-md-3">
  <div class="card p-3 bg-success text-white">
    <h6>Total Orders</h6>
    <h2><?= $totalOrders ?></h2>
  </div>
</div>

<div class="col-md-3">
  <div class="card p-3 bg-warning text-white">
    <h6>Total Users</h6>
    <h2><?= $totalUsers ?></h2>
  </div>
</div>

<div class="col-md-3">
  <div class="card p-3 bg-danger text-white">
    <h6>Revenue</h6>
    <h2>₹<?= number_format($revenue,0) ?></h2>
  </div>
</div>

</div>

<hr class="my-5">

<h5>Recent Orders</h5>

<table class="table table-bordered bg-white">
<tr>
  <th>ID</th>
  <th>Name</th>
  <th>Amount</th>
</tr>

<?php
if ($recentOrders && $recentOrders->num_rows > 0) {
    while ($row = $recentOrders->fetch_assoc()) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['fname']}</td>
                <td>₹".number_format($row['amount'],2)."</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='3' class='text-center'>No orders</td></tr>";
}
?>

</table>
</div>

</body>
</html>
