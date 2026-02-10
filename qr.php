<?php
// qr.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Scan QR</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
body{
  margin:0;
  height:100vh;
  display:flex;
  align-items:center;
  justify-content:center;
  background:#0f172a;
}

.qr-box{
  background:#fff;
  padding:25px;
  border-radius:18px;
  box-shadow:0 20px 40px rgba(0,0,0,.35);
  text-align:center;
}

.qr-box img{
  width:260px;
  height:260px;
  object-fit:contain;
  margin-bottom:18px;
}

/* BUTTON */
.btn-submit{
  width:100%;
  padding:12px;
  border:none;
  border-radius:12px;
  background:linear-gradient(120deg,#2874f0,#6a11cb);
  color:#fff;
  font-size:16px;
  font-weight:600;
  cursor:pointer;
}
.btn-submit:hover{
  opacity:.9;
}
</style>
</head>

<body>

<div class="qr-box">

  <img src="WhatsApp Image 2026-02-09 at 12.20.16 AM.jpeg" alt="Scan QR to Pay">

  <!-- SUBMIT FORM -->
  <form action="checkout.php" method="post">
    <button type="submit" class="btn-submit">
      ✅ Payment Done – Submit Form
    </button>
  </form>

</div>

</body>
</html>
