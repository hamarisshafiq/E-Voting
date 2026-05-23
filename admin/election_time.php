<?php
include "../db.php";

$msg = "";

if (isset($_POST['save'])) {

    $start = $_POST['start_time'];
    $end = $_POST['end_time'];

    $conn->query("UPDATE settings SET start_time='$start', end_time='$end' WHERE id=1");

    $msg = "Election Time Updated!";
}

$data = $conn->query("SELECT * FROM settings WHERE id=1")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Election Time</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>

<body>

<div class="auth-wrapper">
    <div class="auth-card" style="max-width: 500px;">
        <div class="text-center mb-4">
            <span style="font-size: 2.5rem;">⏱️</span>
            <h3 class="mt-2" style="font-weight: 700; color: var(--text-main);">Set Election Time</h3>
            <p class="text-muted">Configure the voting window.</p>
        </div>

        <?php if($msg): ?>
            <div class="alert alert-success p-2 text-center mb-4">
                <?php echo $msg; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label text-muted small fw-bold">Start Time</label>
                <input type="datetime-local" name="start_time" class="form-control"
                       value="<?php echo date('Y-m-d\TH:i', strtotime($data['start_time'])); ?>">
            </div>

            <div class="mb-4">
                <label class="form-label text-muted small fw-bold">End Time</label>
                <input type="datetime-local" name="end_time" class="form-control"
                       value="<?php echo date('Y-m-d\TH:i', strtotime($data['end_time'])); ?>">
            </div>

            <button type="submit" name="save" class="btn btn-primary w-100 py-2">Save Settings</button>
        </form>

        <div class="text-center mt-4">
            <a href="dashboard.php" class="text-muted" style="text-decoration: none; font-size: 0.85rem; display: inline-block;">
                ⬅ Back to Dashboard
            </a>
        </div>
    </div>
</div>

</body>
</html>