<?php
session_start();
include "dbconne.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

/* FETCH PRODUCTS */
$products = $conn->query("SELECT * FROM product ORDER BY product_id DESC");
?>
<!DOCTYPE html>
<html>
<head>
<title>Products</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
body{
  margin:0;
  font-family:"Segoe UI",sans-serif;
  background:#f4f6fb;
}
.admin-wrapper{display:flex;height:100vh}

/* SIDEBAR */
.sidebar{
  width:240px;
  background:#111827;
  color:#fff;
  padding:20px;
}
.logo{text-align:center;margin-bottom:30px}
.sidebar ul{list-style:none;padding:0}
.sidebar li{margin-bottom:8px;border-radius:8px}
.sidebar li a{
  display:block;
  padding:12px;
  color:#fff;
  text-decoration:none
}
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
<li class="active"><a href="products.php"><i class="fa fa-box"></i> Products</a></li>
<li><a href="categories.php"><i class="fa fa-users"></i> Categories</a></li>
<li><a href="order.php"><i class="fa fa-shopping-cart"></i> Orders</a></li>
<li><a href="users.php"><i class="fa fa-users"></i> Users</a></li>
<li>
      <a href="setting.php"><i class="fa fa-cog"></i> Settings</a>
    </li>
    <li><a href="logout.php" class="text-danger"><i class="fa fa-sign-out"></i> Logout</a></li>
</ul>
</aside>

<!-- MAIN -->
<div class="main">

<div class="d-flex justify-content-between align-items-center mb-3">
<h4>Products</h4>
<select class="form-select w-auto"
        onchange="if(this.value) window.location.href=this.value">
  <option value="">+ Add Product (Select Category)</option>
  <option value="add-product.php?category=Electronics">Electronics</option>
  <option value="add-product.php?category=Mobile">Mobile</option>
  <option value="add-product.php?category=Kitchen & Home">Kitchen & Home</option>
  <option value="add-product.php?category=Fashion">Fashion</option>
</select>

</div>

<table class="table table-bordered bg-white">
<thead>
<tr>
<th>ID</th>
<th>Image</th>
<th>Name</th>
<th>Price</th>
<th>Category</th>
<th>Action</th>
</tr>
</thead>

<tbody>
<?php if ($products && $products->num_rows > 0): ?>
<?php while($p = $products->fetch_assoc()): ?>
<tr>
<td><?= $p['product_id'] ?></td>

<td>
<img src="../uploads/<?= htmlspecialchars($p['image']) ?>"
     width="60" height="60"
     style="object-fit:cover;border-radius:6px">
</td>

<td><?= htmlspecialchars($p['name']) ?></td>
<td>₹<?= number_format($p['price'],2) ?></td>
<td><?= htmlspecialchars($p['category']) ?></td>

<td>
<a href="edit-product.php?id=<?= $p['product_id'] ?>"
   class="btn btn-warning btn-sm">
   Edit
</a>

<a href="delete-product.php?id=<?= $p['product_id'] ?>"
   onclick="return confirm('Delete this product?')"
   class="btn btn-danger btn-sm">
   Delete
</a>
</td>
</tr>
<?php endwhile; ?>
<?php else: ?>
<tr>
<td colspan="6" class="text-center">No products found</td>
</tr>
<?php endif; ?>
</tbody>
</table>

</div>
</div>

</body>
</html>
