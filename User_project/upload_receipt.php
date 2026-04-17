<?php
session_start();
include "conn.php";
include "header.php"; // Tambah ini supaya CSS Bootstrap masuk

if (isset($_POST['upload'])) {
    $booking_id = intval($_POST['booking_id']);
    $user_id = $_SESSION['user_id'];

    // --- TAMBAH SEMAKAN PEMILIKAN (SECURITY) ---
    $check_owner = $conn->prepare("SELECT id FROM bookings WHERE id = ? AND user_id = ?");
    $check_owner->bind_param("ii", $booking_id, $user_id);
    $check_owner->execute();
    if ($check_owner->get_result()->num_rows == 0) {
        die("<script>alert('Akses disekat!'); window.location='booking_history.php';</script>");
    }
    // ------------------------------------------

    $target_dir = "../uploads/receipts/" . $user_id . "/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $file = $_FILES['receipt'];
    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed_ext = ['jpg', 'jpeg', 'png', 'pdf'];

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    $allowed_mime = ['image/jpeg', 'image/png', 'application/pdf'];

    if (!in_array($file_ext, $allowed_ext) || !in_array($mime, $allowed_mime)) {
        die("Ralat: Hanya fail JPG, PNG dan PDF sahaja dibenarkan.");
    }

    if ($file['size'] > 2000000) {
        die("Ralat: Fail terlalu besar. Maksimum 2MB.");
    }

    $new_filename = "RESIT_" . bin2hex(random_bytes(8)) . "_" . time() . "." . $file_ext;
    $final_path = $target_dir . $new_filename;

    if (move_uploaded_file($file['tmp_name'], $final_path)) {
        $stmt = $conn->prepare("UPDATE bookings SET receipt=?, status='Paid' WHERE id=? AND user_id=?");
        $stmt->bind_param("sii", $new_filename, $booking_id, $user_id);
        $stmt->execute();
        echo "<script>alert('Resit berjaya dimuat naik!'); window.location='booking_history.php';</script>";
    } else {
        echo "Ralat teknikal semasa menyimpan fail.";
    }
}
?>

<style>
.upload-card{
    max-width:500px;
    margin:auto;
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
</style>

<div class="upload-card">

    <div class="upload-icon">📤</div>
    <h4 class="mb-3">Upload Payment Receipt</h4>
    

<form method="POST" enctype="multipart/form-data">
    <input type="hidden" name="booking_id" value="<?= isset($_GET['id']) ? intval($_GET['id']) : 0; ?>">
    
    <label class="file-input w-100">
        <input type="file" name="receipt" hidden required onchange="showFileName(this)">
        <span id="fileText">Click here to choose file</span>

        <br><br>

        <button type="submit" name="upload" class="btn-upload">
            Upload Receipt
        </button>

    </form>

</div>

<script>
function showFileName(input){
    const fileName = input.files[0].name;
    document.getElementById("fileText").innerHTML = fileName;
}
</script>

<?php include "footer.php"; ?>