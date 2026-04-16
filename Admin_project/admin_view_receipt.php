<?php
session_start();
include 'conn.php';

if (!isset($_SESSION['admin_id'])) {
    die("Access denied");
}

if (!isset($_GET['file'])) {
    die("No file");
}

$file = basename($_GET['file']);
$path = "../uploads/" . $file;

if (!file_exists($path)) {
    die("File not found");
}

$ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
?>

<!DOCTYPE html>
<html>
<head>
<title>Receipt Viewer</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

/* BACKGROUND */
body{
    margin:0;
    font-family:Arial;
    background: url("images/birdpark_bg.jpg") center/cover no-repeat;
}

/* blur overlay */
body::before{
    content:"";
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.45);
    backdrop-filter: blur(6px);
    z-index:-1;
}

/* POPUP BOX */
.popup{
    width:850px;
    max-width:95%;
    margin:60px auto;
    background:white;
    border-radius:15px;
    box-shadow:0 15px 35px rgba(0,0,0,0.4);
    position:relative;
    overflow:hidden;
}

/* TOP BAR CLOSE */
.top-bar{
    display:flex;
    justify-content:flex-end;
    padding:10px;
    background:#f5f5f5;
}

/* CLOSE BUTTON TOP RIGHT */
.close-btn{
    background:#dc3545;
    color:white;
    border:none;
    padding:6px 12px;
    border-radius:8px;
    text-decoration:none;
    font-size:13px;
}

/* CONTENT */
.content{
    padding:20px;
    text-align:center;
}

/* IMAGE */
img{
    max-width:100%;
    border-radius:10px;
}

/* PDF */
iframe{
    width:100%;
    height:500px;
    border:none;
    border-radius:10px;
}

/* TITLE */
.title{
    font-weight:bold;
    margin-bottom:15px;
    font-size:18px;
}
</style>

</head>

<body>

<div class="popup">

    <!-- CLOSE TOP RIGHT -->
    <div class="top-bar">
        <a href="admin_bookings.php" class="close-btn"> Close</a>
    </div>

    <div class="content">

        <div class="title">Receipt Preview</div>

        <?php if($ext=="jpg" || $ext=="jpeg" || $ext=="png"): ?>

            <img src="<?= $path; ?>">

        <?php elseif($ext=="pdf"): ?>

            <iframe src="<?= $path; ?>"></iframe>

        <?php else: ?>

            <p>File not supported</p>

        <?php endif; ?>

    </div>

</div>

</body>
</html>