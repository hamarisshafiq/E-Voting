<?php
include "../db.php";

$result = $conn->query("SELECT * FROM candidates");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Candidates</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>

<body>

<div class="container mt-5">

    <div class="dashboard-header mb-4">
        <div>
            <h3 class="mb-0" style="color: var(--primary-color);">📋 All Candidates</h3>
            <p class="text-muted mb-0">Manage registered candidates</p>
        </div>

        <a href="dashboard.php" class="btn btn-secondary px-4">
            ⬅ Back to Dashboard
        </a>
    </div>

    <div class="card p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Candidate Name</th>
                        <th scope="col">Party</th>
                        <th scope="col">City</th>
                        <th scope="col">Photo</th>
                        <th scope="col" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><span class="badge bg-light text-dark border"><?php echo $row['unique_id']; ?></span></td>
                        <td class="fw-bold text-dark"><?php echo $row['name']; ?></td>
                        <td><span class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle"><?php echo $row['party']; ?></span></td>
                        <td><?php echo $row['city']; ?></td>
                        <td>
                            <img src="../assets/images/<?php echo $row['picture']; ?>" 
                                 class="rounded shadow-sm" 
                                 style="width: 50px; height: 50px; object-fit: cover;">
                        </td>
                        <td class="text-center">
                            <a href="edit_candidate.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm me-2 text-dark fw-bold">Edit</a>
                            <a href="delete_candidate.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this candidate?');">Delete</a>
                        </td>
                    </tr>
                    <?php } ?>
                    <?php if($result->num_rows == 0): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No candidates found.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

</body>
</html>