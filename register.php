<?php
session_start();
include "dbconne.php";

if (isset($_POST['register'])) {
  $name  = trim($_POST['name']);
  $email = trim($_POST['email']);
  $pass  = password_hash($_POST['password'], PASSWORD_DEFAULT);

  $check = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");
  if (mysqli_num_rows($check) > 0) {
    $error = "Email already registered";
  } else {
    mysqli_query($conn,
      "INSERT INTO users (name,email,password)
       VALUES ('$name','$email','$pass')"
    );
    header("Location: userlogin.php");
    exit;
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Create Account</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
*{box-sizing:border-box}
html,body{
  margin:0;
  height:100%;
  font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;
  overflow:hidden;
}

/* ================= VIDEO BACKGROUND ================= */
.video-bg{
  position:fixed;
  inset:0;
  z-index:-3;
}
.video-bg video{
  width:100%;
  height:100%;
  object-fit:cover;
  filter:brightness(1.15) contrast(1.12) saturate(1.1);
}

/* CINEMATIC OVERLAY */
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

/* AURORA LIGHT */
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

/* ================= CENTER ================= */
.wrapper{
  height:100vh;
  display:flex;
  justify-content:center;
  align-items:center;
}

/* ================= CARD ================= */
.card{
  width:360px;
  padding:44px 36px;
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
  to{
    opacity:1;
    transform:translateY(0) scale(1);
  }
}

/* GLOW FOLLOW */
.card::before{
  content:"";
  position:absolute;
  inset:-3px;
  border-radius:inherit;
  background:radial-gradient(circle at var(--x,50%) var(--y,50%), #ffffff25, transparent 60%);
  pointer-events:none;
}

/* TEXT */
.card h2{
  margin-bottom:26px;
  font-weight:700;
}

/* INPUTS */
.card input{
  width:100%;
  padding:15px;
  margin-bottom:16px;
  border-radius:16px;
  border:none;
  background:rgba(255,255,255,.2);
  color:#fff;
}
.card input::placeholder{color:#e5e7eb}
.card input:focus{
  outline:none;
  box-shadow:0 0 0 2px rgba(255,255,255,.5);
}

/* BUTTON */
.card button{
  width:100%;
  padding:15px;
  border-radius:18px;
  border:none;
  background:linear-gradient(135deg,#fbbf24,#f97316);
  font-weight:600;
  font-size:16px;
  cursor:pointer;
  transition:.3s;
}
.card button:hover{
  transform:translateY(-2px);
  box-shadow:0 18px 45px rgba(251,191,36,.45);
}

/* LINKS */
.links{
  margin-top:20px;
  font-size:14px;
}
.links a{
  color:#fde68a;
  text-decoration:none;
}
.links a:hover{text-decoration:underline}

/* ERROR */
.error{
  background:rgba(255,0,0,.25);
  padding:10px;
  border-radius:12px;
  margin-bottom:16px;
}

/* SOUND ICON */
.sound{
  position:fixed;
  bottom:28px;
  right:28px;
  width:54px;
  height:54px;
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
.sound:hover{transform:scale(1.1)}
</style>
</head>

<body>

<!-- VIDEO -->
<div class="video-bg">
  <video id="bgVideo" src="your_name.mp4" autoplay loop playsinline preload="auto"></video>
</div>

<div class="overlay"></div>
<div class="aurora"></div>

<div class="wrapper">
  <div class="card" id="card">
    <h2>Create Account</h2>

    <?php if(isset($error)): ?>
      <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
      <input type="text" name="name" placeholder="Full Name" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <button name="register">Register</button>
    </form>

    <div class="links">
      <a href="userlogin.php">Already have an account?</a><br><br>
      <a href="frontpage.php">Back to Home</a>
    </div>
  </div>
</div>

<div class="sound" id="soundBtn">🔊</div>

<script>
/* GLOW FOLLOW */
const card = document.getElementById("card");
document.addEventListener("mousemove", e=>{
  card.style.setProperty("--x",(e.clientX/innerWidth)*100+"%");
  card.style.setProperty("--y",(e.clientY/innerHeight)*100+"%");
});

/* VIDEO + AUDIO (MAX POSSIBLE AUTOPLAY) */
const video = document.getElementById("bgVideo");
const btn   = document.getElementById("soundBtn");

video.volume = 0.75;
video.muted = false;

const attempt = video.play();
if (attempt !== undefined) {
  attempt.catch(() => {
    video.muted = true;
    video.play();
    btn.textContent = "🔇";

    const unlock = () => {
      video.muted = false;
      video.play();
      btn.textContent = "🔊";
      document.removeEventListener("click", unlock);
      document.removeEventListener("scroll", unlock);
      document.removeEventListener("keydown", unlock);
    };

    document.addEventListener("click", unlock);
    document.addEventListener("scroll", unlock);
    document.addEventListener("keydown", unlock);
  });
}

btn.onclick = () => {
  video.muted = !video.muted;
  btn.textContent = video.muted ? "🔇" : "🔊";
};
</script>

</body>
</html>
