<?php
include "../db.php";

$msg = "";

if (isset($_POST['register'])) {

    $name = $_POST['name'];
    $cnic = $_POST['cnic'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // check duplicate email
    $check = $conn->query("SELECT * FROM users WHERE email='$email'");

    if ($check->num_rows > 0) {

        $msg = "<div class='alert alert-danger'>
                    ❌ Email already registered. Try login.
                </div>";

    } else {

        $unique_id = uniqid();

        $sql = "INSERT INTO users (unique_id, name, cnic, email, password)
                VALUES ('$unique_id', '$name', '$cnic', '$email', '$password')";

        if ($conn->query($sql)) {

            $msg = "<div class='alert alert-success'>
                        ✅ Registration Successful! Now Login
                    </div>";

        } else {

            $msg = "<div class='alert alert-danger'>
                        ❌ Error in Registration!
                    </div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>

<body>

<div class="auth-wrapper">
    <div class="auth-card">
        <div class="text-center mb-4">
            <span style="font-size: 2.5rem;">📝</span>
            <h3 class="mt-2" style="font-weight: 700; color: var(--text-main);">Create Account</h3>
            <p class="text-muted">Register to participate in the election.</p>
        </div>

        <!-- MESSAGE -->
        <?php if($msg) { echo "<div class='mb-3' style='font-size: 0.9rem;'>$msg</div>"; } ?>

        <form method="POST">
            <div class="mb-3">
                <input type="text" name="name" class="form-control" placeholder="Full Name" required>
            </div>

            <div class="mb-3">
                <input type="text" name="cnic" class="form-control" placeholder="CNIC (e.g. 12345-6789012-3)" required>
            </div>

            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="Email Address" required>
            </div>

            <div class="mb-4">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>

            <button type="submit" name="register" class="btn btn-primary w-100 py-2">
                Register
            </button>
        </form>

        <div class="text-center mt-4">
            <p class="text-muted mb-1" style="font-size: 0.9rem;">
                Already have an account?
                <a href="login.php" class="text-primary font-weight-bold" style="text-decoration: none;">Login here</a>
            </p>
            
            <a href="../index.php" class="text-muted" style="text-decoration: none; font-size: 0.85rem; display: inline-block; margin-top: 10px;">
                ⬅ Back to Home
            </a>
        </div>
    </div>
</div>

</body>
</html>