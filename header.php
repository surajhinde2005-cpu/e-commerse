<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
?>

<div class="navbar">
  <!-- LEFT SIDE (logo, menu etc – your existing code) -->

  <div class="nav-right">

    <?php if(isset($_SESSION['user_id'])): ?>

      <!-- Logged in -->
      <div class="user-menu">
        👤 <?= htmlspecialchars($_SESSION['user_name']) ?>
        <div class="dropdown">
          <a href="cart.php">🛒 Cart</a>
          <a href="orders.php">📦 Orders</a>
          <a href="logout.php">🚪 Logout</a>
        </div>
      </div>

    <?php else: ?>

      <!-- Guest -->
      <a href="userlogin.php" class="login-link">👤 Login</a>

    <?php endif; ?>

  </div>
</div>
