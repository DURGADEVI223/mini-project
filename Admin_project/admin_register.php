<?php
include 'conn.php';
include 'header.php';


$msg = "";
$fullname = "";
$username = "";
$phone = "";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = trim($_POST['admin_fullname']);
    $username = trim($_POST['admin_username']);
    $password = trim($_POST['admin_password']);
    $phone = trim($_POST['admin_phone']);

    // VALIDATION
    if (empty($fullname)) $errors[] = "Fullname required";
    if (empty($username)) $errors[] = "Username required";
    if (empty($password)) $errors[] = "Password required";

    // CHECK DUPLICATE
    if (empty($errors)) {
        $check = $conn->prepare("SELECT id FROM user WHERE username=?");
        $check->bind_param("s", $username);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $errors[] = "Username already exists";
        }
        $check->close();
    }

    // INSERT
    if (empty($errors)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $role = "admin";

        $stmt = $conn->prepare("
INSERT INTO user (fullname, username, password, phone, role)
VALUES (?, ?, ?, ?, ?)
");

$stmt->bind_param("sssss", $fullname, $username, $hashed, $phone, $role);
        if ($stmt->execute()) {
            $msg = "Admin registered!";
            // clear form
            $fullname = $username = $phone = "";
        } else {
            $errors[] = "Error occurred";
        }

        $stmt->close();
    }
}
?>


<style>
/* Purple Theme */

.register-card{
    border-top:6px solid #c084fc;
}

.btn-purple{
    background:#c084fc;
    color:white;
}

.btn-purple:hover{
    background:#a855f7;
    color:white;
}

.register-title{
    color:#a855f7;
    font-weight:bold;
}
</style>


<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow register-card">
                <div class="card-body p-4">
                    <h3 class="text-center mb-4 register-title">Create Account</h3>


	<!-- Error Messages -->
	<?php if (!empty($errors)): ?>
<div class="alert alert-danger">
<ul>
<?php foreach ($errors as $error): ?>
<li><?php echo htmlspecialchars($error); ?></li>
<?php endforeach; ?>
</ul>
</div>
<?php endif; ?>

<?php if ($msg): ?>
<div class="alert alert-success"><?php echo $msg; ?></div>
<?php endif; ?>

<form method="POST" autocomplete="off">

    <label class="form-label">Full Name</label>
    <input type="text" name="admin_fullname" class="form-control mb-2"
        placeholder="Enter full name"
        value="<?php echo htmlspecialchars($fullname); ?>"
        autocomplete="off">

    <label class="form-label">Username</label>
    <input type="text" name="admin_username" class="form-control mb-2"
        placeholder="Enter username"
        value="<?php echo htmlspecialchars($username); ?>"
        autocomplete="off">

    <label class="form-label">Password</label>
    <input type="password" name="admin_password" class="form-control mb-2"
        placeholder="Enter password"
        autocomplete="new-password">

    <label class="form-label">Phone</label>
    <input type="text" name="admin_phone" class="form-control mb-3"
        placeholder="Enter phone number"
        value="<?php echo htmlspecialchars($phone); ?>"
        autocomplete="off">

    <button type="submit" class="btn btn-purple w-100">
        Register
    </button>

</form>

<p class="text-center mt-3 mb-0">
    Already have an account?
    <a href="admin_login.php" class="text-decoration-none" style="color:#a855f7;">
       Login here
    </a>
</p>

</div>
</div>
</div>
</div>
</div>

<?php include 'footer.php'; ?>