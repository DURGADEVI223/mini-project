<?php
session_start();
include "conn.php";
include "header.php";

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* GET DATA */
$stmt = mysqli_prepare($conn, "
    SELECT * 
    FROM bookings 
    WHERE user_id=? 
    ORDER BY created_at DESC
");
mysqli_stmt_bind_param($stmt,"i",$user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<style>
.form-box {
    background: rgba(255,255,255,0.92);
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.12);
    margin-bottom: 15px;
}

.booking-title {
    font-size: 28px;
    letter-spacing: 1px;
}
</style>

<div class="form-box text-center fw-bold mb-3 booking-title">
    TICKET BOOKING
</div>

<table class="table table-bordered">
<tr>
    <th>Booking Date</th>
    <th>Category</th>
    <th>Quantity</th>
    <th>Total Price</th>
    <th>Status</th>
    <th>Action</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)){ ?>

<tr>
    <td><?= htmlspecialchars($row['booking_date']); ?></td>
    <td><?= htmlspecialchars($row['ticket_category']); ?></td>
    <td><?= htmlspecialchars($row['quantity']); ?></td>
    <td>RM <?= htmlspecialchars($row['total_price']); ?></td>

    <!-- STATUS -->
    <td>
        <?php if($row['status'] == "Pending"): ?>
            <span style="color:orange;font-weight:bold;">Pending</span>

        <?php elseif($row['status'] == "Paid"): ?>
            <span style="color:blue;font-weight:bold;">Paid</span>

        <?php else: ?>
            <span style="color:green;font-weight:bold;">Confirmed</span>
        <?php endif; ?>
    </td>

    <!-- ACTION -->
    <td>

        <!-- EDIT -->
        <a href="edit_booking.php?id=<?= $row['id']; ?>">Edit</a> |

        <!-- DELETE -->
        <a href="delete_booking.php?id=<?= $row['id']; ?>"
           onclick="return confirm('Delete this booking?')">
           Delete
        </a> |

        <!-- PRINT -->
        <a href="print_ticket.php?id=<?= $row['id']; ?>">
            Print
        </a> |

        <!-- UPLOAD RECEIPT (ONLY IF PENDING) -->
        <?php if($row['status'] == "Pending"): ?>
            <a href="upload_receipt.php?id=<?= $row['id']; ?>">
                Upload Receipt
            </a>
        <?php else: ?>
            <small style="color:gray;">Receipt Uploaded</small>
        <?php endif; ?>

    </td>

</tr>

<?php } ?>

</table>

<?php include "footer.php"; ?>