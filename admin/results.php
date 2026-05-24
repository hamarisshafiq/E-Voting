<?php
session_start();
include "../db.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// SETTINGS
$settings = $conn->query("SELECT * FROM settings WHERE id=1");
$settings = $settings ? $settings->fetch_assoc() : ['is_result_declared' => 0];


// GET HIGHEST VOTES
$topVoteQuery = $conn->query("
    SELECT MAX(vote_count) as max_votes
    FROM (
        SELECT COUNT(*) as vote_count
        FROM votes
        GROUP BY candidate_id
    ) as temp
");

$topVoteData = $topVoteQuery ? $topVoteQuery->fetch_assoc() : null;

$max_votes = $topVoteData['max_votes'] ?? 0;


// CHECK DRAW (SAFE VERSION)
$isDraw = false;
$drawCheck = null;

if ($max_votes > 0) {

    $drawQuery = "
        SELECT candidate_id, COUNT(*) as total
        FROM votes
        GROUP BY candidate_id
        HAVING total = $max_votes
    ";

    $drawCheck = $conn->query($drawQuery);

    if ($drawCheck) {
        $isDraw = ($drawCheck->num_rows > 1);
    } else {
        $isDraw = false;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Election Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
    <style>
        .progress { height: 12px; border-radius: 10px; background-color: #f1f5f9; }
        .progress-bar { border-radius: 10px; }
    </style>
</head>

<body>

<div class="container mt-5">

    <div class="dashboard-header mb-5">
        <div>
            <h3 class="mb-0" style="color: var(--primary-color);">🏆 Election Results</h3>
            <p class="text-muted mb-0">Live monitoring of candidate standings</p>
        </div>
        <a href="dashboard.php" class="btn btn-secondary px-4">⬅ Back</a>
    </div>

    <!-- Overall Statistics -->
    <?php 
    $totalVotesQuery = $conn->query("SELECT COUNT(*) as total FROM votes");
    $totalAllVotes = $totalVotesQuery ? $totalVotesQuery->fetch_assoc()['total'] : 0;
    ?>
    
    <div class="row mb-5">
        <div class="col-12">
            <div class="card p-4 text-center" style="background: linear-gradient(135deg, var(--primary-color), var(--primary-hover)); color: white;">
                <h6 style="opacity: 0.9; text-transform: uppercase; letter-spacing: 1px;">Total Votes Cast</h6>
                <h1 style="font-size: 3rem; margin: 0; color: white;"><?php echo $totalAllVotes; ?></h1>
            </div>
        </div>
    </div>

    <div class="row">

    <?php
    $result = $conn->query("SELECT * FROM candidates");

    while($row = $result->fetch_assoc()) {

        $cid = $row['id'];

        // TOTAL VOTES
        $voteQuery = $conn->query("
            SELECT COUNT(*) as total_votes
            FROM votes
            WHERE candidate_id=$cid
        ");

        $voteData = $voteQuery ? $voteQuery->fetch_assoc() : ['total_votes' => 0];
        $total_votes = $voteData['total_votes'];
        
        $votePercentage = ($totalAllVotes > 0) ? round(($total_votes / $totalAllVotes) * 100, 1) : 0;

        // STATUS
        $status = "Result Pending";
        $badge = "secondary";
        $cardStyle = "";

        if ($settings['is_result_declared'] == 1) {

            if ($isDraw && $total_votes == $max_votes) {
                $status = "Result Draw";
                $badge = "warning text-dark";
                $cardStyle = "border: 2px solid #ffc107;";

            } elseif ($total_votes == $max_votes && $max_votes > 0) {
                $status = "Winner 🎉";
                $badge = "success px-3 py-2";
                $cardStyle = "border: 2px solid var(--success-color); box-shadow: 0 0 20px rgba(16, 185, 129, 0.2) !important;";

            } else {
                $status = "Lost";
                $badge = "danger";
                $cardStyle = "opacity: 0.7;";
            }
        }
    ?>

        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card interactive-card pt-0 h-100" style="<?php echo $cardStyle; ?>">

                <img src="../assets/images/<?php echo $row['picture']; ?>"
                     class="candidate-avatar mt-4">

                <div class="candidate-card-body">
                    <h5 style="font-weight: 700; color: var(--text-main);"><?php echo $row['name']; ?></h5>
                    <p class="text-muted mb-3" style="font-size: 0.9rem;"><?php echo $row['party']; ?> • <?php echo $row['city']; ?></p>

                    <div class="mt-4 text-start">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="fw-bold" style="font-size: 1.1rem;"><?php echo $total_votes; ?> Votes</span>
                            <span class="text-muted fw-bold"><?php echo $votePercentage; ?>%</span>
                        </div>
                        <div class="progress mb-4">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: <?php echo $votePercentage; ?>%" aria-valuenow="<?php echo $votePercentage; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>

                    <div class="mt-auto">
                        <span class="badge bg-<?php echo $badge; ?> w-100 py-2" style="font-size: 0.95rem;">
                            <?php echo $status; ?>
                        </span>
                    </div>

                </div>
            </div>
        </div>

    <?php } ?>

    </div>
</div>

</body>
</html>