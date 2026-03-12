<?php
session_start();
include "dbconne.php";

$search = '';
if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
}

/* ✅ ONLY CHANGE: DIRECT PRODUCT REDIRECT */
if ($search !== '') {

    $stmt = $conn->prepare(
        "SELECT product_id FROM products WHERE name = ? LIMIT 1"
    );
    $stmt->bind_param("s", $search);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $row = $res->fetch_assoc();
        header("Location: product.php?id=" . $row['product_id']);
        exit;
    }


}
/* 🔥 AUTO REDIRECT FOR MOBILE SEARCH */ 
if ($search !== '') { 
  $key = strtolower($search);
 if (strpos($key, 'mobile') !== false) {
   header("Location: mobile.php");
    exit; 
  } 
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Search Results</title>
  <style>
    body{font-family:Arial;padding:30px;}
    .product{border:1px solid #ddd;padding:15px;margin:10px 0;}
  </style>
</head>
<body>

<h2>Search results for: <b><?= htmlspecialchars($search) ?></b></h2>

<?php
if ($search != '') {

    // ❗ SAME QUERY — NOT CHANGED
    $stmt = $conn->prepare(
        "SELECT * FROM product WHERE name LIKE ?"
    );

    $like = "%" . $search . "%";
    $stmt->bind_param("s", $like);
    $stmt->execute();
    $q = $stmt->get_result();

} else {
    echo "Please enter a search term.";
    exit;
}

if ($q->num_rows > 0):
  while ($p = $q->fetch_assoc()):
?>

  <div class="product">
    <h3><?= $p['name'] ?></h3>
    <p>₹<?= $p['price'] ?></p>
    <a href="product.php?id=<?= $p['product_id'] ?>">View</a>
  </div>

<?php
  endwhile;
else:
  echo "<p style='color:red;'>No products found.</p>";
endif;
?>

</body>
</html>
