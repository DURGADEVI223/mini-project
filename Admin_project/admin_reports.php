<?php
session_start();
include 'conn.php';
include 'header.php';

if(!isset($_SESSION['admin_id'])){
    header("Location: admin_login.php");
    exit();
}
?>
<style>

.page-title-box {
    background:linear-gradient(90deg, #6f42c1, #8b5cf6);
    color:white;
    padding:22px 25px;
    border-radius:12px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    font-size:18px;
}
.card {
    border-radius: 12px;
}
.card-header {
    font-weight: bold;
    font-size: 16px;
}
</style>

<div class="container mt-4">

<div class="page-title-box mb-3">
    SALES REPORT
</div>

<!-- SEARCH + FILTER -->
<div class="card p-3 mb-3">

<input type="text" id="search_name" placeholder="Search Customer" class="form-control mb-2">

<input type="date" id="search_date" class="form-control mb-2">

<select id="search_category" class="form-control mb-2">
    <option value="">All Category</option>
    <option>Adult</option>
    <option>Child</option>
</select>

<select id="search_status" class="form-control mb-2">
    <option value="">All Status</option>
    <option>Pending</option>
    <option>Paid</option>
    <option>Confirmed</option>
</select>

</div>

<!-- AJAX RESULT -->
<div id="result"></div>

<hr>

<!-- DAILY REPORT -->
<div class="card shadow-sm mb-4">
<div class="card-header bg-primary text-white">Daily Sales</div>
<div class="card-body">

<?php
$q1 = $conn->query("
SELECT booking_date,
COUNT(*) as total_booking,
SUM(total_price) as total_revenue
FROM bookings
GROUP BY booking_date
ORDER BY booking_date DESC
");
?>

<table class="table table-bordered">
<tr>
<th>Date</th>
<th>Total Booking</th>
<th>Total Revenue</th>
</tr>

<?php while($r = $q1->fetch_assoc()): ?>
<tr>
<td><?= $r['booking_date'] ?></td>
<td><?= $r['total_booking'] ?></td>
<td>RM <?= number_format($r['total_revenue'],2) ?></td>
</tr>
<?php endwhile; ?>

</table>

</div>
</div>

<!-- MONTHLY REPORT -->
<div class="card shadow-sm mb-4">
<div class="card-header bg-success text-white">Monthly Sales</div>
<div class="card-body">

<?php
$q2 = $conn->query("
SELECT DATE_FORMAT(booking_date,'%Y-%m') as month,
COUNT(*) as total_booking,
SUM(total_price) as total_revenue
FROM bookings
GROUP BY month
");
?>

<table class="table table-bordered">
<tr>
<th>Month</th>
<th>Total Booking</th>
<th>Total Revenue</th>
</tr>

<?php while($r = $q2->fetch_assoc()): ?>
<tr>
<td><?= $r['month'] ?></td>
<td><?= $r['total_booking'] ?></td>
<td>RM <?= number_format($r['total_revenue'],2) ?></td>
</tr>
<?php endwhile; ?>

</table>

</div>
</div>

<!-- CATEGORY REPORT -->
<div class="card shadow-sm mb-4">
<div class="card-header bg-dark text-white">Ticket Category Analysis</div>
<div class="card-body">

<?php
$q3 = $conn->query("
SELECT ticket_category, COUNT(*) as total
FROM bookings
GROUP BY ticket_category
ORDER BY total DESC
");
?>

<table class="table table-bordered">
<tr>
<th>Category</th>
<th>Total</th>
</tr>

<?php while($r = $q3->fetch_assoc()): ?>
<tr>
<td><?= $r['ticket_category'] ?></td>
<td><?= $r['total'] ?></td>
</tr>
<?php endwhile; ?>

</table>

</div>
</div>

<!-- GRAPH BOX -->
<div class="card shadow-sm mt-4">
    <div class="card-header bg-info text-white">
        Daily Revenue Graph
    </div>
    <div class="card-body">
        <canvas id="myChart" height="100"></canvas>
    </div>
</div>

</div>

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

// AJAX LIVE SEARCH
function loadData(){
    $.ajax({
        url: "search_booking.php",
        method: "GET",
        data: {
            name: $("#search_name").val(),
            date: $("#search_date").val(),
            category: $("#search_category").val(),
            status: $("#search_status").val()
        },
        success: function(data){
            $("#result").html(data);
        }
    });
}

$("#search_name, #search_date, #search_category, #search_status")
.on("keyup change", function(){
    loadData();
});

loadData();

// GRAPH
fetch('chart_data.php')
.then(res => res.json())
.then(data => {

    const labels = data.map(d => d.booking_date);
    const values = data.map(d => d.total);

    new Chart(document.getElementById('myChart'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Daily Revenue',
                data: values
            }]
        },
        options: {
            animation: {
                duration: 1500
            }
        }
    });

});
</script>

<?php include 'footer.php'; ?>