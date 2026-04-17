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

    /* 🔥 DELETE BOOKINGS FIRST (To avoid foreign key error) */
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
.container { max-width: 1100px; }

/* UI Title Box - Gradient Ungu */
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

/* Card Box */
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
    font-size: 20px;
    border-bottom: 1px solid #f1f1f1;
}

/* Pagination Customization */
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

.btn-back {
    background: #f3f4f6;
    color: #4b5563;
    border: none;
    border-radius: 8px;
    padding: 8px 20px;
    text-decoration: none;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    transition: 0.3s;
}

.btn-back:hover {
    background: #e5e7eb;
    color: #1f2937;
}
</style>

<div class="container mt-4">

    <div class="page-title-box">
        <div>USER MANAGEMENT</div>
        <div style="font-size: 14px; font-weight: normal;">Admin / Users</div>
    </div>

    <div class="card-box">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Full Name</th>
                        <th>Username</th>
                        <th>Phone</th>
                        <th>Registration Date</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($row['fullname']); ?></strong></td>
                        <td><span class="badge bg-light text-dark p-2"><?= htmlspecialchars($row['username']); ?></span></td>
                        <td><?= htmlspecialchars($row['phone']); ?></td>
                        <td><span class="text-muted"><?= date("d M Y, h:i A", strtotime($row['created_at'])); ?></span></td>
                        <td class="text-center">
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="user_id" value="<?= $row['id']; ?>">
                                <button type="submit" name="delete"
                                    class="btn btn-outline-danger btn-sm"
                                    onclick="return confirm('Delete this user and all their bookings?')">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4">
            <a href="admin_dashboard.php" class="btn-back">
                ← Back to Dashboard
            </a>
            
            <nav>
                <ul class="pagination mb-0">
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
    </div>

</div>

<?php include 'footer.php'; ?>