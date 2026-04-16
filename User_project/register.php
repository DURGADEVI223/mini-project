<?php
session_start();
include 'conn.php';
include 'header.php';

$errors = [];
$fullname = "";
$username = "";
$phone = "";

	/* Process Register */
	if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fullname = trim($_POST['fullname']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $phone = trim($_POST['phone']);

    /* Validation */
    if (empty($fullname)) {
        $errors[] = "Full name is required.";
    }

    if (empty($username)) {
        $errors[] = "Username is required.";
    }

    if (empty($password)) {
        $errors[] = "Password is required.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }

    if (!empty($phone) && !preg_match('/^[0-9+\-\s]+$/', $phone)) {
        $errors[] = "Invalid phone number.";
    }

    /* Check duplicate username */
    if (empty($errors)) {

        $stmt = $conn->prepare("SELECT id FROM user WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors[] = "Username already exists.";
        }

        $stmt->close();
    }

    /* Insert user */
    if (empty($errors)) {

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $role = "customer";

        $stmt = $conn->prepare("
            INSERT INTO user
            (fullname, username, password, phone, role, created_at )
            VALUES (?, ?, ?, ?, ?, NOW())
        ");

        $stmt->bind_param("sssss", $fullname, $username, $hashedPassword, $phone, $role);

        if ($stmt->execute()) {

            $_SESSION['success'] = "Registration successful. Please login.";
            header("Location: login.php");
            exit();

        } else {

            $errors[] = "Registration failed. Please try again.";
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
			<ul class="mb-0">

	<?php foreach ($errors as $error): ?>

		<li><?php echo htmlspecialchars($error); ?></li>

	<?php endforeach; ?>

			</ul>

		</div>

<?php endif; ?>

	<form method="POST">

		<div class="mb-3">
			<label class="form-label">Full Name</label>
		<input 
			type="text"
			name="fullname"
			class="form-control"
			value="<?php echo htmlspecialchars($fullname); ?>">
		</div>

		<div class="mb-3">
			<label class="form-label">Username</label>
		<input 
			type="text"
			name="username"
			class="form-control"
			value="<?php echo htmlspecialchars($username); ?>">
		</div>

		<div class="mb-3">
			<label class="form-label">Password</label>
		<input 
			type="password"
			name="password"
			class="form-control">
		</div>

		<div class="mb-3">
			<label class="form-label">Phone Number</label>
		<input 
			type="text"
			name="phone"
			class="form-control"
			value="<?php echo htmlspecialchars($phone); ?>">
		</div>

		<button type="submit" class="btn btn-purple w-100">
			Register
		</button>

	</form>

		<p class="text-center mt-3 mb-0">
			Already have an account?
			<a href="login.php" style="color:#a855f7;">Login here</a>
		</p>
		
				</div>
			</div>
		</div>
	</div>
</div>

<?php include 'footer.php'; ?>


