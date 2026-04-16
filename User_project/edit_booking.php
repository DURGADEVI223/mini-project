<?php
include "conn.php";
include "header.php";

// SESSION VALIDATION
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// GET BOOKING ID
if (!isset($_GET['id'])) {
    echo "<script>alert('Booking ID not found!'); window.location='booking_history.php';</script>";
    exit();
}
 
$booking_id = intval($_GET['id']);

// Get existing booking data
$stmt = $conn->prepare("SELECT * FROM bookings WHERE id=? AND user_id=?");
$stmt->bind_param("ii", $booking_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows != 1) {
    echo "<script>alert('Booking not found!'); window.location='booking_history.php';</script>";
    exit();
}

$booking = $result->fetch_assoc();

// Halang edit tarikh hari ini
$today = date("Y-m-d");

if ($booking['booking_date'] == $today) {
    echo "<script>
            alert('You cannot edit a booking made for today.');
            window.location='booking_history.php';
          </script>";
    exit();
}

// Handle update
if(isset($_POST['update'])){
    $date = htmlspecialchars($_POST['booking_date']);
    $adult = intval($_POST['adult_qty']);
    $child = intval($_POST['child_qty']);
    $senior = intval($_POST['senior_qty']);

    // Validation choose 
    if(empty($date) || ($adult==0 && $child==0 && $senior==0)){
        echo "<script>alert('Please enter date and at least one ticket.');</script>";
    } elseif (strtotime($date) < strtotime($today)){
        echo "<script>alert('Cannot select past date.');</script>";
    } else {
        // Calculate total price
        $total = ($adult*50) + ($child*30) + ($senior*20);
        $qty_total = $adult + $child + $senior;

        // Prepare ticket category string
        $category_str = "";
        if($adult>0) $category_str .= "Adult x$adult, ";
        if($child>0) $category_str .= "Child x$child, ";
        if($senior>0) $category_str .= "Senior x$senior, ";
        $category_str = rtrim($category_str, ", ");

        // Update database
        $stmt_update = $conn->prepare("UPDATE bookings SET booking_date=?, ticket_category=?, quantity=?, total_price=? WHERE id=? AND user_id=?");
        $stmt_update->bind_param("ssiddi", $date, $category_str, $qty_total, $total, $booking_id, $user_id);

        if($stmt_update->execute()){
            echo "<script>
                alert('Booking updated successfully!');
                window.location='booking_history.php';
            </script>";
            exit();
        } else {
            echo "<script>alert('Update failed. Please try again.');</script>";
        }
    }
}

?>

<h3 class="mb-4">Edit Booking</h3>

<div class="content-card">
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Booking Date</label>
            <input type="date" name="booking_date" class="form-control" value="<?= htmlspecialchars($booking['booking_date']); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Adult Tickets (RM50 each)</label>
            <input type="number" name="adult_qty" min="0" class="form-control" value="<?= preg_match('/Adult x(\d+)/', $booking['ticket_category'], $m) ? $m[1] : 0; ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Child Tickets (RM30 each)</label>
            <input type="number" name="child_qty" min="0" class="form-control" value="<?= preg_match('/Child x(\d+)/', $booking['ticket_category'], $m) ? $m[1] : 0; ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Senior Tickets (RM20 each)</label>
            <input type="number" name="senior_qty" min="0" class="form-control" value="<?= preg_match('/Senior x(\d+)/', $booking['ticket_category'], $m) ? $m[1] : 0; ?>">
        </div>

        <button type="submit" name="update" class="btn btn-main w-100">Update Booking</button>
        <a href="booking_history.php" class="btn btn-secondary w-100 mt-2">Cancel</a>
    </form>
</div>

<?php include "footer.php"; ?> 