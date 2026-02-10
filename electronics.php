
<?php include "navbar.php"; ?>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "dbconne.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Products</title> 

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>



<style>
/* ===== RESET ===== */
*{
  margin:0;
  padding:0;
  box-sizing:border-box;
  font-family:"Segoe UI", Arial, sans-serif;
}

html{
  scroll-behavior:smooth;
}


/* no underline  */


.no-underline {
  text-decoration: none;
  color: inherit;
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
  box-shadow:0 6px 20px rgba(0,0,0,.25);
}


.navbar a{
  color:#fff;
  text-decoration:none;
  margin-left:25px;
  font-size:15px;
  position:relative;
}

.navbar a::after{
  content:"";
  position:absolute;
  left:0;
  bottom:-6px;
  width:0;
  height:2px;
  background:#ffd814;
  transition:.3s;
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


 /* GLOBAL */
body {
  background: linear-gradient(135deg, #eef2f7, #f7f9fc);
  font-family: "Segoe UI", sans-serif;
  color: #212121;
}

.no-underline {
  text-decoration: none;
  color: inherit;
}

/* MAIN LAYOUT */
.main-layout {
  display: flex;
  gap: 25px;
  padding: 20px;
}

/* SIDEBAR – GLASS EFFECT */
.sidebar {
  width: 280px;
  padding: 20px;
  height: 100vh;
  position: sticky;
  top: 0;
  overflow-y: auto;

  background: rgba(255, 255, 255, 0.75);
  backdrop-filter: blur(12px);
  border-radius: 20px;

  box-shadow:
    0 10px 35px rgba(0, 0, 0, 0.15),
    inset 0 1px 0 rgba(255,255,255,0.6);
}

.filter-title {
  font-size: 20px;
  font-weight: 800;
  margin-bottom: 18px;
  color: #2874f0;
}

/* FILTER SECTIONS */
.filter-section {
  padding: 14px 0;
  border-bottom: 1px solid rgba(0,0,0,0.08);
}

.filter-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  cursor: pointer;
  font-weight: 700;
  font-size: 14px;
}

.arrow {
  font-size: 18px;
  transition: transform 0.3s ease;
  color: #2874f0;
}

.filter-body {
  display: none;
  margin-top: 12px;
  animation: slideDown 0.35s ease;
}

.filter-body label {
  display: block;
  font-size: 14px;
  margin: 7px 0;
  cursor: pointer;
}

.filter-body input {
  accent-color: #2874f0;
}

.more-link {
  margin-top: 10px;
  color: #2874f0;
  font-weight: 700;
  cursor: pointer;
}

/* CONTENT AREA */
.content-area {
  flex: 1;
}

/* PRODUCT CARD – PREMIUM */
.card {
  background: linear-gradient(145deg, #ffffff, #f1f4fa);
  border-radius: 22px;
  padding: 22px;
  height: 100%;
  text-align: center;
  border: none;

  box-shadow:
    0 10px 30px rgba(0,0,0,0.1),
    inset 0 1px 0 rgba(255,255,255,0.6);

  transition: all 0.35s ease;
  position: relative;
  overflow: hidden;
}

/* CARD GLOW */
.card::before {
  content: "";
  position: absolute;
  inset: 0;
  background: linear-gradient(120deg, transparent, rgba(40,116,240,0.12), transparent);
  opacity: 0;
  transition: opacity 0.3s ease;
}

.card:hover::before {
  opacity: 1;
}

.card:hover {
  transform: translateY(-14px) scale(1.03);
  box-shadow:
    0 25px 55px rgba(0,0,0,0.2),
    0 0 30px rgba(40,116,240,0.25);
}

/* PRODUCT IMAGE */
.card img {
  height: 220px;
  object-fit: contain;
  margin-bottom: 15px;
  transition: transform 0.35s ease;
}

.card:hover img {
  transform: scale(1.12);
}

/* TEXT */
.card h5 {
  font-size: 16px;
  font-weight: 700;
  margin-bottom: 8px;
}

.card p {
  font-size: 15px;
  color: #555;
}

.card span {
  font-size: 17px;
  font-weight: 800;
  color: #2874f0;
}

/* GRID */
.row {
  margin-bottom: 15px;
}

/* ANIMATION */
@keyframes slideDown {
  from {
    opacity: 0;
    transform: translateY(-6px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* SCROLLBAR */
.sidebar::-webkit-scrollbar {
  width: 6px;
}
.sidebar::-webkit-scrollbar-thumb {
  background: linear-gradient(#2874f0, #6fa4ff);
  border-radius: 10px;
}

/* RESPONSIVE */
@media (max-width: 992px) {
  .main-layout {
    flex-direction: column;
  }

  .sidebar {
    width: 100%;
    height: auto;
  }
}


/* wishlike icon */

.wishlist {
  position: absolute;
  top: 2px;
  right: 15px;
  background: rgba(255,255,255,0.9);
  border-radius: 50%;
  padding: 8px 10px;
  font-size: 18px;
  cursor: pointer;
  color: #aaa;
  transition: all 0.3s ease;
  user-select: none;
}

/* Hover */
.wishlist:hover {
  transform: scale(1.15);
  box-shadow: 0 0 12px rgba(255,61,61,0.4);
}

/* Active (clicked) */
.wishlist.active {
  color: #ff3d3d;
  background: #fff;
  box-shadow: 0 0 15px rgba(255,61,61,0.7);
}

/* footer */

.footer {
    background-color: #172337;
    color: #fff;
    font-size: 13px;
}

.footer-top {
    display: grid;
    grid-template-columns: repeat(4, 1fr) 1px 2fr;
    gap: 35px;
    padding: 50px 70px;
}

.footer-col h4,
.footer-address h4 {
    color: #878787;
    font-size: 12px;
    margin-bottom: 14px;
    text-transform: uppercase;
}

.footer-col a {
    display: block;
    color: #fff;
    text-decoration: none;
    margin-bottom: 9px;
    transition: color 0.3s ease;
}

.footer-col a:hover {
    color: #f08804;
}

.footer-divider {
    background-color: #454d5e;
    width: 1px;
}

.footer-address p {
    line-height: 1.7;
    margin-bottom: 18px;
}

.footer-bottom {
    border-top: 1px solid #454d5e;
    display: flex;
    justify-content: space-around;
    align-items: center;
    padding: 18px 40px;
    font-size: 12px;
}

.footer-bottom span:hover {
    color: #f08804;
    cursor: pointer;
}

.copyright {
    cursor: default;
}


/* NEW STYLE ADDED HERE */
/* ===============================
   PRODUCT SECTION (NEW GRID)
   =============================== */

.product-section {
  padding: 30px 0;
}


/* reload */
/* LOADER WRAPPER */
.product-loader {
  display: none;
  width: 100%;
  text-align: center;
  padding: 60px 0;
}

/* SPINNER */
.spinner {
  width: 48px;
  height: 48px;
  border: 5px solid #e0e6f1;
  border-top-color: #2874f0;
  border-radius: 50%;
  animation: spin 0.8s linear infinite;
  margin: auto;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}



.product-row {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
  gap: 28px;
  align-items: stretch;
}

/* Product Card (inherits your .card style) */
.product-card {
  height: 100%;
  display: flex;
  flex-direction: column;
}

/* Image fix */
.product-card img {
  height: 220px;
  object-fit: contain;
  margin-bottom: 12px;
}

/* Card body spacing */
.product-card .card-body {
  padding: 10px 5px 0;
}

/* Title */
.product-card h6 {
  font-size: 16px;
  font-weight: 700;
  margin-bottom: 6px;
  color: #222;
}

/* Price */
.product-card p {
  font-size: 15px;
  color: #555;
}

.product-card p b {
  font-size: 17px;
  color: #2874f0;
}

/* Wishlist icon already styled — just ensure layer */
.product-card .wishlist {
  z-index: 5;
}

/* Hover consistency */
.product-card:hover {
  transform: translateY(-14px) scale(1.03);
}

/* ===============================
   RESPONSIVE
   =============================== */

@media (max-width: 576px) {
  .product-row {
    grid-template-columns: repeat(1, 1fr);
  }
}

@media (min-width: 577px) and (max-width: 992px) {
  .product-row {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (min-width: 993px) {
  .product-row {
    grid-template-columns: repeat(3, 1fr);
  }
}


/* share */
.share-btn{
  position:absolute;
  top:1px;
  right:70px;
  font-size:30px;
  cursor:pointer;
  color:#ca1414;
}
.share-btn:hover{
  color:#2874f0;
}


/* price range */
#priceRange {
  width: 100%;
  cursor: pointer;
}

</style>
<body>


<!-- NAVBAR -->

<!-- tool bar -->
<div>
<div class="category-bar">
  <a href="mobile.php" class="no-underline">
  <div class="category-item">
    
    <img src="https://rukminim2.flixcart.com/fk-p-flap/64/64/image/22fddf3c7da4c4f4.png" />
    <span>Mobiles </span>
   
  </div>
</a>  




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



<div class="main-layout">

  <!-- LEFT SIDEBAR -->
  <div class="sidebar">
    <h4 class="filter-title">Filters</h4>

    <div class="filter-section">
      <h6>CATEGORIES</h6>
      <p class="category">‹ Electronics</p>
      <strong>Electronics</strong>
    </div>

     <!-- brand -->
   <div class="filter-section">
  <div class="filter-header" onclick="toggleFilter('brandBody','brandArrow')">
    <span>BRAND</span>
    <span id="brandArrow" class="arrow">▼</span>
  </div>
  <div id="brandBody" class="filter-body">
    <label><input type="checkbox" class="filter" data-type="brand" value="HP"> HP</label>
    <label><input type="checkbox" class="filter" data-type="brand" value="Dell"> Dell</label>
    <label><input type="checkbox" class="filter" data-type="brand" value="Lenovo"> Lenovo</label>
    <label><input type="checkbox" class="filter" data-type="brand" value="Apple"> Apple</label>
    <label><input type="checkbox" class="filter" data-type="brand" value="Asus"> Asus</label>
  </div>
</div>


    <!--PROCESSOR-->
   <div class="filter-section">
  <div class="filter-header" onclick="toggleFilter('processorBody','processorArrow')">
    <span>PROCESSOR</span>
    <span id="processorArrow" class="arrow">▼</span>
  </div>
  <div id="processorBody" class="filter-body">
    <label><input type="checkbox" class="filter" data-type="processor" value="Core i3"> Core i3</label>
    <label><input type="checkbox" class="filter" data-type="processor" value="Core i5"> Core i5</label>
    <label><input type="checkbox" class="filter" data-type="processor" value="Core i7"> Core i7</label>
    <label><input type="checkbox" class="filter" data-type="processor" value="Core i9"> Core i9</label>
    <label><input type="checkbox" class="filter" data-type="processor" value="Ryzen 5"> Ryzen 5</label>
    <label><input type="checkbox" class="filter" data-type="processor" value="Ryzen 7"> Ryzen 7</label>
  </div>
</div>



    <!-- PROCESSOR GENERATION -->
  <div class="filter-section">
  <div class="filter-header" onclick="toggleFilter('genBody','genArrow')">
    <span>PROCESSOR GENERATION</span>
    <span id="genArrow" class="arrow">▼</span>
  </div>
  <div id="genBody" class="filter-body">
    <label><input type="checkbox" class="filter" data-type="processor_gen" value="14th Gen"> 14th Gen</label>
    <label><input type="checkbox" class="filter" data-type="processor_gen" value="13th Gen"> 13th Gen</label>
    <label><input type="checkbox" class="filter" data-type="processor_gen" value="12th Gen"> 12th Gen</label>
    <label><input type="checkbox" class="filter" data-type="processor_gen" value="11th Gen"> 11th Gen</label>
    <label><input type="checkbox" class="filter" data-type="processor_gen" value="10th Gen"> 10th Gen</label>
  </div>
</div>


        <!-- RAM CAPACITY-->
   <div class="filter-section">
  <div class="filter-header" onclick="toggleFilter('ramBody','ramArrow')">
    <span>RAM CAPACITY</span>
    <span id="ramArrow" class="arrow">▼</span>
  </div>
  <div id="ramBody" class="filter-body">
    <label><input type="checkbox" class="filter" data-type="ram" value="4 GB"> 4 GB</label>
    <label><input type="checkbox" class="filter" data-type="ram" value="8 GB"> 8 GB</label>
    <label><input type="checkbox" class="filter" data-type="ram" value="16 GB"> 16 GB</label>
    <label><input type="checkbox" class="filter" data-type="ram" value="32 GB"> 32 GB</label>
    <label><input type="checkbox" class="filter" data-type="ram" value="64 GB"> 64 GB</label>
  </div>
</div>



 <!-- STORAGE TYPE -->
   <div class="filter-section">
  <div class="filter-header" onclick="toggleFilter('storageBody','storageArrow')">
    <span>STORAGE TYPE</span>
    <span id="storageArrow" class="arrow">▼</span>
  </div>
  <div id="storageBody" class="filter-body">
    <label><input type="checkbox" class="filter" data-type="storage_type" value="SSD"> SSD</label>
    <label><input type="checkbox" class="filter" data-type="storage_type" value="HDD"> HDD</label>
    <label><input type="checkbox" class="filter" data-type="storage_type" value="eMMC"> eMMC</label>
  </div>
</div>


<!-- SCREEN SIZE -->
   <div class="filter-section">
  <div class="filter-header" onclick="toggleFilter('screenBody','screenArrow')">
    <span>SCREEN SIZE</span>
    <span id="screenArrow" class="arrow">▼</span>
  </div>
  <div id="screenBody" class="filter-body">
    <label><input type="checkbox" class="filter" data-type="screen_size" value="13 inch"> 13 inch</label>
    <label><input type="checkbox" class="filter" data-type="screen_size" value="14 inch"> 14 inch</label>
    <label><input type="checkbox" class="filter" data-type="screen_size" value="15.6 inch"> 15.6 inch</label>
    <label><input type="checkbox" class="filter" data-type="screen_size" value="16 inch"> 16 inch</label>
  </div>
</div>



    <!-- GRAPHICS -->
  <div class="filter-section">
  <div class="filter-header" onclick="toggleFilter('graphicsBody','graphicsArrow')">
    <span>GRAPHICS</span>
    <span id="graphicsArrow" class="arrow">▼</span>
  </div>
  <div id="graphicsBody" class="filter-body">
    <label><input type="checkbox" class="filter" data-type="graphics" value="Intel Integrated"> Intel Integrated</label>
    <label><input type="checkbox" class="filter" data-type="graphics" value="NVIDIA RTX"> NVIDIA RTX</label>
    <label><input type="checkbox" class="filter" data-type="graphics" value="NVIDIA GTX"> NVIDIA GTX</label>
    <label><input type="checkbox" class="filter" data-type="graphics" value="AMD Radeon"> AMD Radeon</label>
  </div>
</div>



     <!-- OFFERS-->
<div class="filter-section">
  <div class="filter-header" onclick="toggleFilter('offerBody','offerArrow')">
    <span>OFFERS</span>
    <span id="offerArrow" class="arrow">▼</span>
  </div>

  <div id="offerBody" class="filter-body">
    <label><input type="checkbox" class="filter" data-type="discount" value="10%"> 10% or more</label>
    <label><input type="checkbox" class="filter" data-type="discount" value="20%"> 20% or more</label>
    <label><input type="checkbox" class="filter" data-type="discount" value="30%"> 30% or more</label>
    <label><input type="checkbox" class="filter" data-type="discount" value="40%"> 40% or more</label>
    <label><input type="checkbox" class="filter" data-type="discount" value="50%"> 50% or more</label>
  </div>
</div>

  </div>
  <!-- Right sidebar -->


  <div id="loader" class="product-loader">
  <div class="spinner"></div>
</div>

  <!-- example -->
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

        <!-- share button -->
        <div class="share-btn" onclick="shareProduct(event, this)">📤</div>




        <!-- wishlist -->
        <div class="wishlist" onclick="toggleWish(this)">❤</div>

        <!-- product link -->
        <a href="product.php?id=<?= $p['product_id'] ?>" class="no-underline">

          <img src="uploads/<?= $p['image'] ?>" class="card-img-top" alt="<?= $p['name'] ?>">

          <div class="card-body text-center">
            <h6><?= $p['name'] ?></h6>
            <p>From <b>₹<?= $p['price'] ?></b></p>
          </div>

        </a>

      </div>

      <?php endwhile; ?>

    </div>

  </div>
</div>
</div>




<!-- footer  -->
<footer class="footer">
  <div class="footer-top">
    <div class="footer-col">
      <h4>ABOUT</h4>
      <a href="#">Contact Us</a>
      <a href="#">About Us</a>
      <a href="#">Careers</a>
      <a href="#">ShopNow Stories</a>
      <a href="#">Press</a>
      <a href="#">Corporate Information</a>
    </div>

    <div class="footer-col">
      <h4>GROUP COMPANIES</h4>
      <a href="#">Myntra</a>
      <a href="#">Cleartrip</a>
    
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
    <span class="copyright">© 2026 YourSite.com</span>
  </div>
</footer>







 
<!-- javascript -->
<!-- sidebar -->
<script>
function toggleFilter(bodyId, arrowId) {
  const body = document.getElementById(bodyId);
  const arrow = document.getElementById(arrowId);
  if (body.style.display === "block" || body.style.display === "") {
    body.style.display = "none";
    arrow.textContent = "▼";
  } else {
    body.style.display = "block";
    arrow.textContent = "⌃";
  }
}

// wishlist icon

function toggleWish(el) {
  el.classList.toggle("active");
}
</script>





<!-- SHARE BUTTTON -->
<script>
function shareProduct(e, el) {
  e.preventDefault();
  e.stopPropagation();

  const card = el.closest('.card');
  const link = card.querySelector('a').href;

  const whatsappUrl = `https://wa.me/?text=${encodeURIComponent(link)}`;
  window.open(whatsappUrl, '_blank');
}
</script>


<!-- clickable checkbox -->
 <script>
document.querySelectorAll('.filter').forEach(cb => {
  cb.addEventListener('change', filterProducts);
});

function filterProducts() {
  let filters = {};

  document.querySelectorAll('.filter:checked').forEach(cb => {
    let type = cb.dataset.type;

    if (!filters[type]) {
      filters[type] = [];
    }
    filters[type].push(cb.value);
  });

  fetch('filter-products.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(filters)
  })
  .then(res => res.text())
  .then(html => {
    document.querySelector('.product-row').innerHTML = html;
  });
}
</script>



<script>
document.querySelectorAll('.filter').forEach(cb => {
  cb.addEventListener('change', filterProducts);
});
</script>


<!-- price -->
<script>
const priceRange = document.getElementById("priceRange");
const priceValue = document.getElementById("priceValue");

// update price text
priceRange.addEventListener("input", () => {
  priceValue.textContent = priceRange.value;
  filterProducts();
});

// checkbox change
document.querySelectorAll('.filter').forEach(cb => {
  cb.addEventListener('change', filterProducts);
});

function filterProducts() {
  let filters = {};

  // collect checked filters
  document.querySelectorAll('.filter:checked').forEach(cb => {
    let type = cb.dataset.type;
    if (!filters[type]) filters[type] = [];
    filters[type].push(cb.value);
  });

  // add price
  filters.max_price = priceRange.value;

  // loader
  document.getElementById('loader').style.display = "block";
  document.querySelector('.product-row').style.display = "none";

  fetch('filter-products.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(filters)
  })
  .then(res => res.text())
  .then(html => {
    document.getElementById('loader').style.display = "none";
    document.querySelector('.product-row').style.display = "grid";
    document.querySelector('.product-row').innerHTML = html;
  });
}
</script>



</body>
</html>
