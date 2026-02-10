<?php
session_start();
include "dbconne.php";

if(isset($_POST['login'])){
  $u = $_POST['username'];
  $p = $_POST['password'];

  $q = $conn->query("SELECT * FROM admin WHERE username='$u' AND password='$p'");

  if($q && $q->num_rows == 1){
    $_SESSION['admin'] = $u;
    header("Location: dashboard.php");
    exit;
  } else {
    $error = "Invalid login details";
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Login</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

<style>
/* ========== RESET ========== */
*{box-sizing:border-box}
html,body{
  margin:0;
  height:100%;
  font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;
  overflow:hidden;
}

/* ========== VIDEO BACKGROUND ========== */
.video-bg{
  position:fixed;
  inset:0;
  z-index:-3;
}
.video-bg video{
  width:100%;
  height:100%;
  object-fit:cover;
  filter:brightness(1.15) contrast(1.1) saturate(1.1);
}

/* SOFT DARK OVERLAY (VIDEO CLEAR) */
.overlay{
  position:fixed;
  inset:0;
  background:linear-gradient(
    120deg,
    rgba(2,6,23,.45),
    rgba(2,6,23,.25),
    rgba(2,6,23,.45)
  );
  z-index:-2;
}

/* AURORA GLOW */
.aurora{
  position:fixed;
  inset:-30%;
  background:
    radial-gradient(circle at 20% 30%, #38bdf855, transparent 45%),
    radial-gradient(circle at 80% 20%, #a78bfa55, transparent 45%),
    radial-gradient(circle at 50% 80%, #22d3ee55, transparent 50%);
  filter:blur(160px);
  animation:auroraMove 30s ease-in-out infinite;
  z-index:-1;
}
@keyframes auroraMove{
  0%{transform:translate(0,0)}
  50%{transform:translate(120px,-90px)}
  100%{transform:translate(0,0)}
}

/* ========== CENTER LAYOUT ========== */
.wrapper{
  height:100vh;
  display:flex;
  justify-content:center;
  align-items:center;
}

/* ========== LOGIN CARD ========== */
.login-card{
  position:relative;
  width:360px;
  padding:42px 36px;
  border-radius:28px;
  background:rgba(255,255,255,.14);
  backdrop-filter:blur(28px);
  box-shadow:0 60px 140px rgba(0,0,0,.55);
  color:#fff;
  text-align:center;

  opacity:0;
  transform:translateY(90px) scale(.97);
  animation:appleFloatIn 1.6s cubic-bezier(.16,1,.3,1) forwards;
}
@keyframes appleFloatIn{
  to{opacity:1;transform:translateY(0) scale(1)}
}

/* GLOW FOLLOW EFFECT */
.login-card::before{
  content:"";
  position:absolute;
  inset:-3px;
  border-radius:inherit;
  background:radial-gradient(circle at var(--x,50%) var(--y,50%), #ffffff25, transparent 60%);
  pointer-events:none;
}

/* TITLE */
.login-card h4{
  margin-bottom:28px;
  font-weight:800;
  letter-spacing:.4px;
}

/* INPUTS */
.login-card input{
  width:100%;
  padding:14px;
  margin-bottom:16px;
  border-radius:16px;
  border:none;
  background:rgba(255,255,255,.2);
  color:#fff;
  font-size:15px;
}
.login-card input::placeholder{color:#e5e7eb}
.login-card input:focus{
  outline:none;
  box-shadow:0 0 0 2px rgba(255,255,255,.5);
}

/* BUTTON */
.login-card button{
  width:100%;
  padding:14px;
  border-radius:18px;
  border:none;
  background:linear-gradient(135deg,#fbbf24,#f97316);
  font-weight:700;
  font-size:16px;
  cursor:pointer;
  transition:.3s;
}
.login-card button:hover{
  transform:translateY(-2px);
  box-shadow:0 18px 45px rgba(251,191,36,.45);
}

/* ERROR */
.alert{
  background:rgba(255,0,0,.25);
  padding:10px;
  border-radius:12px;
  margin-bottom:16px;
}

/* SOUND BUTTON */
.sound-toggle{
  position:fixed;
  bottom:28px;
  right:28px;
  width:56px;
  height:56px;
  border-radius:50%;
  background:rgba(255,255,255,.18);
  backdrop-filter:blur(14px);
  display:flex;
  align-items:center;
  justify-content:center;
  font-size:22px;
  cursor:pointer;
  transition:.3s;
}
.sound-toggle:hover{transform:scale(1.1)}
</style>
</head>

<body>

<!-- BACKGROUND VIDEO -->
<div class="video-bg">
  <video id="bgVideo" src="s.mp4" autoplay loop playsinline preload="auto"></video>
</div>

<div class="overlay"></div>
<div class="aurora"></div>

<div class="wrapper">
  <form method="post" class="login-card" id="card">

    <h4><i class="fa-solid fa-user-shield"></i> Admin Login</h4>

    <?php if(isset($error)): ?>
      <div class="alert"><?= $error ?></div>
    <?php endif; ?>

    <input type="text" name="username" placeholder="👤 Username" required>
    <input type="password" name="password" placeholder="🔒 Password" required>

    <button name="login">Login</button>

  </form>
</div>

<div class="sound-toggle" id="soundBtn">🔊</div>

<script>
/* GLOW FOLLOW */
const card=document.getElementById("card");
document.addEventListener("mousemove",e=>{
  card.style.setProperty("--x",(e.clientX/innerWidth)*100+"%");
  card.style.setProperty("--y",(e.clientY/innerHeight)*100+"%");
});

/* VIDEO SOUND */
const video=document.getElementById("bgVideo");
const btn=document.getElementById("soundBtn");
video.volume=0.6;

video.play().catch(()=>{
  video.muted=true;
  btn.textContent="🔇";
});

document.addEventListener("click",()=>{
  video.muted=false;
  video.play();
  btn.textContent="🔊";
},{once:true});

btn.onclick=()=>{
  video.muted=!video.muted;
  btn.textContent=video.muted?"🔇":"🔊";
};
</script>

</body>
</html>
