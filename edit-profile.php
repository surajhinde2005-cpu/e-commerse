<?php
session_start();
include "dbconne.php";



/* CHECK LOGIN */
if(!isset($_SESSION['user_id'])){
  header("Location: userlogin.php");
  exit;
}

$uid = $_SESSION['user_id'];

/* FETCH USER */
$user = $conn->query("SELECT * FROM users WHERE id=$uid")->fetch_assoc();
if(!$user){
  die("User not found");
}

/* UPDATE PROFILE */
if(isset($_POST['update'])){

  $name     = $_POST['name'];
  $phone    = $_POST['phone'];
  $gender   = $_POST['gender'];
  $dob      = $_POST['dob'];
  $address  = $_POST['address'];
  $city     = $_POST['city'];
  $state    = $_POST['state'];
  $pincode  = $_POST['pincode'];

  $profileImage = $user['profile_image'];

  if(!empty($_FILES['profile_image']['name'])){
    $imgName = time().'_'.$_FILES['profile_image']['name'];
    move_uploaded_file($_FILES['profile_image']['tmp_name'], "uploads/profile/".$imgName);
    $profileImage = $imgName;
  }

  $conn->query("
    UPDATE users SET
      name='$name',
      phone='$phone',
      gender='$gender',
      dob='$dob',
      address='$address',
      city='$city',
      state='$state',
      pincode='$pincode',
      profile_image='$profileImage'
    WHERE id=$uid
  ");

  header("Location: profile.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Profile</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
*{
  box-sizing:border-box;
  margin:0;
  padding:0;
  font-family:"Segoe UI",system-ui,sans-serif;
}

/* ===== VIDEO BACKGROUND ===== */
.video-bg{
  position:fixed;
  inset:0;
  z-index:-3;
}
.video-bg video{
  width:100%;
  height:100%;
  object-fit:cover;
  filter:brightness(1.25) contrast(1.15) saturate(1.2);
}

/* ===== DARK SOFT OVERLAY ===== */
.overlay{
  position:fixed;
  inset:0;
  background:rgba(0,0,0,.15);
  z-index:-2;
}

/* ===== AURORA EFFECT ===== */
.aurora{
  position:fixed;
  inset:-30%;
  background:
    radial-gradient(circle at 20% 30%, #38bdf855, transparent 45%),
    radial-gradient(circle at 80% 20%, #a78bfa55, transparent 45%),
    radial-gradient(circle at 50% 80%, #22d3ee55, transparent 50%);
  filter:blur(160px);
  animation:auroraMove 32s ease-in-out infinite;
  z-index:-1;
}
@keyframes auroraMove{
  0%{transform:translate(0,0)}
  50%{transform:translate(120px,-100px)}
  100%{transform:translate(0,0)}
}

/* ===== GLASS EDIT CARD ===== */
.profile-card{
  background:rgba(255,255,255,.22);
  backdrop-filter:blur(26px);
  -webkit-backdrop-filter:blur(26px);
  border-radius:22px;
  padding:34px;
  border:1px solid rgba(255,255,255,.28);
  box-shadow:
    0 30px 70px rgba(0,0,0,.35),
    inset 0 0 0 1px rgba(255,255,255,.25);
  animation:fadeUp .9s ease;
}
@keyframes fadeUp{
  from{opacity:0;transform:translateY(40px)}
  to{opacity:1;transform:translateY(0)}
}

/* ===== TITLE ===== */
.title{
  font-weight:800;
  color:#fff;
  text-shadow:0 2px 6px rgba(0,0,0,.4);
}

/* ===== PROFILE IMAGE ===== */
.profile-img{
  width:130px;
  height:130px;
  border-radius:50%;
  object-fit:cover;
  border:5px solid rgba(255,255,255,.95);
  background:#fff;
  box-shadow:
    0 0 0 8px rgba(255,255,255,.35),
    0 20px 40px rgba(0,0,0,.35);
  transition:.4s;
}
.profile-img:hover{
  transform:scale(1.08);
}

/* ===== INPUTS ===== */
.form-control,
.form-select{
  background:rgba(255,255,255,.6);
  backdrop-filter:blur(12px);
  border-radius:14px;
  padding:12px;
  border:1px solid rgba(255,255,255,.4);
  color:#111;
}
.form-control:focus,
.form-select:focus{
  background:rgba(255,255,255,.75);
  box-shadow:0 0 0 .25rem rgba(40,116,240,.35);
  border-color:#2874f0;
}

/* ===== LABELS ===== */
label{
  color:#fff;
  font-weight:600;
  text-shadow:0 1px 3px rgba(0,0,0,.5);
}

/* ===== BUTTONS ===== */
.btn-save{
  background:linear-gradient(
    120deg,
    rgba(40,116,240,.95),
    rgba(106,17,203,.95)
  );
  color:#fff;
  border:none;
  border-radius:16px;
  font-weight:600;
}
.btn-save:hover{
  opacity:.92;
}

.btn-cancel{
  background:rgba(255,255,255,.45);
  backdrop-filter:blur(12px);
  border-radius:16px;
  border:1px solid rgba(255,255,255,.6);
  color:#111;
}

/* ===== SOUND BUTTON ===== */
.sound-toggle{
  position:fixed;
  bottom:30px;
  right:30px;
  width:56px;
  height:56px;
  border-radius:50%;
  background:rgba(255,255,255,.25);
  backdrop-filter:blur(14px);
  display:flex;
  align-items:center;
  justify-content:center;
  font-size:22px;
  cursor:pointer;
  z-index:10;
}

</style>
</head>

<body>

<!-- 🎥 VIDEO -->
<div class="video-bg">
  <video id="bgVideo" src="y.mp4" autoplay loop playsinline preload="auto"></video>
</div>

<div class="overlay"></div>
<div class="aurora"></div>

<div class="container d-flex align-items-center justify-content-center min-vh-100">
<div class="col-lg-7 col-md-9">

<div class="profile-card">

<h3 class="text-center mb-4 title">✏️ Edit Your Profile</h3>

<form method="post" enctype="multipart/form-data">

<div class="text-center mb-4">
  <img src="uploads/profile/<?= $user['profile_image'] ?>" class="profile-img mb-3">
  <input type="file" name="profile_image" class="form-control">
</div>

<div class="row mb-3">
  <div class="col-md-6">
    <label>Full Name</label>
    <input type="text" name="name" class="form-control" value="<?= $user['name'] ?>" required>
  </div>
  <div class="col-md-6">
    <label>Mobile</label>
    <input type="text" name="phone" class="form-control" value="<?= $user['phone'] ?>" required>
  </div>
</div>

<div class="row mb-3">
  <div class="col-md-6">
    <label>Gender</label>
    <select name="gender" class="form-select">
      <option <?= $user['gender']=='Male'?'selected':'' ?>>Male</option>
      <option <?= $user['gender']=='Female'?'selected':'' ?>>Female</option>
      <option <?= $user['gender']=='Other'?'selected':'' ?>>Other</option>
    </select>
  </div>
  <div class="col-md-6">
    <label>Date of Birth</label>
    <input type="date" name="dob" class="form-control" value="<?= $user['dob'] ?>">
  </div>
</div>

<div class="mb-3">
  <label>Address</label>
  <textarea name="address" class="form-control" rows="2"><?= $user['address'] ?></textarea>
</div>

<div class="row mb-4">
  <div class="col-md-4">
    <label>City</label>
    <input type="text" name="city" class="form-control" value="<?= $user['city'] ?>">
  </div>
  <div class="col-md-4">
    <label>State</label>
    <input type="text" name="state" class="form-control" value="<?= $user['state'] ?>">
  </div>
  <div class="col-md-4">
    <label>Pincode</label>
    <input type="text" name="pincode" class="form-control" value="<?= $user['pincode'] ?>">
  </div>
</div>

<div class="d-flex gap-3">
  <button type="submit" name="update" class="btn btn-save w-50">Save Changes</button>
  <a href="profile.php" class="btn btn-outline-secondary w-50 btn-cancel">Cancel</a>
</div>

</form>

</div>
</div>
</div>

<!-- 🔊 SOUND BUTTON -->
<div class="sound-toggle" id="soundBtn">🔊</div>

<script>
const video = document.getElementById("bgVideo");
const btn   = document.getElementById("soundBtn");

video.volume = 0.6;

// Try autoplay
const attempt = video.play();
if (attempt !== undefined) {
  attempt.catch(() => {
    video.muted = true;
    btn.textContent = "🔇";
  });
}

// Enable sound on first click
document.addEventListener("click", () => {
  video.muted = false;
  video.play();
  btn.textContent = "🔊";
}, { once:true });

// Toggle sound
btn.onclick = () => {
  video.muted = !video.muted;
  btn.textContent = video.muted ? "🔇" : "🔊";
};
</script>

</body>
</html>
