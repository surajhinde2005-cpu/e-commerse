<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header("Content-Type: application/json");
include "dbconne.php";

if(!isset($_SESSION['user_id'])){
    echo json_encode(["success"=>false]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$id = (int)($data['id'] ?? 0);

$stmt = $conn->prepare("
    DELETE FROM reviews 
    WHERE id=? AND user_id=?
");
$stmt->bind_param("ii",$id,$_SESSION['user_id']);
$stmt->execute();

echo json_encode(["success"=>true]);
?>
