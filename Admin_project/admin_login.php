<?php
session_start();
include 'conn.php';
include 'header.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = htmlspecialchars(trim($_POST['admin_username']));
    $password = trim($_POST['admin_password']);

    $stmt = $conn->prepare("SELECT id, fullname, password, role FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {

        if (password_verify($password, $row['password'])) {

            if ($row['role'] == 'admin') {

                session_regenerate_id(true);

                $_SESSION['admin_id'] = $row['id'];
                $_SESSION['admin_name'] = $row['fullname'];
                $_SESSION['role'] = $row['role'];

                header("Location: admin_dashboard.php");
                exit();

            } else {
                $error = "Access denied. Not admin account.";
            }

        } else {
            $error = "Invalid username or password.";
        }

    } else {
        $error = "Invalid username or password.";
    }

    $stmt->close();
}
?>


<style>
/* Purple Theme */
.login-card{
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

.login-title{
    color:#a855f7;
    font-weight:bold;
}
</style>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">

            <div class="card shadow login-card">
                <div class="card-body p-4">

                    <h3 class="text-center mb-4 login-title">Admin Login</h3>

                    <!-- ERROR MESSAGE -->
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger text-center">
                            <?= htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>

                    <!-- FORM -->
                    <form method="POST" autocomplete="off">

                        <label class="form-label">Username</label>
                        <input type="text"
                               name="admin_username"
                               class="form-control mb-2"
                               placeholder="Enter username"
                               required
                               autocomplete="off">

                        <label class="form-label">Password</label>
                        <input type="password"
                               name="admin_password"
                               class="form-control mb-3"
                               placeholder="Enter password"
                               required
                               autocomplete="new-password">

                        <button type="submit" class="btn btn-purple w-100">
                            Login
                        </button>

                    </form>

                    <!-- FOOTER -->
                    <p class="text-center mt-3 mb-0">
                        Don't have an account?
                        <a href="admin_register.php" style="color:#a855f7;">
                            Register here
                        </a>
                    </p>

                </div>
            </div>

        </div>
    </div>
</div>

</body>
<?php include 'footer.php'; ?>