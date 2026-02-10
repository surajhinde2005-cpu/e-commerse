<?php
session_start();
if(!isset($_SESSION['admin'])){
  header("Location: login.php");
  exit;
}

include "dbconne.php";

$id = (int)($_GET['id'] ?? 0);
if($id == 0) die("Invalid Product");

$p = $conn->query("SELECT * FROM product WHERE product_id=$id")->fetch_assoc();
if(!$p) die("Product not found");

/* FIX CATEGORY */
$selectedCategory = $p['category'];
if ($selectedCategory === 'Kitchen') {
  $selectedCategory = 'Kitchen & Home';
}

/* UPDATE */
if(isset($_POST['update'])){

  function esc($v){ return htmlspecialchars(trim($v)); }

  $name     = esc($_POST['name']);
  $price    = esc($_POST['price']);
  $category = esc($_POST['category']);

  /* COMMON */
  $brand    = esc($_POST['brand'] ?? '');
  $ram      = esc($_POST['ram'] ?? '');
  $storage  = esc($_POST['storage'] ?? '');
  $battery  = esc($_POST['battery'] ?? '');
  $network  = esc($_POST['network'] ?? '');
  $camera   = esc($_POST['camera'] ?? '');
  $os       = esc($_POST['os'] ?? '');
  $discount = esc($_POST['discount'] ?? '');

  /* ELECTRONICS */
  $processor = esc($_POST['processor'] ?? '');

  /* FASHION */
  $gender   = esc($_POST['gender'] ?? '');
  $rating   = esc($_POST['rating'] ?? '');
  $size     = esc($_POST['size'] ?? '');
  $color    = esc($_POST['color'] ?? '');
  $occasion = esc($_POST['occasion'] ?? '');
  $fabric   = esc($_POST['fabric'] ?? '');
  $type     = esc($_POST['type'] ?? '');
  $offers   = esc($_POST['offers'] ?? '');
  $new_arrival = isset($_POST['new_arrival']) ? 1 : 0;

  /* KITCHEN & HOME */
  $hk_category    = esc($_POST['hk_category'] ?? '');
  $hk_subcategory = esc($_POST['hk_subcategory'] ?? '');
  $hk_brand       = esc($_POST['hk_brand'] ?? '');
  $features       = esc($_POST['features'] ?? '');

  /* IMAGE */
  $imgSQL = "";
  if(!empty($_FILES['image']['name'])){
    $image = time().'_'.$_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], "uploads/".$image);
    $imgSQL = ", image='$image'";
  }

  $conn->query("
    UPDATE product SET
    name='$name',
    price='$price',
    category='$category',
    brand='$brand',
    ram='$ram',
    storage='$storage',
    battery='$battery',
    network='$network',
    camera='$camera',
    os='$os',
    processor='$processor',
    gender='$gender',
    discount='$discount',
    rating='$rating',
    size='$size',
    color='$color',
    occasion='$occasion',
    fabric='$fabric',
    type='$type',
    offers='$offers',
    new_arrival='$new_arrival',
    features='$features',
    hk_category='$hk_category',
    hk_subcategory='$hk_subcategory',
    hk_brand='$hk_brand'
    $imgSQL
    WHERE product_id=$id
  ");

  header("Location: products.php");
  exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Product</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-4">

<h3>Edit Product</h3>

<form method="post" enctype="multipart/form-data">

<!-- CATEGORY -->
<div class="mb-3">
  <label>Category</label>
  <input type="text" name="category" class="form-control"
         value="<?= htmlspecialchars($selectedCategory) ?>" readonly>
</div>

<!-- NAME -->
<div class="mb-3">
  <label>Product Name</label>
  <input type="text" name="name" class="form-control"
         value="<?= htmlspecialchars($p['name']) ?>" required>
</div>

<!-- PRICE -->
<div class="mb-3">
  <label>Price</label>
  <input type="number" name="price" class="form-control"
         value="<?= $p['price'] ?>" required>
</div>

<!-- IMAGE -->
<div class="mb-3">
  <label>Image (leave blank to keep old)</label><br>
  <img src="uploads/<?= $p['image'] ?>" width="120"><br><br>
  <input type="file" name="image" class="form-control">
</div>

<!-- ================= MOBILE ================= -->
<?php if($selectedCategory == 'Mobile'): ?>
<div class="mb-3">
  <label>Brand</label>
  <input name="brand" class="form-control" value="<?= $p['brand'] ?>">
</div>

<div class="mb-3">
  <label>RAM</label>
  <input name="ram" class="form-control" value="<?= $p['ram'] ?>">
</div>

<div class="mb-3">
  <label>Storage</label>
  <input name="storage" class="form-control" value="<?= $p['storage'] ?>">
</div>

<div class="mb-3">
  <label>Battery</label>
  <input name="battery" class="form-control" value="<?= $p['battery'] ?>">
</div>

<div class="mb-3">
  <label>Network</label>
  <select name="network" class="form-control">
    <option <?= ($p['network']=='4G')?'selected':'' ?>>4G</option>
    <option <?= ($p['network']=='5G')?'selected':'' ?>>5G</option>
  </select>
</div>

<div class="mb-3">
  <label>Operating System (OS)</label>
  <select name="os" class="form-control">
    <option value="">Select OS</option>
    <option <?= ($p['os']=='Android')?'selected':'' ?>>Android</option>
    <option <?= ($p['os']=='iOS')?'selected':'' ?>>iOS</option>
    <option <?= ($p['os']=='HarmonyOS')?'selected':'' ?>>HarmonyOS</option>
    <option <?= ($p['os']=='Windows Mobile')?'selected':'' ?>>Windows Mobile</option>

  </select>
</div>

<div class="mb-3">
  <label>Camera</label>
  <select name="camera" class="form-control">
    <option value="">Select Camera</option>
    <option <?= ($p['camera']=='12 MP')?'selected':'' ?>>12 MP</option>
    <option <?= ($p['camera']=='16 MP')?'selected':'' ?>>16 MP</option>
    <option <?= ($p['camera']=='32 MP')?'selected':'' ?>>32 MP</option>
    <option <?= ($p['camera']=='48 MP')?'selected':'' ?>>48 MP</option>
    <option <?= ($p['camera']=='50 MP')?'selected':'' ?>>50 MP</option>
    <option <?= ($p['camera']=='64 MP')?'selected':'' ?>>64 MP</option>
    <option <?= ($p['camera']=='108 MP')?'selected':'' ?>>108 MP</option>
  </select>
</div>
<?php endif; ?>

<!-- ================= ELECTRONICS ================= -->
<?php if($selectedCategory == 'Electronics'): ?>
<div class="mb-3">
  <label>Processor</label>
  <input name="processor" class="form-control" value="<?= $p['processor'] ?>">
</div>

<div class="mb-3">
  <label>RAM</label>
  <input name="ram" class="form-control" value="<?= $p['ram'] ?>">
</div>

<div class="mb-3">
  <label>Storage</label>
  <input name="storage" class="form-control" value="<?= $p['storage'] ?>">
</div>

<div class="mb-3">
  <label>Operating System (OS)</label>
  <input name="os" class="form-control"
         value="<?= htmlspecialchars($p['os']) ?>"
         placeholder="Windows / Linux / Android">
</div>

<div class="mb-3">
  <label>Camera</label>
  <input name="camera" class="form-control"
         value="<?= htmlspecialchars($p['camera']) ?>"
         placeholder="HD / 13 MP">
</div>
<?php endif; ?>

<!-- ================= KITCHEN & HOME ================= -->
<?php if($selectedCategory == 'Kitchen & Home'): ?>
<div class="mb-3">
  <label>Brand</label>
  <input name="hk_brand" class="form-control" value="<?= $p['hk_brand'] ?>">
</div>

<div class="mb-3">
  <label>Sub Category</label>
  <input name="hk_subcategory" class="form-control" value="<?= $p['hk_subcategory'] ?>">
</div>

<div class="mb-3">
  <label>Features</label>
  <input name="features" class="form-control" value="<?= $p['features'] ?>">
</div>
<?php endif; ?>

<button name="update" class="btn btn-success">Update Product</button>
<a href="products.php" class="btn btn-secondary">Back</a>

</form>

</body>
</html>
