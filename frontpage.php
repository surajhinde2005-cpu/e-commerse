
<?php
session_start();
include "dbconne.php";
?>
<?php include "navbar.php"; ?>

<!-- search bar -->
<?php
$search = '';
if(isset($_GET['search'])){
  $search = trim($_GET['search']);
}

if($search != ''){
  $stmt = $conn->prepare(
    "SELECT * FROM product 
     WHERE status = 1 
     AND name LIKE ?"
  );
  $like = "%$search%";
  $stmt->bind_param("s", $like);
  $stmt->execute();
  $q = $stmt->get_result();
} else {
  $q = $conn->query("SELECT * FROM product WHERE status = 1");
}
?>






<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<style>
/* ===== RESET ===== */
*{
  margin:0;
  padding:0;
  box-sizing:border-box;
  font-family:"Segoe UI", Arial, sans-serif;

  /* ✨ smooth text & UI polish */
  -webkit-font-smoothing:antialiased;
  -moz-osx-font-smoothing:grayscale;

  /* ⚡ better touch & click feel */
  -webkit-tap-highlight-color:transparent;
}

html{
  scroll-behavior:smooth;

  /* 🌍 better rendering consistency */
  text-rendering:optimizeLegibility;
}


/* background */


/* ===== ANIMATED BACKGROUND ===== */
body{
  min-height:100vh;
  background-size:400% 400%;
  animation:bgMove 18s ease infinite;
  background-color: #1c92d2;

  /* ✨ smoother rendering */
  -webkit-font-smoothing:antialiased;
  overflow-x:hidden;
}

/* ===== BACKGROUND GRADIENT MOVE ===== */
@keyframes bgMove{
  0%{background-position:0% 50%}
  50%{background-position:100% 50%}
  100%{background-position:0% 50%}
}

/* ===== FADE-UP ANIMATION ===== */
@keyframes fadeUp{
  from{
    opacity:0;
    transform:translateY(40px);
  }
  to{
    opacity:1;
    transform:translateY(0);
  }
}

/* ===== NO UNDERLINE (cleaned, single definition) ===== */
.no-underline{
  text-decoration:none;
  color:inherit;
}

/* ===== NAVBAR ===== */
.navbar{
  background:linear-gradient(90deg,#0f2027,#203a43,#2c5364);
  color:#fff;
  padding:14px 60px;
  display:flex;
  justify-content:space-between;
  align-items:center;
  position:sticky;
  top:0;
  z-index:1000;

  /* ✨ depth + glass feel */
  box-shadow:0 10px 30px rgba(0,0,0,.35);
  backdrop-filter:blur(6px);

  animation:fadeUp .8s ease;
}

/* ===== NAVBAR LINKS ===== */
.navbar a{
  color:#fff;
  text-decoration:none;
  margin-left:25px;
  font-size:15px;
  font-weight:500;
  position:relative;
  transition:color .3s ease;
}

/* underline animation */
.navbar a::after{
  content:"";
  position:absolute;
  left:0;
  bottom:-6px;
  width:0;
  height:2px;
  background:#ffd814;
  border-radius:2px;
  transition:width .3s ease;
}

.navbar a:hover{
  color:#ffd814;
}

.navbar a:hover::after{
  width:100%;
}

/* ===== CATEGORY BAR ===== */
/* ===== CATEGORY BAR ===== */
.category-bar{
  background:#fff;
  display:flex;
  justify-content:flex-start;   /* RIGHT SIDE */
  align-items:center;
  gap:470px;                   /* space between items */
  padding:18px 30px;
  margin:20px;
  border-radius:18px;
  box-shadow:0 10px 30px rgba(0,0,0,.12);
}


/* ONLY category bar items */
.category-bar .category-item{
  display:flex;
  flex-direction:column;
  align-items:center;
  cursor:pointer;
  transition:transform .35s;
}

.category-bar .category-item img{
  width:64px;
  height:64px;
  object-fit:contain;
  transition:transform .35s;
}

.category-bar .category-item span{
  margin-top:8px;
  font-size:14px;
  font-weight:700;
  color:#212121;
  transition:color .35s;
}

.category-bar .category-item:hover{
  transform:translateY(-6px);
}

.category-bar .category-item:hover img{
  transform:scale(1.15);
}

.category-bar .category-item:hover span{
  color:#2874f0;
}

/* ===== HERO ===== */
/* ===== HERO ===== */
.hero{
  height:90vh;
  background:
    radial-gradient(circle at top,rgba(255,255,255,.08),transparent 60%),
    radial-gradient(circle at bottom,rgba(0,0,0,.25),transparent 60%),
    linear-gradient(135deg,#1c92d2,#0f2027);
  color:#fff;
  display:flex;
  align-items:center;
  justify-content:center;
  text-align:center;
  position:relative;
  overflow:hidden;

  animation:fadeUp .9s ease;
}

/* subtle floating light */
.hero::before{
  content:"";
  position:absolute;
  width:600px;
  height:600px;
  background:radial-gradient(circle,rgba(255,255,255,.12),transparent 70%);
  top:-200px;
  left:-200px;
  animation:floatGlow 12s ease-in-out infinite;
}

@keyframes floatGlow{
  0%,100%{transform:translate(0,0)}
  50%{transform:translate(120px,80px)}
}

.hero h1{
  font-size:60px;
  margin-bottom:15px;
  font-weight:900;
  letter-spacing:.5px;
  text-shadow:0 8px 30px rgba(0,0,0,.45);
}

.hero p{
  font-size:22px;
  opacity:.92;
  margin-bottom:30px;
  max-width:520px;
  margin-inline:auto;
}

.hero button{
  padding:15px 44px;
  font-size:18px;
  background:linear-gradient(90deg,#ffd814,#f7ca00);
  border:none;
  border-radius:30px;
  cursor:pointer;
  font-weight:800;
  transition:.4s ease;
  box-shadow:0 10px 30px rgba(0,0,0,.35);
}

.hero button:hover{
  transform:translateY(-5px) scale(1.06);
  box-shadow:0 18px 45px rgba(0,0,0,.55);
}

/* ===== FEATURES ===== */
.features{
  display:flex;
  justify-content:center;
  gap:30px;
  padding:80px 40px;
  background-color:#1c92d2;
  flex-wrap:wrap;
}

.feature{
  background:#fff;
  padding:35px;
  width:260px;
  text-align:center;
  border-radius:20px;
  box-shadow:0 14px 35px rgba(0,0,0,.12);
  transition:.4s ease;
  position:relative;
  overflow:hidden;
}

.feature::after{
  content:"";
  position:absolute;
  inset:0;
  background:linear-gradient(120deg,transparent,rgba(255,216,20,.18),transparent);
  opacity:0;
  transition:.4s;
}

.feature:hover::after{
  opacity:1;
}

.feature:hover{
  transform:translateY(-12px);
  box-shadow:0 22px 45px rgba(0,0,0,.2);
}

/* ===== PRODUCTS ===== */
.products{
  padding:70px;
  background-color: #1c92d2;  
}

.products h2{
  text-align:center;
  margin-bottom:45px;
  font-size:36px;
  font-weight:900;
  color:#111827;
}

/* grid refined */
.product-grid{
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
  gap:32px;
}

/* product card */
.card{
  background:#fff;
  padding:20px;
  text-align:center;
  border-radius:20px;
  box-shadow:0 14px 35px rgba(0,0,0,.15);
  transition:.4s ease;
  position:relative;
}

.card:hover{
  transform:translateY(-12px);
  box-shadow:0 24px 50px rgba(0,0,0,.25);
}

.card img{
  width:100%;
  height:150px;
  object-fit:cover;
  border-radius:14px;
  transition:.4s ease;
}

.card:hover img{
  transform:scale(1.05);
}

.card h4{
  margin:18px 0 12px;
  font-weight:700;
}

.card button{
  margin-top:10px;
  padding:12px;
  width:100%;
  background:linear-gradient(90deg,#ffd814,#f7ca00);
  border:none;
  border-radius:25px;
  cursor:pointer;
  font-weight:800;
  transition:.35s;
}

.card button:hover{
  transform:translateY(-2px);
  box-shadow:0 10px 25px rgba(0,0,0,.35);
}


/* category */

/* ===== MEGA MENU ===== */
.category-item{
  position:relative;
  
}

.mega-menu{
  position:absolute;
  top:110%;
  left:-120px;
  width:520px;
  background:#fff;
  padding:25px;
  display:grid;
  grid-template-columns:repeat(3,1fr);
  gap:20px;
  border-radius:18px;
  box-shadow:0 20px 60px rgba(0,0,0,.2);
  opacity:0;
  visibility:hidden;
  transform:translateY(20px);
  transition:.4s;
  z-index:999;
}

.category-item:hover .mega-menu{
  opacity:1;
  visibility:visible;
  transform:translateY(0);
}

.mega-col h4{
  font-size:14px;
  margin-bottom:10px;
  color:#2874f0;
}

.mega-col a{
  display:block;
  margin-bottom:8px;
  text-decoration:none;
  color:#333;
  font-size:13px;
}

.mega-col a:hover{
  color:#f08804;
}

/* ===== FOOTER ===== */
.footer{
  background:#172337;
  color:#fff;
  font-size:13px;
}

.footer-top{
  display:grid;
  grid-template-columns:repeat(4,1fr) 1px 2fr;
  gap:35px;
  padding:60px 80px;
}

.footer-col h4,
.footer-address h4{
  color:#878787;
  font-size:12px;
  margin-bottom:14px;
  text-transform:uppercase;
}

.footer-col a{
  display:block;
  color:#fff;
  text-decoration:none;
  margin-bottom:9px;
  transition:.3s;
}

.footer-col a:hover{
  color:#ffd814;
  transform:translateX(6px);
}

.footer-divider{
  background:#454d5e;
  width:1px;
}

.footer-bottom{
  border-top:1px solid #454d5e;
  display:flex;
  justify-content:space-around;
  align-items:center;
  padding:20px;
}

.footer-bottom span:hover{
  color:#ffd814;
  cursor:pointer;
}

/* ===== RESPONSIVE ===== */
@media(max-width:992px){
  .category-bar{
    overflow-x:auto;
    justify-content:flex-start;
    gap:25px;
  }

  .footer-top{
    grid-template-columns:1fr;
  }

  .footer-divider{
    display:none;
  }
}

/* mobile bar  */
/* product card */
.product-card{
  background:#fff;
  border-radius:18px;
  padding:12px;
  position:relative;
  box-shadow:0 10px 25px rgba(0,0,0,.08);
  transition:.35s ease;
  overflow:hidden;
}

.product-card:hover{
  transform:translateY(-8px);
  box-shadow:0 18px 40px rgba(0,0,0,.15);
}

/* image */
.img-wrap{
  display:block;
  border-radius:14px;
  overflow:hidden;
}

.img-wrap img{
  width:100%;
  height:220px;
  object-fit:cover;
  transition:.4s ease;
}

.product-card:hover img{
  transform:scale(1.08);
}

/* wishlist heart */
.card{
  position: relative;
  border-radius:18px;
  border:none;
  padding:12px;
  box-shadow:0 10px 25px rgba(0,0,0,.08);
  transition:.3s ease;
}

.card:hover{
  transform:translateY(-6px);
  box-shadow:0 18px 40px rgba(0,0,0,.15);
}

.card img{
  width:100%;
  height:220px;
  object-fit:cover;
  border-radius:14px;
}

/* wishlist */
.wishlist{
  position:absolute;
  top:12px;
  right:12px;
  width:36px;
  height:36px;
  background:#fff;
  border-radius:50%;
  display:flex;
  align-items:center;
  justify-content:center;
  cursor:pointer;
  font-size:18px;
  color:#999;
  box-shadow:0 4px 10px rgba(0,0,0,.2);
  transition:.3s;
  z-index:10;
}

.wishlist.active{
  background:#ff4d4d;
  color:#fff;
}

h5{
  margin-top:10px;
  font-size:16px;
  font-weight:600;
}

.price{
  color:#28a745;
  font-weight:700;
  font-size:17px;
}

.btn-cart{
  background:linear-gradient(135deg,#ff7a18,#ff3d00);
  border:none;
  color:#fff;
  font-weight:600;
}

.btn-cart:hover{
  opacity:.9;
  color:#fff;
}
/* NEW MOBILE LAYER HERE ADDED */
.product-row {
  display: flex;
  gap: 24px;
  justify-content: center;
  flex-wrap: wrap;        /* responsive */
}

/* EXACT SIZE YOU WANT */
.product-card {
  width: 18rem;           /* ⭐ exactly this */
  border-radius: 18px;
  position: relative;
  box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

/* image */
.product-card img {
  height: 220px;
  object-fit: contain;
  padding: 15px;
}

/* wishlist */
.wishlist {
  position: absolute;
  top: 10px;
  right: 10px;
  background: #fff;
  padding: 6px 8px;
  border-radius: 50%;
  cursor: pointer;
}

.wishlist.active {
  color: red;
}

/* h5 for perticular product */
h5.section-title{
  text-align: center;
  color: #fff;
  font-size: 28px;
  font-weight: 700;
  padding: 12px 35px;
  display: inline-block;
  margin: 25px auto;
  border-radius: 40px;
  border: 2px solid rgba(255,255,255,0.6);
  box-shadow: 
    0 0 10px rgba(255,255,255,0.4),
    0 0 20px rgba(40,116,240,0.6);
  background: rgba(0,0,0,0.25);
}


</style>
</head>

<body>

<!-- navbar for frontpage -->

<!-- NAVBAR -->
<!-- NAVBAR -->




<!-- tool bar -->

<div class="category-bar">
  <div class="category-item">
    <img src="https://rukminim2.flixcart.com/fk-p-flap/64/64/image/22fddf3c7da4c4f4.png" />
    <span><a href="mobile.php" class="no-underline">  Mobiles</a></span>
  </div>

  <div class="category-item">
    <img src="https://rukminim2.flixcart.com/fk-p-flap/64/64/image/0d75b34f7d8fbcb3.png" />
    <span><a href="fashion.php" class="no-underline">Fashion</a></span>
  </div>

  <div class="category-item">
    <img src="https://rukminim2.flixcart.com/fk-p-flap/64/64/image/69c6589653afdb9a.png" />
    <span><a href="electronics.php" class="no-underline">Electronics</a></span>
  </div>

  <div class="category-item">
    <img src="https://rukminim2.flixcart.com/fk-p-flap/64/64/image/71050627a56b4693.png" />
    <span><a href="home.php" class="no-underline">Home & Kitchen</a></span>
  </div>
</div>

<!-- HERO -->
<section class="hero">
    <div>
        <h1 class="animate__animated animate__zoomIn   animate__infinite">
  Welcome to ShopNow
</h1>
        <p  class="animate__animated animate__zoomIn   animate__infinite">Your one-stop destination for online shopping</p>
        <button  class="animate__animated animate__zoomIn">Shop Now</button>
    </div>
</section>



<!-- mobile -->
<div style="text-align:center;">
  <h5 class="section-title">Mobiles</h5>
</div>


<div class="animate__animated animate__zoomIn">
  <div class="product-section">
    <div class="container">
      <div class="product-row">

        <?php
        $q = $conn->query("SELECT * FROM product WHERE status=1 AND category='Mobile'");
        while($p = $q->fetch_assoc()):
        ?>

        <div class="card product-card">
          <!-- wishlist -->
          <div class="wishlist" onclick="toggleWish(this)">❤</div>

          <a href="product.php?id=<?= $p['product_id'] ?>" class="no-underline">
            <img src="uploads/<?= $p['image'] ?>" class="card-img-top">
          </a>

          <div class="card-body text-center">
            <h6><?= $p['name'] ?></h6>
            <p>From <b>₹<?= $p['price'] ?></b></p>
          </div>
        </div>

        <?php endwhile; ?>

      </div>
    </div>
  </div>
</div>

<br>



<div style="text-align:center;">
  <h5 class="section-title">Fashion</h5>
</div>


<div class="animate__animated animate__zoomIn">
  <div class="product-section">
    <div class="container">
      <div class="product-row">

        <?php
        $q = $conn->query(
  "SELECT * FROM product 
   WHERE status=1 AND category='fashion'"
);
while($p = $q->fetch_assoc()):
        ?>

        <div class="card product-card">
          <!-- wishlist -->
          <div class="wishlist" onclick="toggleWish(this)">❤</div>

          <a href="product.php?id=<?= $p['product_id'] ?>" class="no-underline">
            <img src="uploads/<?= $p['image'] ?>" class="card-img-top">
          </a>

          <div class="card-body text-center">
            <h6><?= $p['name'] ?></h6>
            <p>From <b>₹<?= $p['price'] ?></b></p>
          </div>
        </div>

        <?php endwhile; ?>

      </div>
    </div>
  </div>
</div>

<br>

<div style="text-align:center;">
  <h5 class="section-title">Electronics</h5>
</div>


<div class="animate__animated animate__zoomIn">
  <div class="product-section">
    <div class="container">
      <div class="product-row">

        <?php
              $q = $conn->query(
  "SELECT * FROM product 
   WHERE status=1 AND category='electronics'"
);
while($p = $q->fetch_assoc()):
        ?>

        <div class="card product-card">
          <!-- wishlist -->
          <div class="wishlist" onclick="toggleWish(this)">❤</div>

          <a href="product.php?id=<?= $p['product_id'] ?>" class="no-underline">
            <img src="uploads/<?= $p['image'] ?>" class="card-img-top">
          </a>

          <div class="card-body text-center">
            <h6><?= $p['name'] ?></h6>
            <p>From <b>₹<?= $p['price'] ?></b></p>
          </div>
        </div>

        <?php endwhile; ?>

      </div>
    </div>
  </div>
</div>

<br>


<div style="text-align:center;">
  <h5 class="section-title">Home & Kitchen</h5>
</div>


<div class="animate__animated animate__zoomIn">
  <div class="product-section">
    <div class="container">
      <div class="product-row">

        <?php
 $q = $conn->query(
  "SELECT * FROM product 
   WHERE status=1 AND category='Home & Kitchen'"
);
while($p = $q->fetch_assoc()):
        ?>

        <div class="card product-card">
          <!-- wishlist -->
          <div class="wishlist" onclick="toggleWish(this)">❤</div>

          <a href="product.php?id=<?= $p['product_id'] ?>" class="no-underline">
            <img src="uploads/<?= $p['image'] ?>" class="card-img-top">
          </a>

          <div class="card-body text-center">
            <h6><?= $p['name'] ?></h6>
            <p>From <b>₹<?= $p['price'] ?></b></p>
          </div>
        </div>

        <?php endwhile; ?>

      </div>
    </div>
  </div>
</div>

<br>

<!-- FEATURES -->
<section class="features">
    <div class="feature">
        <h3>Fast Delivery</h3>
        <p>Quick and reliable shipping</p>
    </div>
    <div class="feature">
        <h3>Best Prices</h3>
        <p>Affordable deals every day</p>
    </div>
    <div class="feature">
        <h3>Secure Payment</h3>
        <p>100% safe checkout</p>
    </div>
</section>

<!-- PRODUCTS -->
 <div class="animate__animated animate__headShake ">


<section class="products" id="products">
    <h2>Popular Categories</h2>

    <div class="product-grid">
        <div class="card">
            <img src="o/EP.jpg" alt="Electronics" />
            <h4><a href="electronics.php" class="no-underline">Electronics</a></h4>
            <button><a href="electronics.php" class="no-underline">Shop Now</a></button>
        </div>

        <div class="card">
            <img src="o/t.jpg" alt="Fashion" />
            <h4><a href="fashion.php" class="no-underline">Fashion</a></h4>
            <button><a href="fashion.php" class="no-underline">Shop Now</a></button>
        </div>

        <div class="card">
            <img src="o/k.jpg" alt="Home & Kitchen" />
            <h4><a href="home.php" class="no-underline">Home & Kitchen-</a></h4>
           <button><a href="home.php" class="no-underline">Shop Now</a></button>
        </div>

        <div class="card">
            <img src="o/download.jpg" alt="Mobiles" />
            <h4><a href="mobile.php" class="no-underline">mobiles</a></h4>
            
            <button><a href="mobile.php" class="no-underline">Shop Now</a></button>
        </div>
    </div>
</section>
 </div>
<!-- FOOTER -->
<footer class="footer">
  <div class="footer-top">
    <div class="footer-col">
      <h4>ABOUT</h4>
      <a href="#">Contact Us</a>
      <a href="#">About Us</a>
      <a href="#">Careers</a>
      <a href="#">Flipkart Stories</a>
      <a href="#">Press</a>
      <a href="#">Corporate Information</a>
    </div>

    <div class="footer-col">
      <h4>GROUP COMPANIES</h4>
      <a href="#">Myntra</a>
      <a href="#">Cleartrip</a>
      <a href="#">Shopsy</a>
    </div>

    <div class="footer-col">
      <h4>HELP</h4>
      <a href="#">Payments</a>
      <a href="#">Shipping</a>
      <a href="#">Cancellation & Returns</a>
      <a href="#">FAQ</a>
    </div>

    <div class="footer-col">
      <h4>CONSUMER POLICY</h4>
      <a href="#">Cancellation & Returns</a>
      <a href="#">Terms Of Use</a>
      <a href="#">Security</a>
      <a href="#">Privacy</a>
      <a href="#">Sitemap</a>
      <a href="#">Grievance Redressal</a>
      <a href="#">EPR Compliance</a>
    </div>

    <div class="footer-divider"></div>

    <div class="footer-address">
      <h4>Mail Us:</h4>
      <p>
        Flipkart Internet Private Limited,<br>
        Buildings Alyssa, Begonia &<br>
        Clove Embassy Tech Village,<br>
        Outer Ring Road,<br>
        Bengaluru, Karnataka, India
      </p>

      <h4>Registered Office Address:</h4>
      <p>
        Flipkart Internet Private Limited,<br>
        Buildings Alyssa, Begonia &<br>
        Clove Embassy Tech Village,<br>
        Outer Ring Road,<br>
        Bengaluru, Karnataka, India<br>
        CIN : U51109KA2012PTC066107<br>
        Telephone: 044-45614700
      </p>
    </div>
  </div>

  <div class="footer-bottom">
    <span>Become a Seller</span>
    <span>Advertise</span>
    <span>Gift Cards</span>
    <span>Help Center</span>
    <span class="copyright">© 2007-2026 YourSite.com</span>
  </div>
</footer>

<script>
function toggleWish(el){
  el.classList.toggle("active");
}
</script>

</body>
</html>

