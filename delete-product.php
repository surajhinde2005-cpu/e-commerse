<?php
session_start();
if(!isset($_SESSION['admin'])){
  header("Location: login.php");
  exit;
}
?>

<?php
include "dbconne.php";

$id = (int)$_GET['id'];

$conn->query("DELETE FROM product WHERE product_id=$id");

header("Location: products.php");
