<?php
session_start();
include "conn.php";

if (!isset($_SESSION['user_id'])) {
    die("Access denied");
}

$id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

/* CHECK BOOKING OWNER */
$stmt = $conn->prepare("SELECT receipt FROM bookings WHERE id=? AND user_id=?");
$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Not allowed");
}

$row = $result->fetch_assoc();

$file = basename($row['receipt']);
$path = "../uploads/receipts/" . $user_id . "/" . $file;

if (!file_exists($path)) {
    die("File not found");
}

/* FORCE DOWNLOAD */
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="'.$file.'"');
readfile($path);
exit;
?>