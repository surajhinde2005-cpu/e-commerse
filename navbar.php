<?php
include "dbconne.php";

$site_name = "My Website";
$site_logo = "uploads/default.png";

$result = $conn->query("SELECT * FROM settings LIMIT 1");

if($result && $result->num_rows > 0){
    $data = $result->fetch_assoc();
    $site_name = $data['site_name'] ?? $site_name;
    $site_logo = $data['logo'] ?? $site_logo;
}
?>


<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$profilePhoto = "assets/user.png";

if(isset($_SESSION['user_id'])){
  include "dbconne.php";
  $uid = $_SESSION['user_id'];

  $u = $conn->query("SELECT profile_image FROM users WHERE id=$uid")->fetch_assoc();
  if(!empty($u['profile_image'])){
    $profilePhoto = "uploads/profile/".$u['profile_image'];
  }
}
?>

<?php
include "config-site.php";
?>

<style>
/* ===== NAVBAR STYLES ONLY ===== */
.navbar{
  background:linear-gradient(90deg,#0f2027,#203a43,#2c5364);
  color:#fff;
  padding:14px 60px;
  display:flex;
  justify-content:space-between;
  align-items:center;
  position:sticky;
  top: 0;
  z-index: 1000;
  box-shadow:0 6px 20px rgba(0,0,0,.25);
}

.nav-left,
.nav-right{
  display:flex;
  align-items:center;
}

/* Logo container */
.logo a{
  display:flex;
  align-items:center;
  gap:10px;
  text-decoration:none;
}

/* Logo image */
.logo img{
  height:42px;
  width:auto;
  object-fit:contain;
  border-radius:8px;
}

/* Site name */
.logo span{
  font-size:26px;
  font-weight:800;
  color:#ffd814;
  letter-spacing:1px;
}


.nav-right > *{
  margin-left:20px;
  display:flex;
  align-items:center;
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

.user-menu{
  position: relative;
  cursor: pointer;
}

/* dropdown */
.dropdown{
  position: absolute;
  top: 100%;
  right: 0;
  margin-top: 10px;

  background: #0b0202;
  min-width: 160px;
  border-radius: 12px;
  box-shadow: 0 15px 35px rgba(0,0,0,.4);

  display: none;          /* 🔥 hidden by default */
  z-index: 9999;
}

/* show when JS adds class */
.user-menu.open .dropdown{
  display: block;
}

.dropdown a{
  display: block;
  padding: 12px 16px;
  color: #fff;
  text-decoration: none;
}

.dropdown a:hover{
  background: rgba(255,255,255,.1);
}

/*  user profile image */
.user-avatar{
  width:38px;
  height:38px;
  border-radius:50%;
  object-fit:cover;
  border:2px solid #ffd814;
  cursor:pointer;
}

</style>

<div class="navbar">

  <div class="nav-left">
    <div class="logo">
      <a href="frontpage.php" class="logo d-flex align-items-center gap-2">
    <img src="<?= htmlspecialchars($site_logo) ?>" height="40">
    <span><?= htmlspecialchars($site_name) ?></span>
</a>

    </div>
  </div>

  <form action="search.php" method="get">
    <input type="text" name="search" placeholder="Search products...">
    <button type="submit">🔍</button>
  </form>

  <div class="nav-right">
    <a href="frontpage.php">Home</a>
    <a href="#products">Products</a>
    <a href="cart.php">Cart</a>
     <a href="about.php">About Us</a>

  <?php if(isset($_SESSION['user_id'])): ?>
  <div class="user-menu" id="userMenu">
    <img src="<?= $profilePhoto ?>" class="user-avatar">

    <div class="dropdown">
      <a href="profile.php">Profile</a>
      <a href="orders.php">Orders</a>
      <a href="userlogout.php">Logout</a>
    </div>
  </div>
<?php else: ?>
  <a href="userlogin.php">Login</a>
<?php endif; ?>




  </div>

</div>

<!-- javascript -->
 <script>
const userMenu = document.getElementById("userMenu");

if(userMenu){
  userMenu.addEventListener("click", function(e){
    e.stopPropagation();
    this.classList.toggle("open");
  });

  document.addEventListener("click", function(e){
    if(!userMenu.contains(e.target)){
      userMenu.classList.remove("open");
    }
  });
}
</script>

