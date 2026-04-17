<?php
session_start();
include "conn.php";

if(!isset($_SESSION['user_id'])){
    die("Login required");
}

$id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT receipt 
    FROM bookings 
    WHERE id=? AND user_id=?
");
$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 0){
    die("Access denied");
}

$data = $result->fetch_assoc();

$file = "../uploads/receipts/" . $data['receipt'];

if(!file_exists($file)){
    die("File not found");
}

/* AUTO DETECT FILE TYPE */
$mime = mime_content_type($file);
header("Content-Type: $mime");

/* DISPLAY FILE */
readfile($file);
exit;
?>