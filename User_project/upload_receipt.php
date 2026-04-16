<?php
session_start();
include "conn.php";
include "header.php";

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];

if(isset($_POST['upload'])){

    if($_FILES['receipt']['error'] != 0){
        die("File upload error!");
    }

    $file = $_FILES['receipt']['name'];
    $tmp  = $_FILES['receipt']['tmp_name'];

    $newName = time().'_'.$file;
    $newPath = "../uploads/" . $newName;

    $allowed = ['jpg','jpeg','png','pdf'];
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

    if(!in_array($ext, $allowed)){
        die("Invalid file type!");
    }

    if(move_uploaded_file($tmp, $newPath)){

        $stmt = $conn->prepare("
            UPDATE bookings 
            SET receipt=?, status='Paid' 
            WHERE id=?
        ");
        $stmt->bind_param("si", $newName, $id);
        $stmt->execute();

        echo "<script>
            alert('Upload success!');
            window.location='booking_history.php';
        </script>";

    } else {
        echo "<div class='alert alert-danger'>Upload failed!</div>";
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
    <p class="text-muted">Supported: JPG, PNG, PDF</p>

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
    const fileName = input.files[0].name;
    document.getElementById("fileText").innerHTML = fileName;
}
</script>

<?php include "footer.php"; ?>