<?php
include "../db.php";

$msg = "";

if (isset($_POST['submit'])) {

    $unique_id = "CAND-" . uniqid();
    $name = $_POST['name'];
    $age = $_POST['age'];
    $party = $_POST['party'];
    $city = $_POST['city'];

    // image upload
    $image = $_FILES['image']['name'];
    $temp = $_FILES['image']['tmp_name'];

    $folder = "../assets/images/" . $image;
    move_uploaded_file($temp, $folder);

    $sql = "INSERT INTO candidates (unique_id, name, age, party, city, picture)
            VALUES ('$unique_id', '$name', '$age', '$party', '$city', '$image')";

    if ($conn->query($sql)) {
        $msg = "Candidate Added Successfully!";
    } else {
        $msg = "Error Adding Candidate!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Candidate</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>

<body>

<div class="auth-wrapper">
    <div class="auth-card" style="max-width: 500px;">
        <div class="text-center mb-4">
            <span style="font-size: 2.5rem;">➕</span>
            <h3 class="mt-2" style="font-weight: 700; color: var(--text-main);">Add Candidate</h3>
            <p class="text-muted">Register a new candidate for the election.</p>
        </div>

        <?php if($msg): ?>
            <div class="alert <?php echo strpos($msg, 'Error') !== false ? 'alert-danger' : 'alert-success'; ?> p-2 text-center mb-4">
                <?php echo $msg; ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label text-muted small fw-bold">Candidate Name</label>
                <input type="text" name="name" class="form-control" placeholder="Full Name" required>
            </div>

            <div class="mb-3">
                <label class="form-label text-muted small fw-bold">Age</label>
                <input type="number" name="age" class="form-control" placeholder="Age" required>
            </div>

            <div class="mb-3">
                <label class="form-label text-muted small fw-bold">Political Party</label>
                <input type="text" name="party" class="form-control" placeholder="Party Name" required>
            </div>

            <div class="mb-3">
                <label class="form-label text-muted small fw-bold">City</label>
                <input type="text" name="city" class="form-control" placeholder="City" required>
            </div>

            <div class="mb-4">
                <label class="form-label text-muted small fw-bold">Candidate Picture</label>
                <input type="file" name="image" class="form-control" required>
            </div>

            <button type="submit" name="submit" class="btn btn-primary w-100 py-2">Save Candidate</button>
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