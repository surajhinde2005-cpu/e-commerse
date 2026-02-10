<?php
session_start();
include "dbconne.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $email    = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';

  if ($email !== '' && $password !== '') {

    $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
      $user = $result->fetch_assoc();

      // ✅ VERIFY PASSWORD
      if (password_verify($password, $user['password'])) {

        session_regenerate_id(true);

        // ✅ USE SAME SESSION KEY EVERYWHERE
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_name'] = $user['name'];

        header("Location: frontpage.php");
        exit;
      }
    }
  }

  // ❌ Login failed
  echo "Invalid email or password";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login • ShopNow</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
*{box-sizing:border-box}
html,body{
  margin:0;
  height:100%;
  font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;
}

/* VIDEO BACKGROUND */
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

.wrapper{
  height:100vh;
  display:flex;
  justify-content:center;
  align-items:center;
}

.login-box{
  width:360px;
  padding:44px 36px;
  border-radius:28px;
  background:rgba(255,255,255,.14);
  backdrop-filter:blur(28px);
  box-shadow:0 60px 140px rgba(0,0,0,.55);
  color:#fff;
  text-align:center;
  animation:fadeIn 1.3s ease forwards;
}

@keyframes fadeIn{
  from{opacity:0;transform:translateY(80px)}
  to{opacity:1;transform:none}
}

.login-box input{
  width:100%;
  padding:15px;
  margin-bottom:16px;
  border-radius:16px;
  border:none;
  background:rgba(255,255,255,.2);
  color:#fff;
}

.login-box button{
  width:100%;
  padding:15px;
  border-radius:18px;
  border:none;
  background:linear-gradient(135deg,#fbbf24,#f97316);
  font-weight:600;
  cursor:pointer;
}


/* register and back to login */
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

/* error */
.error{
  background:rgba(255,0,0,.25);
  padding:10px;
  border-radius:12px;
  margin-bottom:16px;
}

.sound-toggle{
  position:fixed;
  bottom:30px;
  right:30px;
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
}
</style>
</head>

<body>

<div class="video-bg">
  <video id="bgVideo" src="anime.mp4" autoplay loop playsinline preload="auto"></video>
</div>

<div class="overlay"></div>
<div class="aurora"></div>

<div class="wrapper">
  <div class="login-box" id="card">

    <h2>Welcome Back</h2>

    <?php if(isset($error)): ?>
      <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
      <input type="email" name="email" placeholder="👤 Username" required>

      <input type="password" name="password" placeholder="🔒 Password" required>
      <button name="login">Login</button>
    </form>
     <div class="links">
      <a href="register.php">Register Now</a><br><br>
      <a href="frontpage.php">Back to Home</a>
    </div>
  </div>
</div>

<div class="sound-toggle" id="soundBtn">🔊</div>

<script>
const card = document.getElementById("card");
document.addEventListener("mousemove", e=>{
  card.style.setProperty("--x",(e.clientX/innerWidth)*100+"%");
  card.style.setProperty("--y",(e.clientY/innerHeight)*100+"%");
});

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
