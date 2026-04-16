<?php
include "conn.php";
include "header.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// User info
$fullname = $_SESSION['fullname'];
$phone = $_SESSION['phone'];
$role = $_SESSION['role'];
$regdate = $_SESSION['created_at'];

$prices = [
    "adult" => 50,
    "child" => 30,
    "senior" => 20
];

if (isset($_POST['proceed'])) {

    $date = $_POST['booking_date'];
    $adult  = intval($_POST['adult_qty']);
    $child  = intval($_POST['child_qty']);
    $senior = intval($_POST['senior_qty']);

    if (empty($date)) {
        echo "<div class='alert alert-danger'>All fields required</div>";
    }
    elseif (strtotime($date) < strtotime(date("Y-m-d"))) {
        echo "<div class='alert alert-danger'>Invalid date</div>";
    }
    elseif ($adult == 0 && $child == 0 && $senior == 0) {
        echo "<div class='alert alert-danger'>Select at least one ticket</div>";
    }
    else {

        $total =
            ($adult * 50) +
            ($child * 30) +
            ($senior * 20);

        $_SESSION['booking_date'] = $date;
        $_SESSION['adult_qty'] = $adult;
        $_SESSION['child_qty'] = $child;
        $_SESSION['senior_qty'] = $senior;
        $_SESSION['total_price'] = $total;

        header("Location: confirm_booking.php");
        exit();
    }
}
?>

<style>
.form-box {
    background: rgba(255,255,255,0.92);
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.12);
    margin-bottom: 15px;
}

.form-control {
    border-radius: 8px;
}

.table td, .table th {
    vertical-align: middle;
}

.booking-title {
    font-size: 28px;
    letter-spacing: 1px;
}
</style>

<div class="form-box text-center fw-bold mb-3 booking-title">
    Ticket Booking
</div>

<!-- USER INFO -->
<div class="form-box">
    <h5>User Information</h5>
    <p><strong>Fullname:</strong> <?= htmlspecialchars($fullname) ?></p>
    <p><strong>Phone Number:</strong> <?= htmlspecialchars($phone) ?></p>
    <p><strong>Role:</strong> <?= htmlspecialchars($role) ?></p>
    <p><strong>Registration Date:</strong> <?= htmlspecialchars($regdate) ?></p>
</div>

<form method="POST">

<!-- DATE BOX -->
<div class="form-box">
    <label class="fw-bold mb-2">Booking Date</label>
    <input type="date" name="booking_date" class="form-control" required>
</div>

<!-- TABLE BOX -->
<div class="form-box">
<table class="table table-bordered text-center">
<tr>
    <th>Category</th>
    <th>Price</th>
    <th>Quantity</th>
    <th>Subtotal</th>
</tr>

<?php foreach($prices as $cat => $price): ?>
<tr>
    <td><?= ucfirst($cat) ?></td>
    <td>RM<?= $price ?></td>
    <td>
        <input type="number" name="<?= $cat ?>_qty"
               id="<?= $cat ?>" value="0" min="0"
               class="form-control text-center">
    </td>
    <td id="<?= $cat ?>_total">RM0</td>
</tr>
<?php endforeach; ?>

</table>
</div>

<!-- SUMMARY BOX -->
<div class="form-box">
    <h5>Order Summary</h5>
    <div id="summary"></div>
    <hr>
    <h4>Total: RM <span id="grand_total">0</span></h4>
</div>

<!-- BUTTON (UNCHANGED ORIGINAL STYLE) -->
<button name="proceed" class="btn btn-primary">
    Proceed
</button>

</form>

<script>
const prices = {adult:50, child:30, senior:20};
const categories = Object.keys(prices);

function updateTotal() {
    let total = 0;
    let summary = "";

    categories.forEach(cat => {
        let qty = parseInt(document.getElementById(cat).value) || 0;
        let subtotal = qty * prices[cat];

        document.getElementById(cat+"_total").innerHTML = "RM"+subtotal;

        if(qty > 0){
            summary += `${cat.charAt(0).toUpperCase()+cat.slice(1)} x ${qty} = RM${subtotal}<br>`;
        }

        total += subtotal;
    });

    document.getElementById("summary").innerHTML = summary;
    document.getElementById("grand_total").innerHTML = total;
}

categories.forEach(cat => {
    document.getElementById(cat).addEventListener("input", updateTotal);
});

updateTotal();
</script>

<?php include "footer.php"; ?>