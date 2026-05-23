<?php
include "../db.php";

$id = $_GET['id'];

$result = $conn->query("SELECT * FROM candidates WHERE id=$id");
$row = $result->fetch_assoc();

if (isset($_POST['update'])) {

    $name = $_POST['name'];
    $age = $_POST['age'];
    $party = $_POST['party'];
    $city = $_POST['city'];

    $sql = "UPDATE candidates SET 
            name='$name',
            age='$age',
            party='$party',
            city='$city'
            WHERE id=$id";

    if ($conn->query($sql)) {
        header("Location: view_candidates.php");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Candidate</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>

<body>

<div class="auth-wrapper">
    <div class="auth-card" style="max-width: 500px;">
        <div class="text-center mb-4">
            <span style="font-size: 2.5rem;">✏️</span>
            <h3 class="mt-2" style="font-weight: 700; color: var(--text-main);">Edit Candidate</h3>
            <p class="text-muted">Update candidate information.</p>
        </div>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label text-muted small fw-bold">Candidate Name</label>
                <input type="text" name="name" value="<?php echo $row['name']; ?>" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label text-muted small fw-bold">Age</label>
                <input type="number" name="age" value="<?php echo $row['age']; ?>" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label text-muted small fw-bold">Political Party</label>
                <input type="text" name="party" value="<?php echo $row['party']; ?>" class="form-control" required>
            </div>

            <div class="mb-4">
                <label class="form-label text-muted small fw-bold">City</label>
                <input type="text" name="city" value="<?php echo $row['city']; ?>" class="form-control" required>
            </div>

            <button type="submit" name="update" class="btn btn-success w-100 py-2">Update Information</button>
        </form>

        <div class="text-center mt-4">
            <a href="view_candidates.php" class="text-muted" style="text-decoration: none; font-size: 0.85rem; display: inline-block;">
                ⬅ Back to Candidates List
            </a>
        </div>
    </div>
</div>

</body>
</html>