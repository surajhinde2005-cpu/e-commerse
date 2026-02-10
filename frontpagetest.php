<?php include "dbconne.php"; ?>

<div class="row">
<?php
$q = $conn->query("SELECT * FROM product WHERE status=1");
while($p = $q->fetch_assoc()):
?>
  <div class="col-md-3">
    <div class="card">
      <img src="uploads/<?= $p['image'] ?>" class="card-img-top">
      <div class="card-body">
        <h5><?= $p['name'] ?></h5>
        <p>₹<?= $p['price'] ?></p>
      </div>
    </div>
  </div>
<?php endwhile; ?>
</div>
