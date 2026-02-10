<?php
session_start();
include "dbconne.php";

/* =========================
   BASIC CART TOTAL
========================= */
$total = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
  foreach ($_SESSION['cart'] as $item) {
    if (isset($item['price'], $item['qty'])) {
      $total += $item['price'] * $item['qty'];
    }
  }
}

/* =========================
   FORM SUBMIT CHECK
========================= */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: checkout.php");
  exit;
}

/* =========================
   USER INPUT
========================= */
$fname   = $_POST['fname'];
$lname   = $_POST['lname'];
$email   = $_POST['email'];
$phone   = $_POST['phone'];
$address = $_POST['address'];
$amount  = $total;

/* =========================
   PAYMENT SCREENSHOT SECURITY
========================= */
if (!isset($_FILES['payment_image'])) {
  die("❌ Payment screenshot is required");
}

$file = $_FILES['payment_image'];

/* 1️⃣ Upload error */
if ($file['error'] !== UPLOAD_ERR_OK) {
  die("❌ File upload error");
}

/* 2️⃣ File size (max 2MB) */
if ($file['size'] > 2 * 1024 * 1024) {
  die("❌ Screenshot too large. Max 2MB allowed.");
}

/* 3️⃣ Validate REAL image */
$imageInfo = getimagesize($file['tmp_name']);
if ($imageInfo === false) {
  die("❌ Invalid image file");
}

/* 4️⃣ Allow only JPG & PNG */
$allowedTypes = ['image/jpeg', 'image/png'];
if (!in_array($imageInfo['mime'], $allowedTypes)) {
  die("❌ Only JPG or PNG payment screenshots allowed");
}

/* 5️⃣ Resolution check */
$width  = $imageInfo[0];
$height = $imageInfo[1];
if ($width < 300 || $height < 300) {
  die("❌ Invalid screenshot resolution");
}

/* 6️⃣ Secure filename */
$ext = ($imageInfo['mime'] === 'image/png') ? 'png' : 'jpg';
$newName = 'payment_' . time() . '_' . rand(1000,9999) . '.' . $ext;

/* 7️⃣ Upload directory */
$uploadDir = "uploads/payments/";
if (!is_dir($uploadDir)) {
  mkdir($uploadDir, 0777, true);
}

$uploadPath = $uploadDir . $newName;

if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
  die("❌ Failed to save payment screenshot");
}

/* =========================
   SAVE ORDER
========================= */
$stmt = $conn->prepare("
  INSERT INTO orders 
  (first_name, last_name, email, phone, address, amount, payment_image, payment_status, created_at)
  VALUES (?, ?, ?, ?, ?, ?, ?, 'pending', NOW())
");

$stmt->bind_param(
  "sssssd s",
  $fname,
  $lname,
  $email,
  $phone,
  $address,
  $amount,
  $newName
);

$stmt->execute();

/* =========================
   CLEAR CART
========================= */
unset($_SESSION['cart']);

/* =========================
   REDIRECT
========================= */
header("Location: checkout.php?success=1");
exit;
