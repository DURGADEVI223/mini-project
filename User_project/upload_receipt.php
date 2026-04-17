<?php
session_start();
include "conn.php";
include "header.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid booking ID.");
}

$booking_id = intval($_GET['id']);

// Check booking belongs to logged in user
$checkStmt = $conn->prepare("SELECT id, user_id, receipt, status FROM bookings WHERE id = ? AND user_id = ?");
$checkStmt->bind_param("ii", $booking_id, $user_id);
$checkStmt->execute();
$bookingResult = $checkStmt->get_result();

if ($bookingResult->num_rows === 0) {
    die("Access denied. This booking does not belong to you.");
}

$booking = $bookingResult->fetch_assoc();

if (isset($_POST['upload'])) {

    if (!isset($_FILES['receipt']) || $_FILES['receipt']['error'] !== 0) {
        die("File upload error.");
    }

    $fileName = $_FILES['receipt']['name'];
    $tmpName  = $_FILES['receipt']['tmp_name'];
    $fileSize = $_FILES['receipt']['size'];

    $allowedExt = ['jpg', 'jpeg', 'png', 'pdf'];
    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (!in_array($ext, $allowedExt)) {
        die("Invalid file type. Only JPG, JPEG, PNG and PDF are allowed.");
    }

    // Max 2MB
    if ($fileSize > 2 * 1024 * 1024) {
        die("File too large. Maximum allowed size is 2MB.");
    }

    // Optional MIME type validation
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $tmpName);
    finfo_close($finfo);

    $allowedMime = [
        'image/jpeg',
        'image/png',
        'application/pdf'
    ];

    if (!in_array($mimeType, $allowedMime)) {
        die("Invalid file content.");
    }

    // Create user folder
    $baseUploadDir = "../uploads/";
    $userFolder = $baseUploadDir . "user_" . $user_id . "/";

    if (!is_dir($baseUploadDir)) {
        mkdir($baseUploadDir, 0777, true);
    }

    if (!is_dir($userFolder)) {
        mkdir($userFolder, 0777, true);
    }

    // Generate safe filename
    $newFileName = "receipt_booking_" . $booking_id . "_" . time() . "." . $ext;
    $fullPath = $userFolder . $newFileName;

    if (move_uploaded_file($tmpName, $fullPath)) {

        // Save relative path in DB
        $dbReceiptPath = "user_" . $user_id . "/" . $newFileName;

        $updateStmt = $conn->prepare("UPDATE bookings SET receipt = ?, status = 'Paid' WHERE id = ? AND user_id = ?");
        $updateStmt->bind_param("sii", $dbReceiptPath, $booking_id, $user_id);

        if ($updateStmt->execute()) {
            echo "<script>
                alert('Receipt uploaded successfully!');
                window.location='upload_receipt.php?id=$booking_id';
            </script>";
            exit();
        } else {
            echo "<div class='alert alert-danger'>Failed to update booking record.</div>";
        }

    } else {
        echo "<div class='alert alert-danger'>Failed to upload file.</div>";
    }
}
?>

<style>
.upload-card{
    max-width:550px;
    margin:40px auto;
    border-radius:15px;
    box-shadow:0 6px 15px rgba(0,0,0,0.1);
    background:white;
    padding:30px;
    text-align:center;
}
.upload-icon{
    font-size:50px;
    color:#7c3aed;
}
.file-input{
    border:2px dashed #ccc;
    padding:25px;
    border-radius:10px;
    cursor:pointer;
    transition:0.3s;
    display:block;
}
.file-input:hover{
    border-color:#7c3aed;
    background:#f9f5ff;
}
.btn-upload{
    background:#7c3aed;
    color:white;
    border:none;
    padding:10px 25px;
    border-radius:8px;
    transition:0.3s;
}
.btn-upload:hover{
    background:#5b21b6;
}
.receipt-box{
    margin-top:15px;
    padding:15px;
    background:#f8f9fa;
    border-radius:10px;
    text-align:left;
}
.view-btn{
    display:inline-block;
    margin-top:10px;
    background:#16a34a;
    color:white;
    text-decoration:none;
    padding:8px 16px;
    border-radius:8px;
}
.view-btn:hover{
    background:#15803d;
}
</style>

<div class="upload-card">
    <div class="upload-icon">📤</div>
    <h4 class="mb-3">Upload Payment Receipt</h4>
    <p class="text-muted">Supported: JPG, PNG, PDF (Max 2MB)</p>

    <div class="receipt-box">
        <strong>Booking ID:</strong> <?php echo $booking_id; ?><br>
        <strong>Status:</strong> <?php echo htmlspecialchars($booking['status']); ?><br>

        <?php if (!empty($booking['receipt'])): ?>
           
            
        <?php else: ?>
            <br><span>No receipt uploaded yet.</span>
        <?php endif; ?>
    </div>

    <br>

    <form method="POST" enctype="multipart/form-data">
        <label class="file-input w-100">
            <input type="file" name="receipt" hidden required onchange="showFileName(this)">
            <span id="fileText">Click here to choose file</span>
        </label>

        <br><br>

        <button type="submit" name="upload" class="btn-upload">
            Upload Receipt
        </button>
    </form>
</div>

<script>
function showFileName(input){
    if(input.files.length > 0){
        document.getElementById("fileText").innerHTML = input.files[0].name;
    }
}
</script>

<?php include "footer.php"; ?>