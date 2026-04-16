<?php
session_start();
include 'conn.php';
include 'header.php';

if(!isset($_SESSION['admin_id'])){
    header("Location: admin_login.php");
    exit();
}

/* PAGINATION */
$limit = 8;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

/* GET DATA */
$sql = "
SELECT bookings.*, user.fullname AS user_fullname
FROM bookings
LEFT JOIN user ON bookings.user_id = user.id
ORDER BY bookings.created_at DESC, bookings.id DESC
LIMIT $limit OFFSET $offset
";

$result = $conn->query($sql);

/* TOTAL */
$totalResult = $conn->query("SELECT COUNT(*) as total FROM bookings");
$totalRow = $totalResult->fetch_assoc();
$totalPages = ceil($totalRow['total'] / $limit);
?>

<style>
.container{max-width:1000px}
.card-box{
	
    background:white;
    padding:15px;
    border-radius:10px;
    box-shadow:0 3px 10px rgba(0,0,0,0.08);
}
.table th{
    background:#7c3aed;
    color:white;
}
</style>

<div class="container mt-4">

<div class="card-box mb-3">
<h4>BOOKING MANAGEMENT</h4>
</div>

<div class="card-box">

<table class="table table-bordered table-hover">
<tr>
    <th>Customer</th>
    <th>Booking Date</th>
    <th>Ticket Category</th>
    <th>Quantity</th>
    <th>Total (RM)</th>
    <th>Status</th>
    <th>Receipt</th>
    <th>Action</th>
</tr>

<?php while($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= htmlspecialchars($row['user_fullname']); ?></td>
    <td><?= htmlspecialchars($row['booking_date']); ?></td>
    <td><?= htmlspecialchars($row['ticket_category']); ?></td>
    <td><?= htmlspecialchars($row['quantity']); ?></td>
    <td><?= number_format($row['total_price'],2); ?></td>

    <!-- STATUS -->
    <td>
        <?php if($row['status']=="Pending"): ?>
            <span class="badge bg-warning">Pending</span>
        <?php elseif($row['status']=="Paid"): ?>
            <span class="badge bg-info">Paid</span>
        <?php else: ?>
            <span class="badge bg-success">Confirmed</span>
        <?php endif; ?>
    </td>

    <!-- RECEIPT -->
    <td>
        <?php if(!empty($row['receipt'])): ?>

            <a href="admin_view_receipt.php?file=<?= $row['receipt']; ?>" 
			class="btn btn-sm btn-primary">
			View
			</a>

        <?php else: ?>
            <small>No receipt</small>
        <?php endif; ?>
    </td>

    <!-- ACTION -->
    <td>
        <?php if($row['status']=="Paid"): ?>
            <a href="admin_confirm_booking.php?id=<?= $row['id']; ?>" 
               class="btn btn-sm btn-success"
               onclick="return confirm('Confirm booking?')">
                Confirm
            </a>
        <?php else: ?>
            <button class="btn btn-sm btn-secondary" disabled>Confirm</button>
        <?php endif; ?>
    </td>

</tr>
<?php endwhile; ?>

</table>

<!-- PAGINATION -->
<div class="mt-3">
<nav>
<ul class="pagination">
<?php for($i=1;$i<=$totalPages;$i++): ?>
    <li class="page-item <?= ($i==$page)?'active':''; ?>">
        <a class="page-link" href="?page=<?= $i; ?>"><?= $i; ?></a>
    </li>
<?php endfor; ?>
</ul>
</nav>
</div>

<a href="admin_dashboard.php" class="btn btn-secondary">Back</a>

</div>
</div>

<?php include 'footer.php'; ?>