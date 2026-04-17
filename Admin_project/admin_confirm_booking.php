<?php
session_start();
include 'conn.php';

if(!isset($_SESSION['admin_id'])){
    header("Location: admin_login.php");
    exit();
}

if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
    die("Invalid ID");
}

$id = intval($_GET['id']);

/* CHECK STATUS */
$check = $conn->prepare("SELECT status FROM bookings WHERE id=?");
$check->bind_param("i", $id);
$check->execute();
$result = $check->get_result();

if($result->num_rows == 0){
    die("Booking not found.");
}

$row = $result->fetch_assoc();

if($row['status'] != "Paid"){
    die("Only PAID bookings can be confirmed.");
}

/* UPDATE */
$stmt = $conn->prepare("UPDATE bookings SET status='Confirmed' WHERE id=?");
$stmt->bind_param("i", $id);

if($stmt->execute()){
    header("Location: admin_bookings.php");
} else {
    echo "Error.";
}
?>