<?php
session_start();
include "../db.php";

$error = "";

if (isset($_POST['login'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {

        $row = $result->fetch_assoc();

        $_SESSION['user_id'] = $row['id'];
        $_SESSION['user_name'] = $row['name'];

        header("Location: dashboard.php");

    } else {
        $error = "Invalid Login!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>

<body>

<div class="auth-wrapper">
    <div class="auth-card">
        <div class="text-center mb-4">
            <span style="font-size: 2.5rem;">👤</span>
            <h3 class="mt-2" style="font-weight: 700; color: var(--text-main);">User Login</h3>
            <p class="text-muted">Welcome back! Please login to your account.</p>
        </div>

        <?php if($error): ?>
        <div class="alert alert-danger p-2 text-center" style="font-size: 0.9rem;">
            <?php echo $error; ?>
        </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="Email Address" required>
            </div>
            
            <div class="mb-4">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>

            <button type="submit" name="login" class="btn btn-primary w-100 py-2">Sign In</button>
        </form>

        <div class="text-center mt-4">
            <p class="text-muted mb-1" style="font-size: 0.9rem;">
                Don't have an account? 
                <a href="register.php" class="text-primary font-weight-bold" style="text-decoration: none;">
                    Create Account
                </a>
            </p>
            
            <a href="../index.php" class="text-muted" style="text-decoration: none; font-size: 0.85rem; display: inline-block; margin-top: 10px;">
                ⬅ Back to Home
            </a>
        </div>
    </div>
</div>

</body>
</html>