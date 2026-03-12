<?php
session_start();
if(!isset($_SESSION['admin'])){
  header("Location: login.php");
  exit;
}
include "dbconne.php";

$selectedCategory = $_GET['category'] ?? '';
$selectedCategory = trim($selectedCategory);

/* FIX broken & case */
if ($selectedCategory === 'Kitchen') {
  $selectedCategory = 'Home & Kitchen';
}

if(isset($_POST['add_product'])){

  function esc($v){
    return htmlspecialchars(trim($v));
  }

  $name     = esc($_POST['name']);
  $price    = esc($_POST['price']);
  $category = esc($selectedCategory);

  $brand    = esc($_POST['brand'] ?? '');
  $ram      = esc($_POST['ram'] ?? '');
  $storage  = esc($_POST['storage'] ?? '');
  $battery  = esc($_POST['battery'] ?? '');
  $network  = esc($_POST['network'] ?? '');
  $discount = esc($_POST['discount'] ?? '');
  $gender   = esc($_POST['gender'] ?? '');
  $rating   = esc($_POST['rating'] ?? '');
  $size     = esc($_POST['size'] ?? '');
  $color    = esc($_POST['color'] ?? '');
  $occasion = esc($_POST['occasion'] ?? '');
  $fabric   = esc($_POST['fabric'] ?? '');
  $type     = esc($_POST['type'] ?? '');
  $offers   = esc($_POST['offers'] ?? '');
  $new_arrival = isset($_POST['new_arrival']) ? 1 : 0;

  $processor      = esc($_POST['processor'] ?? '');
  $processor_gen  = esc($_POST['processor_gen'] ?? '');
  $processor_brand= esc($_POST['processor_brand'] ?? '');
  $ram_type       = esc($_POST['ram_type'] ?? '');
  $storage_type   = esc($_POST['storage_type'] ?? '');
  $ssd_capacity   = esc($_POST['ssd_capacity'] ?? '');
  $hdd_capacity   = esc($_POST['hdd_capacity'] ?? '');
  $screen_size    = esc($_POST['screen_size'] ?? '');
  $laptop_type    = esc($_POST['laptop_type'] ?? '');
  $os             = esc($_POST['os'] ?? '');
  $graphics       = esc($_POST['graphics'] ?? '');
  $graphics_memory= esc($_POST['graphics_memory'] ?? '');
  $touch_screen   = esc($_POST['touch_screen'] ?? '');
  $usage          = esc($_POST['usage'] ?? '');
  $features       = esc($_POST['features'] ?? '');

  $hk_category    = esc($_POST['hk_category'] ?? '');
  $hk_subcategory = esc($_POST['hk_subcategory'] ?? '');
  $hk_brand       = esc($_POST['hk_brand'] ?? '');
  $hk_rating      = esc($_POST['hk_rating'] ?? '');
  $hk_offer       = esc($_POST['hk_offer'] ?? '');
  $hk_discount    = esc($_POST['hk_discount'] ?? '');

  // MAIN IMAGE
  $image = time().'_'.$_FILES['image']['name'];
  move_uploaded_file($_FILES['image']['tmp_name'], "uploads/".$image);

  // INSERT MAIN PRODUCT
  $conn->query("INSERT INTO products SET
    name='$name',
    price='$price',
    category='$category',
    brand='$brand',
    ram='$ram',
    storage='$storage',
    battery='$battery',
    network='$network',
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
    processor='$processor',
    processor_gen='$processor_gen',
    processor_brand='$processor_brand',
    ram_type='$ram_type',
    storage_type='$storage_type',
    ssd_capacity='$ssd_capacity',
    hdd_capacity='$hdd_capacity',
    screen_size='$screen_size',
    laptop_type='$laptop_type',
    os='$os',
    graphics='$graphics',
    graphics_memory='$graphics_memory',
    touch_screen='$touch_screen',
    `usage`='$usage',
    features='$features',
    hk_category='$hk_category',
    hk_subcategory='$hk_subcategory',
    hk_brand='$hk_brand',
    hk_rating='$hk_rating',
    hk_offer='$hk_offer',
    hk_discount='$hk_discount',
    image='$image'
  ");

  // GET LAST INSERTED PRODUCT ID
  $productId = $conn->insert_id;

  // HANDLE SUB IMAGES
  if(isset($_FILES['sub_images'])){
    $files = $_FILES['sub_images'];
    for($i=0; $i<count($files['name']); $i++){
      $filename = time().'_'.$files['name'][$i];
      $tmpname = $files['tmp_name'][$i];
      $target = "uploads/".$filename;

if(move_uploaded_file($tmpname, $target)){
    $stmt = $conn->prepare("INSERT INTO product_images (product_id, image) VALUES (?, ?)");
    $stmt->bind_param("is", $productId, $filename); // ✅ SAVE ONLY FILENAME

        $stmt->execute();
      }
    }
  }

/* ✅ REDIRECT AFTER SUCCESS */
header("Location: products.php");

}
?>



<!DOCTYPE html>
<html>
<head>
<title>Add Product</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-4">

<h3>Add Product</h3>

<form method="post" enctype="multipart/form-data">

  <!-- CATEGORY -->
  <div class="mb-3">
    <label>Category</label>
    <input type="text" name="category" class="form-control"
           value="<?= htmlspecialchars($selectedCategory) ?>" readonly>
  </div>

  <!-- COMMON FIELDS -->
  <div class="mb-3">
    <label>Product Name</label>
    <input type="text" name="name" class="form-control" required>
  </div>

  <div class="mb-3">
    <label>Price</label>
    <input type="text" name="price" class="form-control" required>
  </div>

  <!-- ================= MOBILE CATEGORY ================= -->
  <?php if($selectedCategory == 'Mobile'): ?>

  <div class="mb-3">
    <label>Brand</label>
    <select name="brand" class="form-control">
      <option>Apple</option>
      <option>Samsung</option>
      <option>Motorola</option>
      <option>Vivo</option>
      <option>Oppo</option>
      <option>Realme</option>
      <option>Redmi </option>
    </select>
  </div>

  <div class="mb-3">
    <label>RAM</label>
    <select name="ram" class="form-control">
      <option>4 GB</option>
      <option>6 GB</option>
      <option>8 GB & Above</option>
    </select>
  </div>

  <div class="mb-3">
    <label>Internal Storage</label>
    <select name="storage" class="form-control">
      <option>64 GB</option>
      <option>128 GB</option>
      <option>256 GB & Above</option>
    </select>
  </div>

  <div class="mb-3">
    <label>Battery Capacity</label>
    <select name="battery" class="form-control">
      <option>5000 - 5999 mAh</option>
      <option>6000 mAh & Above</option>
    </select>
  </div>

  <div class="mb-3">
    <label>Network Type</label>
    <select name="network" class="form-control">
      <option>4G</option>
      <option>5G</option>
    </select>
  </div>

  <div class="mb-3">
  <label>Camera</label>
  <select name="camera" class="form-control">
    <option>12 MP</option>
    <option>16 MP</option>
    <option>32 MP</option>
    <option>48 MP</option>
    <option>50 MP</option>
    <option>64 MP</option>
    <option>108 MP</option>
    <option>200 MP</option>
  </select>
</div>


<div class="mb-3">
  <label>Operating System</label>
  <select name="os" class="form-control">
    <option>Android</option>
    <option>iOS</option>
    <option>HarmonyOS</option>
    <option>Windows Mobile</option>
  </select>
</div>

  <div class="mb-3">
    <label>Discount</label>
    <select name="discount" class="form-control">
      <option>10%</option>
      <option>20%</option>
      <option>30%</option>
      <option>40%</option>
      <option>50%</option>
    </select>
  </div>

  <?php endif; ?>

  <!-- ================= ELECTRONICS ================= -->
  <?php if($selectedCategory == 'Electronics'): ?>

<h5 class="mt-4">Laptop Specifications</h5>

<!-- BRAND -->
<div class="mb-3">
  <label>Brand</label>
  <input type="text" name="brand" class="form-control"
         placeholder="HP, Dell, Lenovo, Apple">
</div>

<!-- PROCESSOR -->
<div class="mb-3">
  <label>Processor</label>
  <select name="processor" class="form-control">
    <option>Core i3</option>
    <option>Core i5</option>
    <option>Core i7</option>
    <option>Core i9</option>
    <option>Ryzen 5 Quad Core</option>
    <option>Ryzen 7 Quad Core</option>
  </select>
</div>

<!-- PROCESSOR GENERATION -->
<div class="mb-3">
  <label>Processor Generation</label>
  <select name="processor_gen" class="form-control">
    <option>14th Gen</option>
    <option>13th Gen</option>
    <option>12th Gen</option>
    <option>11th Gen</option>
    <option>10th Gen</option>
    <option>9th Gen</option>
  </select>
</div>

<!-- PROCESSOR BRAND -->
<div class="mb-3">
  <label>Processor Brand</label>
  <select name="processor_brand" class="form-control">
    <option>Intel</option>
    <option>AMD</option>
    <option>Apple</option>
    <option>Qualcomm</option>
    <option>MediaTek</option>
  </select>
</div>

<!-- RAM -->
<div class="mb-3">
  <label>RAM Capacity</label>
  <select name="ram" class="form-control">
    <option>4 GB</option>
    <option>8 GB</option>
    <option>16 GB</option>
    <option>32 GB</option>
    <option>64 GB</option>
  </select>
</div>

<!-- RAM TYPE -->
<div class="mb-3">
  <label>RAM Type</label>
  <select name="ram_type" class="form-control">
    <option>DDR4</option>
    <option>DDR5</option>
    <option>LPDDR4X</option>
    <option>LPDDR5</option>
  </select>
</div>

<!-- STORAGE TYPE -->
<div class="mb-3">
  <label>Storage Type</label>
  <select name="storage_type" class="form-control">
    <option>SSD</option>
    <option>HDD</option>
    <option>eMMC</option>
  </select>
</div>

<!-- SSD CAPACITY -->
<div class="mb-3">
  <label>SSD Capacity</label>
  <select name="ssd_capacity" class="form-control">
    <option>256 GB</option>
    <option>512 GB</option>
    <option>1 TB</option>
    <option>2 TB</option>
  </select>
</div>

<!-- SCREEN SIZE -->
<div class="mb-3">
  <label>Screen Size</label>
  <select name="screen_size" class="form-control">
    <option>14 inch</option>
    <option>15.6 inch</option>
    <option>16 inch</option>
    <option>13 inch</option>
  </select>
</div>

<!-- LAPTOP TYPE -->
<div class="mb-3">
  <label>Laptop Type</label>
  <select name="laptop_type" class="form-control">
    <option>Gaming Laptop</option>
    <option>Thin and Light Laptop</option>
    <option>Business Laptop</option>
    <option>Creator Laptop</option>
    <option>2 in 1 Laptop</option>
  </select>
</div>

<!-- OPERATING SYSTEM -->
<div class="mb-3">
  <label>Operating System</label>
  <select name="os" class="form-control">
    <option>Windows 11</option>
    <option>Windows 10</option>
    <option>Mac OS</option>
    <option>Linux</option>
    <option>Chrome OS</option>
  </select>
</div>

<!-- GRAPHICS -->
<div class="mb-3">
  <label>Graphics Processor</label>
  <select name="graphics" class="form-control">
    <option>Intel Integrated</option>
    <option>NVIDIA GeForce RTX</option>
    <option>NVIDIA GTX</option>
    <option>AMD Radeon</option>
  </select>
</div>

<!-- GRAPHICS MEMORY -->
<div class="mb-3">
  <label>Graphics Memory</label>
  <select name="graphics_memory" class="form-control">
    <option>Integrated</option>
    <option>2 GB</option>
    <option>4 GB</option>
    <option>6 GB</option>
    <option>8 GB</option>
  </select>
</div>

<!-- TOUCH SCREEN -->
<div class="mb-3">
  <label>Touch Screen</label>
  <select name="touch_screen" class="form-control">
    <option>No</option>
    <option>Yes</option>
  </select>
</div>

<!-- USAGE -->
<div class="mb-3">
  <label>Usage</label>
  <select name="usage" class="form-control">
    <option>Gaming</option>
    <option>Business</option>
    <option>Student</option>
    <option>Home / Office</option>
    <option>Content Creation</option>
  </select>
</div>

<!-- FEATURES -->
<div class="mb-3">
  <label>Features</label>
  <select name="features" class="form-control">
    <option>Backlit Keyboard</option>
    <option>MS Office</option>
    <option>Full HD Display</option>
    <option>No Optical Disk Drive</option>
  </select>
</div>

<!-- DISCOUNT -->
<div class="mb-3">
  <label>Discount</label>
  <select name="discount" class="form-control">
    <option>10% or more</option>
    <option>20% or more</option>
    <option>30% or more</option>
    <option>40% or more</option>
    <option>50% or more</option>
  </select>
</div>

<!-- OFFERS -->
<div class="mb-3">
  <label>Offers</label>
  <select name="offers" class="form-control">
    <option>Special Price</option>
    <option>No Cost EMI</option>
  </select>
</div>

<!-- RATING -->
<div class="mb-3">
  <label>Customer Rating</label>
  <select name="rating" class="form-control">
    <option>4★ & above</option>
    <option>3★ & above</option>
    <option>2★ & above</option>
    <option>1★ & above</option>
  </select>
</div>

<?php endif; ?>


  <!-- ================= KITCHEN & HOME ================= -->
  <?php if($selectedCategory == 'Home & Kitchen'): ?>

<h5 class="mt-4">Home & Kitchen Details</h5>

<!-- CATEGORY -->
<div class="mb-3">
  <label>Category</label>
  <select name="hk_category" class="form-control">
    <option>Home & Kitchen</option>
  </select>
</div>

<!-- SUB CATEGORY -->
<div class="mb-3">
  <label>Sub Category</label>
  <select name="hk_subcategory" class="form-control">
    <option>Kitchen Appliances</option>
    <option>Weighing Scales</option>
  </select>
</div>

<!-- BRAND -->
<div class="mb-3">
  <label>Brand</label>
  <input type="text" name="hk_brand"
         class="form-control"
         placeholder="A G Enterprises, AADGEX, A-man Traders">
</div>

<!-- CUSTOMER RATING -->
<div class="mb-3">
  <label>Customer Rating</label>
  <select name="hk_rating" class="form-control">
    <option>4★ & above</option>
    <option>3★ & above</option>
  </select>
</div>

<!-- OFFERS -->
<div class="mb-3">
  <label>Offers</label>
  <select name="hk_offer" class="form-control">
    <option>Special Price</option>
    <option>Buy More, Save More</option>
  </select>
</div>

<!-- DISCOUNT -->
<div class="mb-3">
  <label>Discount</label>
  <select name="hk_discount" class="form-control">
    <option value="">Select Discount</option>
    <option>10% or more</option>
    <option>20% or more</option>
    <option>30% or more</option>
    <option>40% or more</option>
    <option>50% or more</option>
  </select>
</div>

<?php endif; ?>


  <!-- ================= FASHION ================= -->
  <?php if($selectedCategory == 'Fashion'): ?>

<!-- GENDER -->
<div class="mb-3">
  <label>Gender</label>
  <select name="gender" class="form-control">
    <option value="">Select Gender</option>
    <option>Women</option>
    <option>Men</option>
    <option>Kids</option>
  </select>
</div>

<!-- DISCOUNT -->
<div class="mb-3">
  <label>Discount</label>
  <select name="discount" class="form-control">
    <option value="">Select Discount</option>
    <option>30% or more</option>
    <option>40% or more</option>
    <option>50% or more</option>
    <option>60% or more</option>
    <option>70% or more</option>
  </select>
</div>

<!-- CUSTOMER RATING -->
<div class="mb-3">
  <label>Customer Rating</label>
  <select name="rating" class="form-control">
    <option value="">Select Rating</option>
    <option>3★ & above</option>
    <option>4★ & above</option>
  </select>
</div>



 <!-- brand -->
 <div class="mb-3">
    <label>Brand</label>
    <select name="brand" class="form-select" required>
      <option value="">Select Brand</option>

      <option>AADESH ENTERPRISE</option>
      <option>AADITRY FASHION</option>
      <option>A Asma dresses</option>
      <option>A Flash</option>
      <option>A I Fashion</option>
      <option>A J Fashion Hub</option>
      <option>A K Daller Fashion</option>
      <option>A K collection</option>
      <option>A K FASHION</option>
      <option>A M Enterprises</option>
      <option>A MODERN FASHION</option>
      <option>A R Fashion</option>
      <option>A R J FASHION</option>
      <option>A R WORLD FASHION</option>
      <option>A S Creation</option>
      <option>A S fashion</option>
      <option>A to Z unique fashion</option>
      <option>24Hour Fashion</option>
      <option>3Buddy Fashion</option>
      <option>3colors</option>
      <option>3DTRENDS</option>
      <option>4K FASHION</option>
      <option>69 FASHION STREET</option>
      <option>7 CUTS</option>
      <option>7800 GROUP</option>
      <option>9&4fashion</option>
      <option>#</option>
      <option>1 Stop Fashion</option>
      <option>100LUCK</option>
      <option>12 O'Clock</option>
      <option>1DAYJEANS</option>
      <option>1stLov</option>
      <option>2 Pc Collection</option>
      <option>W</option>

    </select>
  </div>
</div>

<!-- SIZE -->
<div class="mb-3">
  <label>Size</label>
  <select name="size" class="form-control">

  <option value="">Select Size</option>
    <option>XS</option><option>S</option><option>M</option><option>L</option>
    <option>XL</option><option>2XL</option><option>3XL</option>
    <option>4XL</option><option>5XL</option><option>6XL</option>
    <option>7XL</option>
  </select>
</div>

<!-- COLOR -->
<div class="mb-3">
  <label>Color</label>
  <select name="color" class="form-control">
    <option value="">Select Color</option>
    <option>Pink</option>
    <option>Black</option>
    <option>Blue</option>
    <option>Multicolor</option>
    <option>White</option>
    <option>Maroon</option>
  </select>
</div>

<!-- FABRIC -->
<div class="mb-3">
  <label>Fabric</label>
  <select name="fabric" class="form-control">
    <option value="">Select Fabric</option>
    <option>Pure Cotton</option>
    <option>Cotton Blend</option>
    <option>Viscose Rayon</option>
    <option>Cotton Rayon</option>
    <option>Crepe</option>
    <option>Georgette</option>
  </select>
</div>

<!-- TYPE -->
<div class="mb-3">
  <label>Type</label>
  <select name="type" class="form-control">
    <option value="">Select Type</option>
    <option>Straight</option>
    <option>Anarkali</option>
    <option>A-line</option>
    <option>Flared</option>
    <option>Kurta, Trouser/Pant & Dupatta Set</option>
    <option>Kurta and Pant Set</option>
  </select>
</div>

<!-- OCCASION -->
<div class="mb-3">
  <label>Occasion</label>
  <select name="occasion" class="form-control">
    <option value="">Select Occasion</option>
    <option>Casual</option>
    <option>Festive</option>
    <option>Festive & Party</option>
    <option>Formal</option>
    <option>Wedding</option>
    <option>Ethnic</option>
  </select>
</div>

<!-- OFFERS -->
<div class="mb-3">
  <label>Offers</label>
  <select name="offers" class="form-control">
    <option value="">Select Offer</option>
    <option>10%</option>
    <option>20%</option>
    <option>30%</option>
    <option>40%</option>
    <option>50%</option>
  </select>
</div>

<!-- NEW ARRIVAL -->
<div class="form-check mb-3">
  <input type="checkbox" name="new_arrival" class="form-check-input">
  <label class="form-check-label">New Arrival</label>
</div>

<?php endif; ?>

  <!-- IMAGE -->
  <!-- MAIN IMAGE -->
<div class="mb-3">
  <label>Main Product Image</label>
  <input type="file" name="image" class="form-control" required>
</div>

<!-- SUB IMAGES -->
<div class="mb-3">
  <label>Sub Images (Multiple)</label>
  <input type="file" name="sub_images[]" class="form-control" multiple>
</div>


  <button name="add_product" class="btn btn-primary">Add Product</button>

</form>

</body>
</html>
