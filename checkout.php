<?php include "navbar.php"; ?>
s<?php
// if (!isset($_SESSION['user_id'])) {
//   header("Location: userlogin.php");
//   exit;
// }

$guest = !isset($_SESSION['user_id']);





$total = 0;

if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
  foreach ($_SESSION['cart'] as $item) {
    if (isset($item['price'], $item['qty'])) {
      $total += $item['price'] * $item['qty'];
    }
  }
}

?>

<!DOCTYPE html>
<html>
<head>
<title>Checkout</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{background:#f1f3f6}
.box{background:#fff;border-radius:12px;padding:20px}
</style>
</head>

<body>

<div class="container my-5">
<div class="box">
  <?php if (isset($_GET['success'])): ?>
<div class="alert alert-success text-center">
  <h5 class="mb-1">✅ Payment Successful!</h5>
  <p class="mb-0">Thank you for your order. We’ll contact you shortly.</p>
</div>
<?php endif; ?>


<h3 class="mb-4">Checkout</h3>

<form action="place-order.php" method="POST" enctype="multipart/form-data">

<div class="row g-3">

<div class="col-md-6">
<input type="text" name="fname" class="form-control" placeholder="First Name" required>
</div>

<div class="col-md-6">
<input type="text" name="lname" class="form-control" placeholder="Last Name" required>
</div>

<div class="col-md-6">
<input type="email" name="email" class="form-control" placeholder="Email" required>
</div>

<div class="col-md-6">
<input type="text" name="phone" class="form-control" placeholder="Phone Number" required>
</div>

<div class="col-12">
<textarea name="address" class="form-control" placeholder="Full Address" rows="3" required></textarea>
</div>

<div class="col-md-6">
<label class="form-label">Total Amount</label>
<input type="number"
       name="amount"
       class="form-control"
       value="<?= $total ?>"
       step="0.01"
       >
</div>


<div class="col-md-6">
<label class="form-label">Payment Screenshot</label>
<input type="file" name="payment_image" class="form-control" accept="image/*" required>
</div>

</div>

<hr>

<button type="submit" class="btn btn-success w-100 btn-lg">
Place Order
</button>

</form>

</div>
</div>

</body>
</html>