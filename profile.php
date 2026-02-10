<?php
session_start();
include "dbconne.php";

/* CHECK LOGIN */
if (!isset($_SESSION['user_id'])) {
  header("Location: userlogin.php");
  exit;
}

$uid = (int) $_SESSION['user_id'];

/* FETCH LOGGED-IN USER */
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $uid);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
  session_destroy();
  header("Location: userlogin.php");
  exit;
}

/* PROFILE IMAGE */
$profileImg = !empty($user['profile_image'])
  ? "uploads/profile/" . $user['profile_image']
  : "assets/default-user.png";
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Profile</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

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

/* ===== DARK OVERLAY ===== */
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

/* ===== MAIN GLASS CARD ===== */
.profile-wrap{
  background:rgba(255,255,255,.22);
  backdrop-filter:blur(26px);
  -webkit-backdrop-filter:blur(26px);
  border-radius:22px;
  border:1px solid rgba(255,255,255,.28);
  box-shadow:
    0 30px 70px rgba(0,0,0,.35),
    inset 0 0 0 1px rgba(255,255,255,.25);
  overflow:hidden;
  animation:fadeUp .9s ease;
}
@keyframes fadeUp{
  from{opacity:0;transform:translateY(40px)}
  to{opacity:1;transform:translateY(0)}
}

/* ===== HEADER ===== */
.profile-header{
  background:linear-gradient(
    120deg,
    rgba(40,116,240,.92),
    rgba(106,17,203,.92)
  );
  color:#fff;
  padding:40px 20px 95px;
  text-align:center;
}
.profile-header h3,
.profile-header small{
  color:#fff;
  text-shadow:0 2px 6px rgba(0,0,0,.4);
}

/* ===== AVATAR ===== */
.avatar{
  width:150px;
  height:150px;
  border-radius:50%;
  object-fit:cover;
  border:6px solid rgba(255,255,255,.95);
  margin-top:-85px;
  background:#fff;
  box-shadow:
    0 0 0 10px rgba(255,255,255,.35),
    0 25px 45px rgba(0,0,0,.35);
}

/* ===== CONTENT ===== */
.content{
  padding:35px;
}

/* ===== INFO BOXES ===== */
.info{
  background:rgba(255,255,255,.6);
  backdrop-filter:blur(14px);
  -webkit-backdrop-filter:blur(14px);
  border-radius:14px;
  padding:18px;
  border:1px solid rgba(255,255,255,.35);
  box-shadow:0 10px 25px rgba(0,0,0,.15);
  margin-bottom:18px;
}

.label{
  font-size:13px;
  color:#222;
}
.value{
  font-weight:600;
  color:#111;
}

/* ===== SECTION TITLE ===== */
.section-title{
  font-weight:700;
  margin:35px 0 15px;
  color:#111;
  text-shadow:0 1px 2px rgba(255,255,255,.6);
}

/* ===== BUTTONS ===== */
.btn-main{
  background:linear-gradient(
    120deg,
    rgba(40,116,240,.95),
    rgba(106,17,203,.95)
  );
  color:#fff;
  border:none;
  font-weight:600;
}
.btn-main:hover{
  opacity:.92;
}

.btn-outline-danger{
  background:rgba(255,255,255,.4);
  backdrop-filter:blur(10px);
  border:1px solid #dc3545;
  color:#dc3545;
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

<!-- VIDEO -->
<div class="video-bg">
  <video id="bgVideo" src="g.mp4" autoplay loop playsinline preload="auto"></video>
</div>

<div class="overlay"></div>
<div class="aurora"></div>

<div class="container my-5 position-relative">
<div class="row justify-content-center">
<div class="col-lg-7 col-md-9">

<div class="profile-wrap">

  <div class="profile-header">
    <h3 class="mb-1">👤 My Profile</h3>
    <small>Welcome back, <?= htmlspecialchars($user['name']) ?></small>
  </div>

  <div class="text-center">
    <img src="<?= $profileImg ?>" class="avatar">
  </div>

  <div class="content">

    <div class="row">
      <div class="col-md-6">
        <div class="info">
          <div class="label"><i class="bi bi-person"></i> Full Name</div>
          <div class="value"><?= htmlspecialchars($user['name']) ?></div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="info">
          <div class="label"><i class="bi bi-envelope"></i> Email</div>
          <div class="value"><?= htmlspecialchars($user['email']) ?></div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6">
        <div class="info">
          <div class="label"><i class="bi bi-telephone"></i> Mobile</div>
          <div class="value"><?= htmlspecialchars($user['phone']) ?></div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="info">
          <div class="label"><i class="bi bi-gender-ambiguous"></i> Gender</div>
          <div class="value"><?= htmlspecialchars($user['gender']) ?></div>
        </div>
      </div>
    </div>

    <div class="section-title">📍 Address</div>
    <div class="info">
      <?= nl2br(htmlspecialchars($user['address'])) ?><br>
      <?= htmlspecialchars($user['city']) ?>,
      <?= htmlspecialchars($user['state']) ?> -
      <?= htmlspecialchars($user['pincode']) ?>
    </div>

    <div class="d-flex gap-3 mt-4">
      <a href="edit-profile.php" class="btn btn-main w-50">
        <i class="bi bi-pencil"></i> Edit Profile
      </a>
      <a href="userlogout.php" class="btn btn-outline-danger w-50">
        <i class="bi bi-box-arrow-right"></i> Logout
      </a>
    </div>
  

  </div>
  <div class="d-flex justify-content-center mt-3">
  <a href="frontpage.php" class="btn btn-main w-50">
    <i class="bi bi-box-arrow-right"></i> Home
  </a>
</div>

</div>

</div>
</div>
</div>

<div class="sound-toggle" id="soundBtn">🔊</div>

<script>
const video = document.getElementById("bgVideo");
const btn   = document.getElementById("soundBtn");

video.volume = 0.6;
const attempt = video.play();
if (attempt !== undefined) {
  attempt.catch(() => {
    video.muted = true;
    btn.textContent = "🔇";
  });
}

document.addEventListener("click", () => {
  video.muted = false;
  video.play();
  btn.textContent = "🔊";
}, { once:true });

btn.onclick = () => {
  video.muted = !video.muted;
  btn.textContent = video.muted ? "🔇" : "🔊";
};
</script>

</body>
</html>
