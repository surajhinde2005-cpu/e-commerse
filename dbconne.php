

<?php
$host = "127.0.0.1";   // FIX: force IPv4
$user = "root";
$pass = "";
$db   = "shop_db";
$port = 3306;          // keep default unless you changed it

$conn = mysqli_connect("localhost", "root", "", "shop_db");

if ($conn->connect_error) {
    die("DB Connection Failed: " . $conn->connect_error);
}
?>
