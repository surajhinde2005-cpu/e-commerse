<?php
include "dbconne.php";

$site = $conn->query("SELECT * FROM settings LIMIT 1")->fetch_assoc();

$site_name = $site['site_name'] ?? "My Website";
$site_logo = $site['logo'] ?? "uploads/default.png";
?>
