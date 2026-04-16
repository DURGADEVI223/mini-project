
<?php
session_start();
include "conn.php";
include "header.php";

/* SESSION VALIDATION */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

/* CHECK BOOKING SESSION */
if (!isset($_SESSION['booking_date'])) {
    header("Location: booking.php");
    exit();
}

/* USER DATA */
$user_id  = $_SESSION['user_id'];
$fullname = $_SESSION['fullname'];
$phone    = $_SESSION['phone'];

/* BOOKING DATA */
$date   = $_SESSION['booking_date'];
$adult  = $_SESSION['adult_qty'];
$child  = $_SESSION['child_qty'];
$senior = $_SESSION['senior_qty'];
$total  = $_SESSION['total_price'];

/* PROCESS CONFIRM */
if (isset($_POST['confirm'])) {

    $category_str = "";
    if ($adult > 0)  $category_str .= "Adult x$adult, ";
    if ($child > 0)  $category_str .= "Child x$child, ";
    if ($senior > 0) $category_str .= "Senior x$senior, ";
    $category_str = rtrim($category_str, ", ");

    $qty = $adult + $child + $senior;

    $stmt = $conn->prepare("
        INSERT INTO bookings
        (user_id, fullname, phone, booking_date, ticket_category, quantity, total_price)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param("issssid",
        $user_id, $fullname, $phone, $date, $category_str, $qty, $total
    );

    if ($stmt->execute()) {

        /* CLEAR SESSION */
        unset($_SESSION['booking_date']);
        unset($_SESSION['adult_qty']);
        unset($_SESSION['child_qty']);
        unset($_SESSION['senior_qty']);
        unset($_SESSION['total_price']);

        header("Location: booking_history.php");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Booking Failed</div>";
    }
}
?>

<h3>Confirm Booking</h3>

<div class="card p-3">
    <p><b>Name:</b> <?php echo htmlspecialchars($fullname); ?></p>
    <p><b>Date:</b> <?php echo htmlspecialchars($date); ?></p>

    <p><b>Tickets:</b></p>
    <ul>
        <?php if($adult>0) echo "<li>Adult x $adult</li>"; ?>
        <?php if($child>0) echo "<li>Child x $child</li>"; ?>
        <?php if($senior>0) echo "<li>Senior x $senior</li>"; ?>
    </ul>

    <h4>Total: RM <?php echo number_format($total,2); ?></h4>
</div>

<br>

<a href="booking.php" class="btn btn-warning">Edit Booking</a>

<!-- FORM -->
<form method="POST" style="display:inline;">

    <!-- BUTTON trigger modal -->
    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#confirmModal">
        Confirm Booking
    </button>

    <!-- MODAL -->
    <div class="modal fade" id="confirmModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5>Confirm Booking</h5>
                </div>

                <div class="modal-body">
                    Are you sure you want to confirm this booking?
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" name="confirm" class="btn btn-success">
                        Yes, Confirm
                    </button>
                </div>

            </div>
        </div>
    </div>

</form>

<?php include "footer.php"; ?>