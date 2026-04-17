<?php
session_start();
include 'conn.php';
include 'header.php';

if(!isset($_SESSION['admin_id'])){
    header("Location: admin_login.php");
    exit();
}

/* STATUS FUNCTION */
function getStatusBadge($status){
    if($status == "Pending"){
        return "<span class='badge bg-warning text-dark'>Pending</span>";
    } elseif($status == "Paid"){
        return "<span class='badge bg-info'>Paid</span>";
    } else {
        return "<span class='badge bg-success'>Confirmed</span>";
    }
}

/* PAGINATION */
$limit = 8;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

/* PREPARED STATEMENT */
$sql = "
SELECT bookings.*, user.fullname AS user_fullname
FROM bookings
LEFT JOIN user ON bookings.user_id = user.id
ORDER BY bookings.created_at DESC
LIMIT ? OFFSET ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();

/* TOTAL */
$totalResult = $conn->query("SELECT COUNT(*) as total FROM bookings");
$totalRow = $totalResult->fetch_assoc();
$totalPages = ceil($totalRow['total'] / $limit);
?>

<style>
.container { max-width: 1100px; }

/* UI Title Box - Tema Ungu Gradient */
.page-title-box {
    background: linear-gradient(90deg, #6f42c1, #8b5cf6);
    color: white;
    padding: 22px 25px;
    border-radius: 12px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 25px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

/* Card Box - Background putih macam User Management */
.card-box {
    background: white;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
}

/* Table Styling */
.table thead th {
    background-color: #f8f9fa;
    color: #6f42c1;
    border-bottom: 2px solid #eee;
    text-transform: uppercase;
    font-size: 13px;
    letter-spacing: 0.5px;
    padding: 15px;
}

.table tbody td {
    padding: 15px;
    vertical-align: middle;
    font-size: 17px;
    border-bottom: 1px solid #f1f1f1;
}

/* Pagination Style */
.pagination .page-link {
    color: #6f42c1;
    border-radius: 5px;
    margin: 0 3px;
}

.pagination .active .page-link {
    background-color: #6f42c1;
    border-color: #6f42c1;
    color: white;
}

.btn-view {
    background-color: #e0e7ff;
    color: #4338ca;
    border: none;
    font-weight: 500;
}
.btn-view:hover {
    background-color: #4338ca;
    color: white;
}
</style>

<div class="container mt-4">

    <div class="page-title-box">
        <div>BOOKING MANAGEMENT</div>
        <div style="font-size: 14px; font-weight: normal;">Admin / Bookings</div>
    </div>

    <div class="card-box">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Category</th>
                        <th>Qty</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Receipt</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($row['user_fullname']); ?></strong></td>
                        <td><?= htmlspecialchars($row['booking_date']); ?></td>
                        <td><span class="text-muted"><?= htmlspecialchars($row['ticket_category']); ?></span></td>
                        <td><?= htmlspecialchars($row['quantity']); ?></td>
                        <td>RM <?= number_format($row['total_price'],2); ?></td>
                        <td><?= getStatusBadge($row['status']); ?></td>
                        <td>
                            <?php if(!empty($row['receipt'])): ?>
                                <a href="admin_view_receipt.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-view">View</a>
                            <?php else: ?>
                                <small class="text-muted">No receipt</small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($row['status']=="Paid"): ?>
                                <a href="admin_confirm_booking.php?id=<?= $row['id']; ?>" 
                                   class="btn btn-success btn-sm"
                                   onclick="return confirm('Confirm booking?')">
                                   Confirm
                                </a>
                            <?php else: ?>
                                <button class="btn btn-light btn-sm text-muted" disabled>Confirm</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <nav class="mt-4">
            <ul class="pagination justify-content-center">
                <?php for($i=1;$i<=$totalPages;$i++): ?>
                    <li class="page-item <?= ($page == $i) ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?= $i; ?>"><?= $i; ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>

</div>

<?php include 'footer.php'; ?>