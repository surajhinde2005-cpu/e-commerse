<?php
session_start();
include "dbconne.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die("❌ Please login first");
}

$user_id = $_SESSION['user_id'];

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: checkout.php");
    exit;
}

// ----------------------------
// CALCULATE CART TOTAL
// ----------------------------

if (!isset($_SESSION['user_id'])) die("Please login first");

// Get user input
$fname = $_POST['fname'];
$lname = $_POST['lname'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$address = $_POST['address'];

// Get amount from form, NOT from session
$amount = isset($_POST['amount']) ? (float)$_POST['amount'] : 0;
if ($amount <= 0) die("Invalid amount");

// Handle payment screenshot
$file = $_FILES['payment_image'];
// (validate upload and move to $uploadPath)

// Insert order
$stmt = $conn->prepare("
    INSERT INTO orders
    (user_id,fname,lname,email,phone,address,amount,payment_image)
    VALUES (?,?,?,?,?,?,?,?)
");

$stmt->bind_param("isssssds", $_SESSION['user_id'], $fname, $lname, $email, $phone, $address, $amount, $uploadPath);
$stmt->execute();

// Clear cart
unset($_SESSION['cart']);

header("Location: checkout.php?success=1");
exit;


// ----------------------------
// HANDLE PAYMENT IMAGE
// ----------------------------
if (!isset($_FILES['payment_image'])) {
    die("❌ Payment screenshot is required");
}

$file = $_FILES['payment_image'];

// Check upload errors
if ($file['error'] !== UPLOAD_ERR_OK) {
    die("❌ File upload error");
}

// Check file size (max 2MB)
if ($file['size'] > 2 * 1024 * 1024) {
    die("❌ Screenshot too large. Max 2MB allowed.");
}

// Validate real image
$imageInfo = getimagesize($file['tmp_name']);
if ($imageInfo === false) {
    die("❌ Invalid image file");
}

// Only allow JPG or PNG
$allowedTypes = ['image/jpeg', 'image/png'];
if (!in_array($imageInfo['mime'], $allowedTypes)) {
    die("❌ Only JPG or PNG allowed");
}

// Secure filename
$ext = ($imageInfo['mime'] === 'image/png') ? 'png' : 'jpg';
$newName = 'payment_' . time() . '_' . rand(1000,9999) . '.' . $ext;

// Upload directory
$uploadDir = "uploads/payments/";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$uploadPath = $uploadDir . $newName;

if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
    die("❌ Failed to save payment screenshot");
}

// ----------------------------
// INSERT INTO DATABASE
// ----------------------------
$stmt = $conn->prepare("
    INSERT INTO orders
    (user_id, fname, lname, email, phone, address, amount, payment_image)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
");

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param(
    "isssssds", // i=integer, s=string, d=double
    $user_id,
    $fname,
    $lname,
    $email,
    $phone,
    $address,
    $amount,
    $uploadPath
);

if (!$stmt->execute()) {
    die("Execute failed: " . $stmt->error);
}

// ----------------------------
// CLEAR CART
// ----------------------------
unset($_SESSION['cart']);

// ----------------------------
// REDIRECT WITH SUCCESS
// ----------------------------
header("Location: checkout.php?success=1");
exit;
