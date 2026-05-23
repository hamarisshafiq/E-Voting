<?php
session_start();
include "../db.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// FETCH SETTINGS
$settings = $conn->query("SELECT * FROM settings WHERE id=1")->fetch_assoc();

$isActive = $settings['is_election_active'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>

<body>

<div class="container mt-5">

    <!-- TOP BAR -->
    <div class="dashboard-header mb-4">
        <div>
            <h3 class="mb-0" style="color: var(--primary-color);">Admin Dashboard</h3>
            <p class="text-muted mb-0">Control panel and election management</p>
        </div>

        <a href="logout.php" class="btn btn-danger px-4">
            Logout
        </a>
    </div>

    <!-- ELECTION STATUS -->
    <?php if($isActive == 1){ ?>
        <div class="alert alert-success d-flex align-items-center shadow-sm mb-4">
            <span style="font-size: 1.5rem; margin-right: 15px;">🟢</span>
            <div>
                <strong>Election is LIVE</strong><br>
                <small>Users are currently allowed to cast votes.</small>
            </div>
        </div>
    <?php } else { ?>
        <div class="alert alert-danger d-flex align-items-center shadow-sm mb-4">
            <span style="font-size: 1.5rem; margin-right: 15px;">🔴</span>
            <div>
                <strong>Election is CLOSED</strong><br>
                <small>Voting is disabled. Users cannot cast votes.</small>
            </div>
        </div>
    <?php } ?>

    <!-- MAIN PANELS -->
    <h5 class="mb-3" style="font-weight: 600; color: var(--text-muted);">Manage Election</h5>
    <div class="row mb-4">

        <div class="col-md-3 mb-3">
            <div class="card h-100 interactive-card p-4 text-center">
                <span style="font-size: 2.5rem; margin-bottom: 10px;">➕</span>
                <h6 class="mb-3" style="font-weight: 600;">Add Candidate</h6>
                <a href="add_candidate.php" class="btn btn-primary w-100 mt-auto">Open</a>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card h-100 interactive-card p-4 text-center">
                <span style="font-size: 2.5rem; margin-bottom: 10px;">📋</span>
                <h6 class="mb-3" style="font-weight: 600;">View Candidates</h6>
                <a href="view_candidates.php" class="btn btn-success w-100 mt-auto">Open</a>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card h-100 interactive-card p-4 text-center">
                <span style="font-size: 2.5rem; margin-bottom: 10px;">🏆</span>
                <h6 class="mb-3" style="font-weight: 600;">Results</h6>
                <a href="results.php" class="btn btn-warning w-100 mt-auto text-dark" style="font-weight:600;">View</a>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card h-100 interactive-card p-4 text-center">
                <span style="font-size: 2.5rem; margin-bottom: 10px;">🏁</span>
                <h6 class="mb-3" style="font-weight: 600;">Declare Result</h6>
                <a href="declare_result.php" class="btn btn-danger w-100 mt-auto">Finalize Election</a>
            </div>
        </div>

    </div>

    <!-- ADMIN CONTROLS -->
    <h5 class="mb-3" style="font-weight: 600; color: var(--text-muted);">Election Controls</h5>
    <div class="row">

        <!-- START ELECTION -->
        <div class="col-md-4 mb-3">
            <div class="card h-100 interactive-card p-4 text-center" 
                 style="<?php echo ($isActive == 1) ? 'border: 2px solid var(--success-color) !important;' : ''; ?>">
                <span style="font-size: 2.5rem; margin-bottom: 10px;">🚀</span>
                <h5 class="mb-3">Start Election</h5>
                <a href="start_election.php" 
                   class="btn w-100 mt-auto <?php echo ($isActive == 1) ? 'btn-success' : 'btn-secondary'; ?>"
                   style="<?php echo ($isActive == 1) ? 'opacity: 1;' : 'opacity: 0.8;'; ?>">
                    <?php echo ($isActive == 1) ? 'Running Now' : 'Start'; ?>
                </a>
            </div>
        </div>

        <!-- END ELECTION -->
        <div class="col-md-4 mb-3">
            <div class="card h-100 interactive-card p-4 text-center"
                 style="<?php echo ($isActive == 0) ? 'border: 2px solid var(--danger-color) !important;' : ''; ?>">
                <span style="font-size: 2.5rem; margin-bottom: 10px;">🛑</span>
                <h5 class="mb-3">End Election</h5>
                <a href="end_election.php" 
                   class="btn w-100 mt-auto <?php echo ($isActive == 0) ? 'btn-danger' : 'btn-secondary'; ?>"
                   style="<?php echo ($isActive == 0) ? 'opacity: 1;' : 'opacity: 0.8;'; ?>">
                    <?php echo ($isActive == 0) ? 'Stopped' : 'Stop'; ?>
                </a>
            </div>
        </div>

        <!-- CLEAR RESULT -->
        <div class="col-md-4 mb-3">
            <div class="card h-100 interactive-card p-4 text-center">
                <span style="font-size: 2.5rem; margin-bottom: 10px;">🗑️</span>
                <h5 class="mb-3">Clear Result</h5>
                <a href="clear_result.php" class="btn btn-dark w-100 mt-auto" onclick="return confirm('Are you sure you want to completely clear all votes and results?');">
                    Clear Data
                </a>
            </div>
        </div>

    </div>

</div>

</body>
</html>