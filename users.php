<?php
include "dbconne.php";

/* Fetch users */
$users = $conn->query("SELECT * FROM users ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
<title>Users</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

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
.main{
  flex:1;
}
</style>
</head>

<body>

<div class="admin-wrapper">

<!-- SIDEBAR -->
<aside class="sidebar">
  <h3 class="logo">ShopAdmin</h3>
  <ul>
    <li><a href="index.php"><i class="fa-solid fa-house"></i> Dashboard</a></li>
    <li><a href="products.php"><i class="fa-solid fa-box"></i> Products</a></li>
    <li><a href="categories.php"><i class="fa-solid fa-list"></i> Categories</a></li>
    <li><a href="order.php"><i class="fa-solid fa-cart-shopping"></i> Orders</a></li>
    <li class="active"><a href="users.php"><i class="fa-solid fa-users"></i> Users</a></li>
    <li><a href="setting.php"><i class="fa-solid fa-gear"></i> Settings</a></li>
     <li><a href="logout.php" class="text-danger"><i class="fa fa-sign-out"></i> Logout</a></li>
  </ul>
</aside>

<!-- MAIN -->
<div class="main p-4">

<h4>Users</h4>

<table class="table table-bordered bg-white">
<thead>
<tr>
  <th>ID</th>
  <th>Name</th>
  <th>Email</th>

</tr>
</thead>

<tbody>
<?php if($users->num_rows > 0): ?>
  <?php while($u = $users->fetch_assoc()): ?>
  <tr>
    <td><?= $u['id'] ?></td>
    <td><?= htmlspecialchars($u['name']) ?></td>
    <td><?= htmlspecialchars($u['email']) ?></td>
  
  </tr>
  <?php endwhile; ?>
<?php else: ?>
  <tr>
    <td colspan="4" class="text-center">No users found</td>
  </tr>
<?php endif; ?>
</tbody>
</table>

</div>
</div>

</body>
</html>
