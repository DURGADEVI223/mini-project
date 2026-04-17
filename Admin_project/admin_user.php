<?php
session_start();
include 'conn.php';
include 'header.php';

if(!isset($_SESSION['admin_id'])){
    header("Location: admin_login.php");
    exit();
}

/* 🔥 DELETE USER (POST SECURE) */
if(isset($_POST['delete'])){
    $id = intval($_POST['user_id']);

    /* 🔥 DELETE BOOKINGS FIRST */
    $stmt = $conn->prepare("DELETE FROM bookings WHERE user_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    /* 🔥 DELETE USER */
    $stmt = $conn->prepare("DELETE FROM user WHERE id=? AND role='customer'");
    $stmt->bind_param("i", $id);

    if($stmt->execute()){
        echo "<script>alert('User & bookings deleted'); window.location='admin_user.php';</script>";
    } else {
        echo "<script>alert('Delete failed');</script>";
    }

    $stmt->close();
}

/* PAGINATION */
$limit = 8;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

/* GET DATA */
$sql = "SELECT id, fullname, username, phone, created_at
        FROM user 
        WHERE role='customer'
        ORDER BY created_at DESC, id DESC
        LIMIT $limit OFFSET $offset";

$result = $conn->query($sql);

if (!$result) {
    die("SQL ERROR: " . $conn->error);
}

/* COUNT TOTAL */
$totalResult = $conn->query("SELECT COUNT(*) as total FROM user WHERE role='customer'");
$totalRow = $totalResult->fetch_assoc();
$totalUsers = $totalRow['total'];
$totalPages = ceil($totalUsers / $limit);
?>

<style>
.container{max-width:1000px}

.card-box{
background:white;
padding:15px;
border-radius:10px;
box-shadow:0 3px 10px rgba(0,0,0,0.08)
}

.table th{
background:#7c3aed;
color:white;
}
</style>
<div class="container mt-4">

<div class="card-box mb-3">
<h4>USER MANAGEMENT</h4>
</div>

<div class="card-box">

<table class="table table-bordered table-hover">
<tr>
    <th>Full Name</th>
    <th>Username</th>
    <th>Phone</th>
    <th>Registration Date</th>
    <th>Action</th>
</tr>

<?php while($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= htmlspecialchars($row['fullname']); ?></td>
    <td><?= htmlspecialchars($row['username']); ?></td>
    <td><?= htmlspecialchars($row['phone']); ?></td>
    <td><?= date("d/m/Y h:i A", strtotime($row['created_at'])); ?></td>

    <td>
        <!-- 🔥 DELETE BUTTON (POST) -->
        <form method="POST" style="display:inline;">
            <input type="hidden" name="user_id" value="<?= $row['id']; ?>">
            <button type="submit" name="delete"
                class="btn btn-danger btn-sm"
                onclick="return confirm('Delete this user?')">
                Delete
            </button>
        </form>
    </td>

</tr>
<?php endwhile; ?>

</table>

</div>

<!-- PAGINATION -->
<div class="mt-3">
<nav>
<ul class="pagination">

<?php for($i=1; $i<=$totalPages; $i++): ?>
    <li class="page-item <?= ($i==$page)?'active':''; ?>">
        <a class="page-link" href="?page=<?= $i; ?>">
            <?= $i; ?>
        </a>
    </li>
<?php endfor; ?>

</ul>
</nav>
</div>

<a href="admin_dashboard.php" class="btn btn-secondary">Back</a>

</div>

<?php include 'footer.php'; ?>