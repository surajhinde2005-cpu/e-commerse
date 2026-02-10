<?php
session_start();
include "dbconne.php";

$id = (int)$_GET['id'];

$p = $conn->query("SELECT * FROM product WHERE product_id=$id")->fetch_assoc();

if(!isset($_SESSION['cart'])){
  $_SESSION['cart'] = [];
}

if(isset($_SESSION['cart'][$id])){
  $_SESSION['cart'][$id]['qty'] += 1;
} else {
  $_SESSION['cart'][$id] = [
    'name'  => $p['name'],
    'price' => $p['price'],
    'image' => $p['image'],
    'qty'   => 1
  ];
}

header("Location: cart.php");

// javascript



