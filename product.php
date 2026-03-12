<?php
if (session_status() === PHP_SESSION_NONE) session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

include "navbar.php";
include "dbconne.php";

// Validate product ID
$id = (int)($_GET['id'] ?? 0);
if($id <= 0) die("Invalid Product");

// Fetch product
$p = $conn->query("SELECT * FROM products WHERE product_id=$id AND status=1")->fetch_assoc();
if(!$p) die("Product not found");

// Fetch extra images
$imgs = $conn->query("SELECT * FROM product_images WHERE product_id=$id");

// Combine main image + extra images
$allImages = [];
if(!empty($p['image'])) $allImages[] = $p['image'];
$imgs->data_seek(0);
while($row = $imgs->fetch_assoc()) $allImages[] = $row['image'];

// Fetch ratings
$rating = $conn->query("SELECT AVG(rating) avg_rating, COUNT(*) total FROM reviews WHERE product_id=$id")->fetch_assoc();

// Fetch reviews with user name
$stmt = $conn->prepare("SELECT r.*, u.name AS user_name FROM reviews r LEFT JOIN users u ON r.user_id = u.id WHERE r.product_id = ? ORDER BY r.created_at DESC");
$stmt->bind_param("i", $id);
$stmt->execute();
$reviews = $stmt->get_result();

// Fetch related products
$related = $conn->query("SELECT * FROM products WHERE category='{$p['category']}' AND product_id!=$id LIMIT 4");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($p['name']) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<style>


/* Glass Effect Box */
.box{
  background: rgba(255,255,255,0.08);
  backdrop-filter: blur(20px);
  border-radius:20px;
  padding:25px;
  border:1px solid rgba(255,255,255,0.15);
  box-shadow:0 0 25px rgba(0,255,255,0.15);
  transition:0.4s ease;
}

.box:hover{
  transform: translateY(-6px);
  box-shadow:0 0 35px rgba(0,255,255,0.35);
}

/* Main Image */
.main-img{
  height:420px;
  object-fit:contain;
  width:100%;
  transition:0.5s ease;
}

.main-img:hover{
  transform: scale(1.05);
}

/* Thumbnails */
.thumbnail-img{
  width:65px;
  height:65px;
  border-radius:10px;
  transition:0.3s;
  border:1px solid rgba(255,255,255,0.2);
}

.thumbnail-img:hover{
  transform:scale(1.1);
  box-shadow:0 0 15px cyan;
}

/* Price */
.price{
  font-size:34px;
  font-weight:800;
  background: linear-gradient(90deg,#00f5ff,#00ff88);
  -webkit-background-clip:text;
  -webkit-text-fill-color:transparent;
}

/* Buttons */
.btn-warning{
  background: linear-gradient(90deg,#ffcc00,#ff8800);
  border:none;
  font-weight:600;
  transition:0.3s;
}

.btn-warning:hover{
  transform:scale(1.05);
  box-shadow:0 0 15px orange;
}

.btn-success{
  background: linear-gradient(90deg,#00ff88,#00c853);
  border:none;
  font-weight:600;
  transition:0.3s;
}

.btn-success:hover{
  transform:scale(1.05);
  box-shadow:0 0 15px #00ff88;
}

/* Rating Badge */
.rating{
  background:linear-gradient(90deg,#00ffcc,#0099ff);
  padding:6px 12px;
  border-radius:20px;
  font-weight:600;
  box-shadow:0 0 10px cyan;
}

/* Sticky Bottom */
.sticky{
  position:fixed;
  bottom:0;
  left:0;
  width:100%;
  background:rgba(0,0,0,0.6);
  backdrop-filter: blur(15px);
  padding:12px;
  border-top:1px solid rgba(255,255,255,0.2);
  z-index:999;
}

/* Fade Animation */
.fade-in{
  animation:fadeIn 1s ease-in-out;
}

@keyframes fadeIn{
  from{opacity:0; transform:translateY(20px);}
  to{opacity:1; transform:translateY(0);}
}


body{background:#f1f3f6;font-family:'Segoe UI'}
.box{background:#fff;border-radius:14px;padding:20px}
.main-img{height:420px;object-fit:contain;width:100%}
.thumbnail-img{width:60px;height:60px;border:1px solid #ddd;cursor:pointer;padding:3px;border-radius:6px}
.price{font-size:32px;font-weight:800}
.cut{text-decoration:line-through;color:#777}
.offer{color:#388e3c;font-weight:700}
.rating{background:#388e3c;color:#fff;padding:4px 10px;border-radius:6px}
.sticky{position:fixed;bottom:0;left:0;width:100%;background:#fff;padding:10px;border-top:1px solid #ddd;z-index:999}
</style>
</head>
<body>

<div class="container my-4 mb-5">
<div class="row g-4">

<!-- LEFT: Images -->
<div class="col-md-5">
  <div class="box text-center">

    <!-- ===== Main Carousel / Main Images ===== -->
    <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-inner">
        <?php foreach($allImages as $index => $img): ?>
          <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
            <!-- Main image -->
            <img src="uploads/<?= htmlspecialchars($img) ?>" class="d-block w-100 main-img"
                 data-bs-toggle="modal" data-bs-target="#imageModal"
                 data-img="uploads/<?= htmlspecialchars($img) ?>"
                 data-caption="Product Image <?= $index+1 ?>">
          </div>
        <?php endforeach; ?>
      </div>

      <!-- Carousel controls -->
      <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
      </button>
    </div>
    <!-- ===== End of Main Carousel ===== -->

    <!-- ===== Thumbnails / Sub Images ===== -->
    <div class="d-flex gap-2 mt-3  justify-content-center">
      <?php foreach($allImages as $i => $img): ?>
        <img src="uploads/<?= htmlspecialchars($img) ?>" 
             class="thumbnail-img" 
             style="cursor:pointer; max-height:60px; object-fit:cover;"
             data-bs-target="#productCarousel" 
             data-bs-slide-to="<?= $i ?>"
             data-bs-toggle="tooltip"
             title="Click to view Image <?= $i+1 ?>">
      <?php endforeach; ?>
    </div>
    <!-- ===== End of Thumbnails ===== -->

  </div>
</div>

<!-- ===== Modal for Image Zoom ===== -->
<div class="modal fade" id="imageModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-body p-0">
        <img id="modalImg" class="w-100" src="">
        <p id="modalCaption" class="text-center mt-2"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
// ===== Modal functionality for main images =====
var modalImg = document.getElementById('modalImg');
var modalCaption = document.getElementById('modalCaption');

document.querySelectorAll('#productCarousel .main-img').forEach(function(img){
    img.addEventListener('click', function(){
        modalImg.src = this.dataset.img;
        modalCaption.textContent = this.dataset.caption;
    });
});
// ===== End of Modal script =====
</script>

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
  <td><?= htmlspecialchars($p['camera'] ?? '') ?></td>
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
  <td><?= htmlspecialchars($p['ideal_for'] ?? '') ?></td>
</tr>
 <!-- ================= FASHION SPECIFICATIONS ================= -->

<tr>
  <td class="text-muted">Gender</td>
  <td><?= htmlspecialchars($p['gender'] ?? '') ?></td>
</tr>

<tr>
  <td class="text-muted">Brand</td>
  <td><?= htmlspecialchars($p['brand'] ?? '') ?></td>
</tr>

<tr>
  <td class="text-muted">Size</td>
  <td><?= htmlspecialchars($p['size'] ?? '') ?></td>
</tr>

<tr>
  <td class="text-muted">Color</td>
  <td><?= htmlspecialchars($p['color'] ?? '') ?></td>
</tr>

<tr>
  <td class="text-muted">Fabric</td>
  <td><?= htmlspecialchars($p['fabric'] ?? '') ?></td>
</tr>

<tr>
  <td class="text-muted">Type</td>
  <td><?= htmlspecialchars($p['type'] ?? '') ?></td>
</tr>

<tr>
  <td class="text-muted">Occasion</td>
  <td><?= htmlspecialchars($p['occasion'] ?? '') ?></td>
</tr>

<tr>
  <td


      <?php endif; ?>

      <!-- ================= ELECTRONICS ================= -->
      <?php if($p['category'] === 'Electronics'): ?>

  <tr>
  <td class="text-muted">Power Consumption</td>
  <td><?= htmlspecialchars($p['power'] ?? '') ?></td>
</tr>

<tr>
  <td class="text-muted">Warranty</td>
  <td><?= htmlspecialchars($p['warranty'] ?? '') ?></td>
</tr>

<tr>
  <td class="text-muted">Usage</td>
  <td><?= htmlspecialchars($p['usage'] ?? '') ?></td>
</tr>

<!-- ================= LAPTOP SPECIFICATIONS ================= -->

<tr>
  <td class="text-muted">Brand</td>
  <td><?= htmlspecialchars($p['brand'] ?? '') ?></td>
</tr>

<tr>
  <td class="text-muted">Processor</td>
  <td><?= htmlspecialchars($p['processor'] ?? '') ?></td>
</tr>

<tr>
  <td class="text-muted">Processor Generation</td>
  <td><?= htmlspecialchars($p['processor_gen'] ?? '') ?></td>
</tr>

<tr>
  <td class="text-muted">Processor Brand</td>
  <td><?= htmlspecialchars($p['processor_brand'] ?? '') ?></td>
</tr>

<tr>
  <td class="text-muted">RAM Capacity</td>
  <td><?= htmlspecialchars($p['ram'] ?? '') ?></td>
</tr>

<tr>
  <td class="text-muted">RAM Type</td>
  <td><?= htmlspecialchars($p['ram_type'] ?? '') ?></td>
</tr>

<tr>
  <td class="text-muted">Storage Type</td>
  <td><?= htmlspecialchars($p['storage_type'] ?? '') ?></td>
</tr>

<tr>
  <td class="text-muted">SSD Capacity</td>
  <td><?= htmlspecialchars($p['ssd_capacity'] ?? '') ?></td>
</tr>

<tr>
  <td class="text-muted">Screen Size</td>
  <td><?= htmlspecialchars($p['screen_size'] ?? '') ?></td>
</tr>

<tr>
  <td class="text-muted">Laptop Type</td>
  <td><?= htmlspecialchars($p['laptop_type'] ?? '') ?></td>
</tr>

<tr>
  <td class="text-muted">Operating System</td>
  <td><?= htmlspecialchars($p['os'] ?? '') ?></td>
</tr>

<tr>
  <td class="text-muted">Graphics Processor</td>
  <td><?= htmlspecialchars($p['graphics'] ?? '') ?></td>
</tr>

<tr>
  <td class="text-muted">Graphics Memory</td>
  <td><?= htmlspecialchars($p['graphics_memory'] ?? '') ?></td>
</tr>

<tr>
  <td class="text-muted">Touch Screen</td>
  <td><?= htmlspecialchars($p['touch_screen'] ?? '') ?></td>
</tr>

<tr>
  <td class="text-muted">Usage</td>
  <td><?= htmlspecialchars($p['usage'] ?? '') ?></td>
</tr>

<tr>
  <td class="text-muted">Features</td>
  <td><?= htmlspecialchars($p['features'] ?? '') ?></td>
</tr>

<tr>
  <td class="text-muted">Discount</td>
  <td><?= htmlspecialchars($p['discount'] ?? '') ?></td>
</tr>

<tr>
  <td class="text-muted">Offers</td>
  <td><?= htmlspecialchars($p['offers'] ?? '') ?></td>
</tr>

      

      <?php endif; ?>

      <!-- ================= HOME ================= -->
      <?php if($p['category'] === 'Home & Kitchen'): ?>

      <tr>
  <td class="text-muted">Material</td>
  <td><?= htmlspecialchars($p['material'] ?? '') ?></td>
</tr>

<tr>
  <td class="text-muted">Dimensions</td>
  <td><?= htmlspecialchars($p['dimensions'] ?? '') ?></td>
</tr>

      <tr>
        <td class="text-muted">Features</td>
        <td><?= htmlspecialchars($p['features']) ?></td>
      </tr>
      <tr>
  <td class="text-muted">Category</td>
  <td><?= htmlspecialchars($p['category'] ?? '') ?></td>
</tr>

<tr>
  <td class="text-muted">Sub Category</td>
  <td><?= htmlspecialchars($p['sub_category'] ?? '') ?></td>
</tr>

<tr>
  <td class="text-muted">Brand</td>
  <td><?= htmlspecialchars($p['brand'] ?? '') ?></td>
</tr>

<tr>
  <td class="text-muted">Offers</td>
  <td><?= htmlspecialchars($p['offers'] ?? '') ?></td>
</tr>

<tr>
  <td class="text-muted">Discount</td>
  <td><?= htmlspecialchars($p['discount'] ?? '') ?></td>
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
<?php if($reviews->num_rows == 0): ?>
<p>No reviews yet</p>
<?php endif; ?>

<?php while($r = $reviews->fetch_assoc()): ?>
<p>

  <b><?= htmlspecialchars($r['user_name'] ?? 'Unknown User') ?></b>
  <br>-

  <span class="text-warning">
    <?= str_repeat('★', (int)$r['rating']) . str_repeat('☆', 5 - (int)$r['rating']) ?>
  </span>
  <br>

  <?= htmlspecialchars($r['comment']) ?>
  <br>

  <small class="text-muted">
    <?= date('d M Y', strtotime($r['created_at'])) ?>
  </small>

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

<!-- animation -->
 <script>
const canvas = document.getElementById("particles");
const ctx = canvas.getContext("2d");

canvas.width = window.innerWidth;
canvas.height = window.innerHeight;

let particlesArray = [];

class Particle{
  constructor(){
    this.x = Math.random() * canvas.width;
    this.y = Math.random() * canvas.height;
    this.size = Math.random() * 2;
    this.speedX = (Math.random() - 0.5) * 0.5;
    this.speedY = (Math.random() - 0.5) * 0.5;
  }
  update(){
    this.x += this.speedX;
    this.y += this.speedY;
  }
  draw(){
    ctx.fillStyle = "rgba(255,255,255,0.5)";
    ctx.beginPath();
    ctx.arc(this.x,this.y,this.size,0,Math.PI*2);
    ctx.fill();
  }
}

function init(){
  particlesArray = [];
  for(let i=0;i<120;i++){
    particlesArray.push(new Particle());
  }
}

function animate(){
  ctx.clearRect(0,0,canvas.width,canvas.height);
  for(let i=0;i<particlesArray.length;i++){
    particlesArray[i].update();
    particlesArray[i].draw();
  }
  requestAnimationFrame(animate);
}

init();
animate();

window.addEventListener("resize",()=>{
  canvas.width = window.innerWidth;
  canvas.height = window.innerHeight;
  init();
});
</script>

</body>
</html>
