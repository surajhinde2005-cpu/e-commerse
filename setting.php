<?php
include "dbconne.php";

/* Fetch settings */
$set = $conn->query("SELECT * FROM settings LIMIT 1")->fetch_assoc();

if(!$set){
    $conn->query("INSERT INTO settings (site_name) VALUES ('My Website')");
    $set = $conn->query("SELECT * FROM settings LIMIT 1")->fetch_assoc();
}


/* Update settings */
if(isset($_POST['save'])){
    $site_name = $_POST['site_name'];
    $payment   = $_POST['payment_method'];

    /* Logo upload */
    if(!empty($_FILES['logo']['name'])){
        $logo = "uploads/".time().$_FILES['logo']['name'];
        move_uploaded_file($_FILES['logo']['tmp_name'], $logo);
    } else {
        $logo = $set['logo'];
    }

    $stmt = $conn->prepare(
        "UPDATE settings SET site_name=?, logo=?, payment_method=? WHERE id=?"
    );
    $stmt->bind_param("sssi", $site_name, $logo, $payment, $set['id']);
    $stmt->execute();

    header("Location: setting.php?success=1");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Settings</title>

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
    <li><a href="dashboard.php"><i class="fa-solid fa-house"></i> Dashboard</a></li>
    <li><a href="products.php"><i class="fa-solid fa-box"></i> Products</a></li>
    <li><a href="categories.php"><i class="fa-solid fa-list"></i> Categories</a></li>
    <li><a href="order.php"><i class="fa-solid fa-cart-shopping"></i> Orders</a></li>
    <li><a href="users.php"><i class="fa-solid fa-users"></i> Users</a></li>
    <li class="active"><a href="setting.php"><i class="fa-solid fa-gear"></i> Settings</a></li>
     <li><a href="logout.php" class="text-danger"><i class="fa fa-sign-out"></i> Logout</a></li>
  </ul>
</aside>

<!-- MAIN -->
<div class="main p-4">

<h4>Website Settings</h4>

<?php if(isset($_GET['success'])): ?>
<div class="alert alert-success">Settings updated successfully</div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data" class="bg-white p-4 rounded">

<label>Site Name</label>
<input type="text" name="site_name" class="form-control mb-3"
       value="<?= htmlspecialchars($set['site_name']) ?>" required>

<label>Logo</label>
<input type="file" name="logo" class="form-control mb-2">
<?php if($set['logo']): ?>
<img src="<?= $set['logo'] ?>" height="50">
<?php endif; ?>

<label class="mt-3">Payment Method</label>
<select name="payment_method" class="form-select mb-3">
  <option <?= $set['payment_method']=="Cash On Delivery"?"selected":"" ?>>Cash On Delivery</option>
  <option <?= $set['payment_method']=="UPI"?"selected":"" ?>>UPI</option>
  <option <?= $set['payment_method']=="Card"?"selected":"" ?>>Card</option>
</select>

<button name="save" class="btn btn-success">Save Settings</button>

</form>

</div>
</div>

</body>
</html>
