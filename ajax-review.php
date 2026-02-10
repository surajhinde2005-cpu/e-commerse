<?php
session_start();
include "dbconne.php";

if(!isset($_SESSION['user_id'])) exit;

$data = json_decode(file_get_contents("php://input"), true);

$product_id = (int)$data['product_id'];
$rating = (int)$data['rating'];
$comment = trim($data['comment']);

$conn->query("
 INSERT INTO reviews(product_id,user_id,user_name,rating,comment)
 VALUES(
   $product_id,
   {$_SESSION['user_id']},
   '{$_SESSION['user_name']}',
   $rating,
   '$comment'
 )
");
