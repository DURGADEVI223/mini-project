<?php
session_start();
include 'conn.php';
include 'header.php';

$errors = [];
$username = "";

// Process login
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validation
    if (empty($username)) {
        $errors[] = "Username is required.";
    }

    if (empty($password)) {
        $errors[] = "Password is required.";
    }

    // Check user in database
    if (empty($errors)) {

        $stmt = $conn->prepare(
            "SELECT id, fullname, username, password, phone, role, created_at 
             FROM user WHERE username = ?"
        );

        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {

            $user = $result->fetch_assoc();

            // Verify password
            if (password_verify($password, $user['password'])) {

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['fullname'] = $user['fullname'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['phone'] = $user['phone'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['created_at'] = $user['created_at'];

                // Cookie
                setcookie("username", $user['username'], time() + 3600, "/");

                header("Location: booking.php");
                exit();

            } else {
                $errors[] = "Invalid  password.";
            }

        } else {
            $errors[] = "Invalid username.";
        }

        $stmt->close();
    }
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
                    <h3 class="text-center mb-4 login-title">Login</h3>
					
                    <!-- Success Message -->
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success">
                            <?php
                                echo htmlspecialchars($_SESSION['success']);
                                unset($_SESSION['success']);
                            ?>
                        </div>
                    <?php endif; ?>

                    <!-- Error Messages -->
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo htmlspecialchars($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
					
                    <!-- Login Form -->
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input 
                                type="text"
                                name="username"
                                class="form-control"
                                value="<?php echo htmlspecialchars($username); ?>" >
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input 
                                type="password"
                                name="password"
                                class="form-control">
                        </div>
                        <button type="submit" class="btn btn-purple w-100">
                            Login
                        </button>
                    </form>
                    <p class="text-center mt-3 mb-0">
                        Don't have an account?
                        <a href="register.php" style="color:#a855f7;">
                            Register here
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>