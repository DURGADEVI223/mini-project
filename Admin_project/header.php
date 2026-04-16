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
<title>Admin Panel - Bird Park</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    min-height: 100vh;
    background: #f7f7f7;
    position: relative;
}

/* BACKGROUND IMAGE (CLEAR + DARK OVERLAY) */
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

/* NAVBAR */
.navbar-admin {
    background: #6f42c1;
}

/* CONTENT CARD (GLASS EFFECT) */
.content-card {
    background: rgba(255,255,255,0.9);
    padding: 30px;
    border-radius: 12px;
    backdrop-filter: blur(5px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
</style>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark navbar-admin">
    <div class="container">
        <a class="navbar-brand fw-bold" href="admin_login.php">BIRD PARK</a>

        <div class="ms-auto">

<?php if(isset($_SESSION['admin_id'])){ ?>

    <span class="text-white me-3">
        WELCOME, <?= $_SESSION['admin_name']; ?>
    </span>

    <a href="admin_dashboard.php" class="btn btn-light btn-sm me-1">Dashboard</a>
    <a href="admin_user.php" class="btn btn-light btn-sm me-1">User Management</a>
    <a href="admin_bookings.php" class="btn btn-light btn-sm me-1">Bookings</a>
    <a href="admin_reports.php" class="btn btn-light btn-sm me-1">Reports</a>

    <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>

<?php } else { ?>

    <a href="admin_login.php" class="btn btn-light btn-sm me-2">Login</a>
    <a href="admin_register.php" class="btn btn-outline-light btn-sm">Register</a>

<?php } ?>

        </div>
    </div>
</nav>

<div class="container mt-5">