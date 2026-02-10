<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

include "navbar.php";
include "dbconne.php";


$id = (int)($_GET['id'] ?? 0);
if($id <= 0){
  die("Invalid Product");
}

/* FETCH PRODUCT */
$p = $conn->query("
  SELECT * FROM product 
  WHERE product_id=$id AND status=1
")->fetch_assoc();

if(!$p){
  die("Product not found");
}

/* EXTRA IMAGES */
$imgs = $conn->query("
  SELECT * FROM product_images 
  WHERE product_id=$id
");

/* RATINGS */
$rating = $conn->query("
  SELECT AVG(rating) avg_rating, COUNT(*) total 
  FROM reviews WHERE product_id=$id
")->fetch_assoc();

/* REVIEWS */
$reviews = $conn->query("
  SELECT * FROM reviews 
  WHERE product_id=$id ORDER BY id DESC
");

/* RELATED PRODUCTS */
$related = $conn->query("
  SELECT * FROM product 
  WHERE category='{$p['category']}'
  AND product_id!=$id 
  LIMIT 4
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($p['name']) ?></title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<style>
body{background:#f1f3f6;font-family:'Segoe UI'}
.box{background:#fff;border-radius:14px;padding:20px}
.main-img{height:420px;object-fit:contain;width:100%}
.thumb img{width:70px;height:70px;border:1px solid #ddd;cursor:pointer;padding:5px}
.price{font-size:32px;font-weight:800}
.cut{text-decoration:line-through;color:#777}
.offer{color:#388e3c;font-weight:700}
.rating{background:#388e3c;color:#fff;padding:4px 10px;border-radius:6px}
.sticky{position:fixed;bottom:0;left:0;width:100%;background:#fff;padding:10px;border-top:1px solid #ddd}
</style>
</head>

<body>
<!-- card presentation -->
 
<div class="container my-4 mb-5">
<div class="row g-4">

<!-- LEFT IMAGES -->
<div class="col-md-5">
  <div class="box text-center">
    <img id="mainImg" src="uploads/<?= $p['image'] ?>" class="main-img">

    <div class="d-flex justify-content-center gap-2 mt-3">
      <?php while($i=$imgs->fetch_assoc()): ?>
        <img src="uploads/<?= $i['image'] ?>" onclick="changeImg(this.src)">
      <?php endwhile; ?>
    </div>
  </div>
</div>

<!-- RIGHT DETAILS -->
<div class="col-md-7">
<div class="box">

<h4><?= htmlspecialchars($p['name']) ?></h4>

<div class="mb-2">
  <?php if(($rating['total'] ?? 0) > 0): ?>
  <span class="rating">
    <?= number_format($rating['avg_rating'], 1) ?> ★
  </span>
<?php else: ?>
  <span class="text-muted">No ratings yet</span>
<?php endif; ?>

  <small class="text-muted ms-2"><?= $rating['total'] ?> Ratings</small>
</div>

<div class="price">
₹<?= $p['price'] ?>
<?php if(!empty($p['old_price'])): ?>
  <span class="cut ms-2">₹<?= $p['old_price'] ?></span>
  <span class="offer ms-2">
    <?= round(100 - ($p['price'] / $p['old_price'] * 100)) ?>% OFF
  </span>
<?php endif; ?>
</div>

<p class="offer">Special Price</p>

<hr>

<?= htmlspecialchars($p['brand'] ?? '') ?>


<div class="d-flex gap-3">
  <button class="btn btn-warning w-50" onclick="addCart(<?= $id ?>)">ADD TO CART</button>

 <a href="<?= isset($_SESSION['user_id']) ? 'qr.php?id='.$id : 'userlogin.php' ?>"
   onclick="<?php if(!isset($_SESSION['user_id'])): ?>alert('Please login first to buy this product')<?php endif; ?>"
   class="btn btn-success w-50">
   BUY NOW
</a>

</div>

<hr>
<!-- ================= PRODUCT DETAILS ================= -->
<div class="box mt-4">
<h5>Product Details</h5>

<table class="table table-borderless">

<!-- BRAND -->
<?php if(!empty($p['brand'])): ?>
<tr>
  <td class="text-muted">Brand</td>
  <td><b><?= htmlspecialchars($p['brand']) ?></b></td>
</tr>
<?php endif; ?>

<!-- MOBILE CATEGORY DETAILS -->
<?php if($p['category'] === 'Mobile'): ?>

<?php if(!empty($p['ram'])): ?>
<tr>
  <td class="text-muted">RAM</td>
  <td><?= htmlspecialchars($p['ram']) ?></td>
</tr>
<?php endif; ?>

<?php if(!empty($p['storage'])): ?>
<tr>
  <td class="text-muted">Internal Storage</td>
  <td><?= htmlspecialchars($p['storage']) ?></td>
</tr>
<?php endif; ?>

<?php if(!empty($p['battery'])): ?>
<tr>
  <td class="text-muted">Battery Capacity</td>
  <td><?= htmlspecialchars($p['battery']) ?></td>
</tr>
<?php endif; ?>

<?php if(!empty($p['network'])): ?>
<tr>
  <td class="text-muted">Network Type</td>
  <td><?= htmlspecialchars($p['network']) ?></td>
</tr>
<?php endif; ?>

<?php endif; ?>

<!-- PRICE -->
<tr>
  <td class="text-muted">Price</td>
  <td class="fw-bold">₹<?= $p['price'] ?></td>
</tr>

</table>
</div>

<hr>
<!-- ================================================== -->
<!-- specification -->
 <!-- ================= SPECIFICATIONS ================= -->
<div class="box mt-4">
  <h5 class="mb-3">Specifications</h5>

  <table class="table table-bordered align-middle">
    <tbody>

      <!-- COMMON -->
      <tr>
        <td width="35%" class="text-muted">Brand</td>
        <td><b><?= htmlspecialchars($p['brand']) ?></b></td>
      </tr>

      <tr>
        <td class="text-muted">Category</td>
        <td><?= htmlspecialchars($p['category']) ?></td>
      </tr>

      <!-- ================= MOBILE ================= -->
      <?php if($p['category'] === 'Mobile'): ?>

      <tr>
        <td class="text-muted">RAM</td>
        <td><?= htmlspecialchars($p['ram']) ?></td>
      </tr>

      <tr>
        <td class="text-muted">Internal Storage</td>
        <td><?= htmlspecialchars($p['storage']) ?></td>
      </tr>

      <tr>
        <td class="text-muted">Battery Capacity</td>
        <td><?= htmlspecialchars($p['battery']) ?></td>
      </tr>

      <tr>
        <td class="text-muted">Network Type</td>
        <td><?= htmlspecialchars($p['network']) ?></td>
      </tr>

      <tr>
        <td class="text-muted">Camera</td>
        <td><?= htmlspecialchars($p['camera']) ?></td>
      </tr>

      <tr>
        <td class="text-muted">Operating System</td>
        <td><?= htmlspecialchars($p['os']) ?></td>
      </tr>

      <?php endif; ?>

      <!-- ================= FASHION ================= -->
      <?php if($p['category'] === 'Fashion'): ?>

      <tr>
        <td class="text-muted">Color</td>
        <td><?= htmlspecialchars($p['color']) ?></td>
      </tr>

      <tr>
        <td class="text-muted">Size</td>
        <td><?= htmlspecialchars($p['size']) ?></td>
      </tr>

      <tr>
        <td class="text-muted">Fabric</td>
        <td><?= htmlspecialchars($p['fabric']) ?></td>
      </tr>

      <tr>
        <td class="text-muted">Ideal For</td>
        <td><?= htmlspecialchars($p['ideal_for']) ?></td>
      </tr>

      <?php endif; ?>

      <!-- ================= ELECTRONICS ================= -->
      <?php if($p['category'] === 'Electronics'): ?>

      <tr>
        <td class="text-muted">Power Consumption</td>
        <td><?= htmlspecialchars($p['power']) ?></td>
      </tr>

      <tr>
        <td class="text-muted">Warranty</td>
        <td><?= htmlspecialchars($p['warranty']) ?></td>
      </tr>

      <tr>
        <td class="text-muted">Usage</td>
        <td><?= htmlspecialchars($p['usage']) ?></td>
      </tr>

      <?php endif; ?>

      <!-- ================= HOME ================= -->
      <?php if($p['category'] === 'Home'): ?>

      <tr>
        <td class="text-muted">Material</td>
        <td><?= htmlspecialchars($p['material']) ?></td>
      </tr>

      <tr>
        <td class="text-muted">Dimensions</td>
        <td><?= htmlspecialchars($p['dimensions']) ?></td>
      </tr>

      <tr>
        <td class="text-muted">Features</td>
        <td><?= htmlspecialchars($p['features']) ?></td>
      </tr>

      <?php endif; ?>

    </tbody>
  </table>
</div>
<!-- ================= END SPECIFICATIONS ================= -->

</div>
</div>
</div>

<!-- ================= REVIEWS ================= -->
<div class="box mt-4">

<?php if(isset($_SESSION['user_id'])): ?>
<h5>Rate this Product</h5>

<select id="rating" class="form-select mb-2">
  <option value="">Select Rating</option>
  <option value="5">⭐⭐⭐⭐⭐</option>
  <option value="4">⭐⭐⭐⭐</option>
  <option value="3">⭐⭐⭐</option>
  <option value="2">⭐⭐</option>
  <option value="1">⭐</option>
</select>

<textarea id="comment" class="form-control mb-2" placeholder="Write your review"></textarea>
<button class="btn btn-primary" onclick="submitReview()">Submit Review</button>

<?php else: ?>
<p class="text-muted">Login to rate this product</p>
<?php endif; ?>

<hr>

<?php if($reviews->num_rows==0): ?>
<p>No reviews yet</p>
<?php endif; ?>

<?php while($r=$reviews->fetch_assoc()): ?>
<p>
  <b><?= htmlspecialchars($r['user_name']) ?></b>
  <span class="text-warning">★<?= $r['rating'] ?></span><br>
  <?= htmlspecialchars($r['comment']) ?>
</p>
<hr>
<?php endwhile; ?>

</div>

<!-- ================= RELATED ================= -->
<div class="box mt-4">
<h5>Related Products</h5>
<div class="row g-3">

<?php while($rp=$related->fetch_assoc()): ?>
<div class="col-md-3 text-center">
<a href="product.php?id=<?= $rp['product_id'] ?>" class="text-decoration-none text-dark">
  <img src="uploads/<?= $rp['image'] ?>" class="img-fluid">
  <p><?= htmlspecialchars($rp['name']) ?></p>
  <b>₹<?= $rp['price'] ?></b>
</a>
</div>
<?php endwhile; ?>

</div>
</div>

</div>

<!-- STICKY CART -->
<div class="sticky d-flex gap-2">
<button class="btn btn-warning w-50" onclick="addCart(<?= $id ?>)">ADD TO CART</button>

<a href="<?= isset($_SESSION['user_id']) ? 'checkout.php?id='.$id : 'userlogin.php' ?>"
   onclick="<?php if(!isset($_SESSION['user_id'])): ?>alert('Please login first to buy this product')<?php endif; ?>"
   class="btn btn-success w-50">
   BUY NOW
</a>
</div>


<!-- javascript -->
<script>
const isLoggedIn = <?= isset($_SESSION['user_id']) ? 'true' : 'false' ?>;
</script>


<script>
function changeImg(src){
  document.getElementById("mainImg").src = src;
}


function addCart(id){
  if(!isLoggedIn){
    alert("Please login first to add this product to cart");
    window.location.href = "userlogin.php";
    return;
  }

  fetch("ajax-cart.php",{
    method:"POST",
    headers:{
      "Content-Type":"application/json"
    },
    body:JSON.stringify({id:id,qty:1})
  })
  .then(res => res.text())
  .then(data => {
    alert("Added to cart successfully");
  });
}



function submitReview(){
 let rating = document.getElementById("rating").value;
 let comment = document.getElementById("comment").value;

 if(!rating){ alert("Select rating"); return; }

 fetch("ajax-review.php",{
   method:"POST",
   body:JSON.stringify({
     product_id:<?= $id ?>,
     rating:rating,
     comment:comment
   })
 }).then(()=>{
   alert("Thanks for your review ❤️");
   location.reload();
 });
}
</script>

</body>
</html>
