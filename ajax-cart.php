<?php
session_start();
if(!isset($_SESSION['user_id'])){
  http_response_code(401);
  exit("Login required");
}


$data = json_decode(file_get_contents("php://input"), true);

$id = (int)$data['id'];
$qty = (int)$data['qty'];

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
$_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + $qty;

echo json_encode(["success"=>true,"count"=>array_sum($_SESSION['cart'])]);
