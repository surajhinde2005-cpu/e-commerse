<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include "dbconne.php";

/* CHECK DB CONNECTION */
if (!isset($conn) || $conn->connect_error) {
    die("Database connection failed");
}

/* ADD CATEGORY */
if (isset($_POST['add'])) {
    $name = trim($_POST['name']);

    if ($name !== "") {
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
        if ($stmt) {
            $stmt->bind_param("s", $name);
            $stmt->execute();
            $stmt->close();
        }
        header("Location: categories.php");
        exit;
    }
}

/* DELETE CATEGORY */
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM categories WHERE id = $id");
    header("Location: categories.php");
    exit;
}

/* FETCH CATEGORIES (SAFE) */
$categories = $conn->query("SELECT * FROM categories ORDER BY id DESC");
if (!$categories) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Categories</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

</head>

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
    <li class="active"><a href="categories.php"><i class="fa-solid fa-list"></i> Categories</a></li>
    <li><a href="order.php"><i class="fa-solid fa-cart-shopping"></i> Orders</a></li>
    <li><a href="users.php"><i class="fa-solid fa-users"></i> Users</a></li>
    <li><a href="setting.php"><i class="fa-solid fa-gear"></i> Settings</a></li>
     <li><a href="logout.php" class="text-danger"><i class="fa fa-sign-out"></i> Logout</a></li>
  </ul>
</aside>

<body class="bg-light">

<div class="container mt-5">

<h3 class="mb-3">Manage Categories</h3>

<!-- ADD FORM -->
<form method="post" class="d-flex gap-2 mb-4">
  <input type="text" name="name" class="form-control" placeholder="Category name" required>
  <button type="submit" name="add" class="btn btn-primary">Add</button>
</form>

<table class="table table-bordered bg-white">
<thead>
<tr>
  <th>ID</th>
  <th>Category Name</th>
  <th>Action</th>
</tr>
</thead>

<tbody>
<?php if ($categories->num_rows > 0): ?>
  <?php while ($row = $categories->fetch_assoc()): ?>
  <tr>
    <td><?= $row['id'] ?></td>
    <td><?= htmlspecialchars($row['name']) ?></td>
    <td>
      <a href="?delete=<?= $row['id'] ?>"
         onclick="return confirm('Delete this category?')"
         class="btn btn-danger btn-sm">
         Delete
      </a>
    </td>
  </tr>
  <?php endwhile; ?>
<?php else: ?>
<tr>
  <td colspan="3" class="text-center">No categories found</td>
</tr>
<?php endif; ?>
</tbody>
</table>

</div>

</body>
</html>
