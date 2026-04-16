<?php
session_start();
include "conn.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM bookings WHERE id=? AND user_id=?");
$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Ticket not found");
}

$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
<title>Bird Park Ticket</title>

<style>
body{
    font-family: 'Segoe UI', sans-serif;
    background:#f0f2f5;
    padding:40px;
}

/* MAIN TICKET */
.ticket{
    max-width:750px;
    margin:auto;
    background:white;
    border-radius:15px;
    overflow:hidden;
    box-shadow:0 10px 30px rgba(0,0,0,0.2);
    display:flex;
    position:relative;
}

/* LEFT */
.left{
    width:65%;
    padding:25px;
}

/* RIGHT */
.right{
    width:35%;
    background:#6f42c1;
    color:white;
    text-align:center;
    padding:25px;
}

/* HEADER */
.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
}

.logo{
    font-size:22px;
    font-weight:bold;
    color:#6f42c1;
}

.ticket-id{
    font-size:13px;
    color:gray;
}

/* TITLE */
.title{
    font-size:20px;
    font-weight:bold;
    margin-bottom:10px;
}

/* INFO */
.info p{
    margin:6px 0;
    font-size:14px;
}

/* BADGE */
.badge{
    display:inline-block;
    padding:6px 12px;
    border-radius:20px;
    font-size:12px;
    font-weight:bold;
}

.confirmed{background:#22c55e;color:white;}
.paid{background:#3b82f6;color:white;}
.pending{background:#f59e0b;color:white;}

/* PRICE */
.price{
    font-size:28px;
    font-weight:bold;
    margin:20px 0;
}

/* FOOTER */
.footer{
    margin-top:20px;
    font-size:12px;
    color:gray;
    text-align:center;
}

/* 🔥 IMPORTANT PRINT FIX */
@media print {

    body {
        background: white;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .ticket {
        box-shadow: none;
    }

    .right {
        background: #6f42c1 !important;
        color: white !important;
    }

    .confirmed {
        background: #22c55e !important;
    }

    .paid {
        background: #3b82f6 !important;
    }

    .pending {
        background: #f59e0b !important;
    }
}
</style>
</head>

<body onload="window.print()">

<div class="ticket">

    <!-- LEFT -->
    <div class="left">

        <div class="header">
            <div class="logo">🐦 Bird Park</div>
            <div class="ticket-id">Ticket ID: BP<?= $row['id']; ?></div>
        </div>

        <div class="title">Entry Ticket</div>

        <div class="info">
            <p><b>Name:</b> <?= htmlspecialchars($row['fullname']); ?></p>
            <p><b>Phone:</b> <?= htmlspecialchars($row['phone']); ?></p>
            <p><b>Date:</b> <?= htmlspecialchars($row['booking_date']); ?></p>
            <p><b>Category:</b> <?= htmlspecialchars($row['ticket_category']); ?></p>
            <p><b>Quantity:</b> <?= htmlspecialchars($row['quantity']); ?></p>
        </div>

        <br>

        <!-- STATUS -->
        <?php if($row['status']=="Confirmed"): ?>
            <span class="badge confirmed">CONFIRMED</span>
        <?php elseif($row['status']=="Paid"): ?>
            <span class="badge paid">PAID</span>
        <?php else: ?>
            
        <?php endif; ?>

        <div class="footer">
            Please present this ticket at entrance.<br>
            Valid for one-time entry only.
        </div>

    </div>

    <!-- RIGHT -->
    <div class="right">

        <h2>Bird Park</h2>
        <p>Admission Pass</p>

        <div class="price">
            RM <?= number_format($row['total_price'],2); ?>
        </div>

        <p style="font-size:12px;">
            Enjoy your visit! 🐦
        </p>

    </div>

</div>

</body>
</html>