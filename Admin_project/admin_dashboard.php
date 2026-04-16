<?php
session_start();
include 'conn.php';
include 'header.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$pending = $conn->query("SELECT COUNT(*) as t FROM bookings WHERE status='Pending'")
->fetch_assoc()['t'];

$paid = $conn->query("SELECT COUNT(*) as t FROM bookings WHERE status='Paid'")
->fetch_assoc()['t'];

$totalUsers = $conn->query("SELECT COUNT(*) as t FROM user WHERE role='customer'")
->fetch_assoc()['t'];

$totalBookings = $conn->query("SELECT COUNT(*) as t FROM bookings")
->fetch_assoc()['t'];
?>

<style>
.container{
    max-width:1100px;
}

.topbar{
    background:linear-gradient(90deg, #6f42c1, #8b5cf6);
    color:white;
    padding:22px 25px;
    border-radius:12px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    font-size:18px;
}

.card-box{
    background:white;
    padding:35px;
    border-radius:15px;
    box-shadow:0 4px 15px rgba(0,0,0,0.08);
    text-align:center;
    height:170px;
    display:flex;
    flex-direction:column;
    justify-content:center;
}

.big{
    font-size:32px;
    font-weight:bold;
}

.label{
    color:gray;
    font-size:15px;
}

.overview{
    background:white;
    padding:25px;
    border-radius:15px;
    margin-top:25px;
    box-shadow:0 4px 10px rgba(0,0,0,0.06);
}
</style>

<div class="container mt-4">

<!-- TOP -->
<div class="topbar">
    <div>DASHBOARD</div>
    <div>Hi, <?php echo $_SESSION['admin_name']; ?></div>
</div>

<!-- STATS -->
<div class="row mt-4">

    <div class="col-md-3">
        <div class="card-box">
            <div class="big"><?php echo $totalUsers; ?></div>
            <div class="label">Total Users</div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-box">
            <div class="big"><?php echo $totalBookings; ?></div>
            <div class="label">Total Bookings</div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-box">
            <div class="big" style="color:orange;">
                <?php echo $pending; ?>
            </div>
            <div class="label">Pending</div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-box">
            <div class="big" style="color:green;">
                <?php echo $paid; ?>
            </div>
            <div class="label">Paid</div>
        </div>
    </div>

</div>

<!-- OVERVIEW -->
<div class="overview">
    <h4>System Overview</h4>
    <p>
        Bird Park Ticketing Admin Panel allows full management of users, bookings and payment status.
        All data is updated in real time for monitoring and reporting.
    </p>

    <hr>

    <p><b>System Status:</b> <span style="color:green;">Active</span></p>
    <p><b>Admin Access:</b> Full Control</p>
</div>

</div>

<?php include 'footer.php'; ?>