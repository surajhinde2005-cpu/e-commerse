<?php
session_start();
include "dbconne.php";

// Only logged-in users can submit
if (!isset($_SESSION['user_id'])) {
    exit; // stop if not logged in
}

// Get data from AJAX request
$data = json_decode(file_get_contents("php://input"), true);

$product_id = (int)$data['id'];       // the product being reviewed
$rating     = (int)$data['rating'];   // rating value
$comment    = $conn->real_escape_string(trim($data['comment'])); // escape input

$user_id   = (int)$_SESSION['user_id'];
$user_name = $conn->real_escape_string($_SESSION['user_name']);

// Insert into reviews table
$conn->query("
    INSERT INTO reviews (product_id, user_id, user_name, rating, comment, created_at)
    VALUES ($product_id, $user_id, '$user_name', $rating, '$comment', NOW())
");

// Optional: return success response
echo json_encode(['status' => 'success']);
