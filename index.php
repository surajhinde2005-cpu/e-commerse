<?php include "header.php"; ?>
<?php include "navbar.php"; ?>

<?php
include "dbconne.php"; ?>

<div class="product-grid">
<?php
$q = $conn->query("SELECT * FROM product WHERE status=1");
while($p = $q->fetch_assoc()):
?>
  <div class="card">
    <img src="../uploads/<?= $p['image'] ?>">
    <h4><?= $p['name'] ?></h4>
    <p>₹<?= $p['price'] ?></p>
    <a href="product.php?id=<?= $p['id'] ?>" class="btn">View</a>
  </div>
<?php endwhile; ?>
</div>
