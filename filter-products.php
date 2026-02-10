<?php
include "dbconne.php";

$data = json_decode(file_get_contents("php://input"), true);

$sql = "SELECT * FROM product WHERE status=1";

// BRAND FILTER
if (!empty($data['brand'])) {
  $brands = array_map(function($b){
    return "'" . mysqli_real_escape_string($GLOBALS['conn'], $b) . "'";
  }, $data['brand']);

  $sql .= " AND brand IN (" . implode(",", $brands) . ")";
}

// ✅ PRICE FILTER (FIXED)
if (!empty($data['max_price'])) {
  $price = (int)$data['max_price'];
  $sql .= " AND price <= $price";
}

$result = $conn->query($sql);

while ($p = $result->fetch_assoc()):
?>
<div class="card product-card">
  <div class="share-btn" onclick="shareProduct(event, this)">📤</div>
  <div class="wishlist" onclick="toggleWish(this)">❤</div>

  <a href="product.php?id=<?= $p['product_id'] ?>" class="no-underline">
    <img src="uploads/<?= $p['image'] ?>">
    <div class="card-body">
      <h6><?= $p['name'] ?></h6>
      <p>From <b>₹<?= $p['price'] ?></b></p>
    </div>
  </a>
</div>
<?php endwhile; ?>
