<?php
session_start();
include "conn.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("DELETE FROM bookings WHERE id=? AND user_id=?");
$stmt->bind_param("ii", $id, $user_id);

//jalankan query delete
$stmt->execute();

header("Location: booking_history.php");
exit();
?>