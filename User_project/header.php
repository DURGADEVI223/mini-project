<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Bird Park Ticketing</title>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
/* ====== BACKGROUND HALUS ====== */
body {
    min-height: 100vh;
    background: #f7f7f7;
    position: relative;
}

body::before {
    content: "";
    position: fixed;
    width: 100%;
    height: 100%;
    background:
        linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)),
        url("images/birdpark_bg.jpg") center/cover no-repeat;
    z-index: -1;
}

/* ====== NAVBAR ====== */
.navbar-purple {
    background: #a855f7; /* warna purple sama dengan footer */
    color: white;
    box-shadow: 0 3px 8px rgba(0,0,0,0.15);
}

.navbar-purple .navbar-brand,
.navbar-purple .nav-link,
.navbar-purple .navbar-text {
    color: white !important;
}

.navbar-purple .nav-link:hover {
    color: #f3e8ff !important; /* highlight ringan */
}

/* ====== PAGE CONTENT WRAPPER ====== */
.page-wrapper {
    flex: 1 0 auto;
    padding: 20px 0;
}

/* ====== CARD/FORM STYLE ====== */
.content-card {
    background: rgba(255, 255, 255, 0.85);
    padding: 25px;
    border-radius: 12px;
    backdrop-filter: blur(5px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

/* ====== TABLE BOX STYLE ====== */
.table-box {
    background: rgba(255, 255, 255, 0.85);
    padding: 20px;
    border-radius: 10px;
    backdrop-filter: blur(5px);
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

/* ====== BUTTON STYLE ====== */
.btn-main {
    background: #4b79a1;
    color: white;
    border-radius: 8px;
}

.btn-main:hover {
    background: #365d80;
    color: #fff;
}

/* ====== FOOTER ====== */
footer {
    background: #a855f7; /* sama dengan navbar */
    color: white;
    padding: 15px 0;
    text-align: center;
    flex-shrink: 0;
}

footer p,
footer small {
    margin: 0;
}

</style>
</head>

<body class="d-flex flex-column">

<!-- ====== NAVBAR ====== -->
<nav class="navbar navbar-expand-lg navbar-purple shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">Bird Park</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMenu">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <span class="navbar-text me-3">Hi, <?php echo htmlspecialchars($_SESSION['fullname']); ?></span>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="booking.php">Book Tickets</a></li>
                    <li class="nav-item"><a class="nav-link" href="booking_history.php">Booking History</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- ====== PAGE CONTENT START ====== -->
<div class="container page-wrapper">