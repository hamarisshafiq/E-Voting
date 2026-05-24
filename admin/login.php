<?php
session_start();
include "../db.php";

$error = "";

if (isset($_POST['login'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM admin WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $_SESSION['admin'] = $username;
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
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>

<body>

<div class="auth-wrapper">
    <div class="auth-card">
        <div class="text-center mb-4">
            <span style="font-size: 2.5rem;">👨‍💼</span>
            <h3 class="mt-2" style="font-weight: 700; color: var(--text-main);">Admin Login</h3>
            <p class="text-muted">Secure access to the control panel.</p>
        </div>

        <?php if($error): ?>
        <div class="alert alert-danger p-2 text-center" style="font-size: 0.9rem;">
            <?php echo $error; ?>
        </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <input type="text" name="username" class="form-control" placeholder="Admin Username" required>
            </div>
            
            <div class="mb-4">
                <input type="password" name="password" class="form-control" placeholder="Admin Password" required>
            </div>

            <button type="submit" name="login" class="btn btn-danger w-100 py-2">Authenticate</button>
        </form>

        <div class="text-center mt-4">
            <a href="../index.php" class="text-muted" style="text-decoration: none; font-size: 0.85rem; display: inline-block;">
                ⬅ Back to Home
            </a>
        </div>
    </div>
</div>

</body>
</html>