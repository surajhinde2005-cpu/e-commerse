<?php
session_start();
include "dbconne.php";
?>


<?php include "navbar.php"; ?>

<!DOCTYPE html>
<html>
<head>
  <title>My Cart</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-5">
  <h2>🛒 My Cart</h2>

<?php
// check if cart is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])):
?>

  <div class="alert alert-warning mt-4">
    Your cart is empty
  </div>

<?php else: ?>

<table class="table table-bordered mt-4">
  <tr>
    <th>Product</th>
    <th>Price</th>
    <th>Qty</th>
    <th>Total</th>
    <th>Action</th>
  </tr>

<?php
$grand_total = 0;

foreach ($_SESSION['cart'] as $pid => $qty):
  $result = mysqli_query($conn, "SELECT * FROM products WHERE product_id = $pid");
  $p = mysqli_fetch_assoc($result);

  $total = $p['price'] * $qty;
  $grand_total += $total;
?>

<tr>
  <td><?= htmlspecialchars($p['name']) ?></td>
  <td>₹<?= $p['price'] ?></td>
  <td><?= $qty ?></td>
  <td>₹<?= $total ?></td>
  <td>
    <a href="remove_cart.php?id=<?= $pid ?>" class="btn btn-danger btn-sm">
      Remove
    </a>
  </td>
</tr>

<?php endforeach; ?>

<tr>
  <th colspan="3">Grand Total</th>
  <th colspan="2">₹<?= $grand_total ?></th>
</tr>

</table>

<a href="checkout.php" class="btn btn-success">
  Proceed to Checkout →
</a>

<?php endif; ?>

</div>

</body>
</html>
