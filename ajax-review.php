<?php
session_start();
include "dbconne.php";

if(!isset($_SESSION['user_id'])) exit;

$data = json_decode(file_get_contents("php://input"), true);

$product_id = (int)$data['product_id'];
$rating = (int)$data['rating'];
$comment = trim($data['comment']);

$stmt = $conn->prepare("
    INSERT INTO reviews(product_id, user_id, rating, comment, created_at)
    VALUES (?, ?, ?, ?, NOW())
");

$stmt->bind_param("iiis", $product_id, $_SESSION['user_id'], $rating, $comment);
$stmt->execute();
