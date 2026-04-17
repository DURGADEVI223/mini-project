
<?php
session_start();
include 'conn.php';

if(!isset($_SESSION['admin_id'])){
    header("Location: admin_login.php");
    exit();
}

if(!isset($_GET['id'])){
    die("Invalid request.");
}

$id = intval($_GET['id']);

/* EXTRA VALIDATION */
if(!is_numeric($id)){
    die("Invalid ID");
}

// Ensure booking exists AND status is PAID
$check = $conn->prepare("SELECT status FROM bookings WHERE id=?");
$check->bind_param("i", $id);
$check->execute();
$check->store_result();

if($check->num_rows == 0){
    die("Booking not found.");
}

$check->bind_result($status);
$check->fetch();

if($status != "Paid"){
    die("Only PAID bookings can be confirmed.");
}

$stmt = $conn->prepare("UPDATE bookings SET status='Confirmed' WHERE id=?");
$stmt->bind_param("i", $id);

if($stmt->execute()){
    echo "<script>alert('Booking confirmed successfully!');window.location='admin_bookings.php';</script>";
} else {
    echo "<script>alert('Error. Try again.');window.location='admin_bookings.php';</script>";
}
?>