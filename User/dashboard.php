<?php
session_start();
include "../db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// USER
$check = $conn->query("SELECT * FROM users WHERE id=$user_id");
$user = $check->fetch_assoc();

// SETTINGS
$settings = $conn->query("SELECT * FROM settings WHERE id=1")->fetch_assoc();

$voting_closed = ($settings['is_election_active'] == 0);


// ========================
// DASHBOARD STATS
// ========================

// Total Candidates
$candidatesQ = $conn->query("SELECT COUNT(*) as total_candidates FROM candidates");
$total_candidates = $candidatesQ->fetch_assoc()['total_candidates'];

// Total Votes
$votesQ = $conn->query("SELECT COUNT(*) as total_votes FROM votes");
$total_votes = $votesQ->fetch_assoc()['total_votes'];


// My Vote Status
$myVoteQ = $conn->query("
    SELECT candidates.name, candidates.party
    FROM votes
    JOIN candidates ON votes.candidate_id = candidates.id
    WHERE votes.user_id = $user_id
    LIMIT 1
");

$myVote = $myVoteQ->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>

<body>

<div class="container mt-5">

    <!-- TOP BAR -->
    <div class="dashboard-header">
        <div>
            <h3 class="mb-0" style="color: var(--primary-color);">
                Dashboard
            </h3>

            <p class="text-muted mb-0">
                Welcome back,
                <strong><?php echo $_SESSION['user_name']; ?></strong> 👋
            </p>
        </div>

        <a href="logout.php" class="btn btn-danger px-4">
            Logout
        </a>
    </div>

    <!-- STATS CARDS -->
    <div class="row mb-4">

        <div class="col-md-4 mb-3">
            <div class="card h-100"
                 style="background: linear-gradient(135deg, var(--primary-color), var(--primary-hover)); border:none;">

                <div class="stat-card-inner">
                    <h5>Total Candidates</h5>
                    <h2><?php echo $total_candidates; ?></h2>
                </div>

            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card h-100"
                 style="background: linear-gradient(135deg, var(--success-color), #059669); border:none;">

                <div class="stat-card-inner">
                    <h5>Total Votes Cast</h5>
                    <h2><?php echo $total_votes; ?></h2>
                </div>

            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card h-100"
                 style="background: linear-gradient(135deg, #334155, #1e293b); border:none;">

                <div class="stat-card-inner">

                    <h5>My Vote Status</h5>

                    <?php if($myVote){ ?>

                        <div class="mt-2">

                            <span class="badge bg-success mb-1">
                                ✔ Voted
                            </span><br>

                            <span style="font-size: 1.2rem; font-weight: 600;">
                                <?php echo $myVote['name']; ?>
                            </span><br>

                            <small style="opacity: 0.8;">
                                <?php echo $myVote['party']; ?>
                            </small>

                        </div>

                    <?php } else { ?>

                        <div class="mt-2">

                            <span class="badge bg-warning text-dark px-3 py-2"
                                  style="font-size: 1rem;">

                                  ❌ Not Voted Yet

                            </span>

                        </div>

                    <?php } ?>

                </div>

            </div>
        </div>

    </div>

    <!-- ELECTION STATUS -->
    <?php if($settings['is_election_active'] == 1){ ?>

        <div class="alert alert-success d-flex align-items-center shadow-sm mb-4">

            <span style="font-size: 1.5rem; margin-right: 15px;">
                🟢
            </span>

            <div>
                <strong>Election is LIVE</strong><br>
                <small>You can cast your vote now.</small>
            </div>

        </div>

    <?php } else { ?>

        <div class="alert alert-danger d-flex align-items-center shadow-sm mb-4">

            <span style="font-size: 1.5rem; margin-right: 15px;">
                🔴
            </span>

            <div>
                <strong>Election is CLOSED</strong><br>
                <small>Voting has ended or not started yet.</small>
            </div>

        </div>

    <?php } ?>

    <!-- VOTING CLOSED MESSAGE -->
    <?php if ($voting_closed) { ?>

        <div class="alert alert-warning text-center mt-4 shadow-sm">
            Voting has been stopped by Admin
        </div>

    <?php } ?>

    <!-- ALREADY VOTED -->
    <?php if ($user['has_voted'] == 1) { ?>

        <div class="alert alert-success mt-3 shadow-sm text-center">

            <strong>✅ Thank you!</strong>

            You have successfully cast your vote.

        </div>

    <?php } ?>

    <!-- CANDIDATES -->
    <h4 class="mt-5 mb-4"
        style="font-weight: 700; color: var(--text-main);">

        Election Candidates

    </h4>

    <div class="row">

    <?php
    $result = $conn->query("SELECT * FROM candidates");

    while($row = $result->fetch_assoc()) {
    ?>

        <div class="col-lg-4 col-md-6 mb-5">

            <div class="card h-100 mt-4 interactive-card pt-0">

                <img src="../assets/images/<?php echo $row['picture']; ?>"
                     class="candidate-avatar">

                <div class="candidate-card-body">

                    <h5 style="font-weight: 700;
                               color: var(--text-main);
                               margin-bottom: 5px;">

                        <?php echo $row['name']; ?>

                    </h5>

                    <div class="mb-3">

                        <span class="badge bg-primary px-3 py-1">
                            <?php echo $row['party']; ?>
                        </span>

                        <span class="badge bg-secondary px-3 py-1">
                            <?php echo $row['city']; ?>
                        </span>

                    </div>

                    <div class="mt-4">

                        <?php if ($user['has_voted'] == 0 && !$voting_closed) { ?>

                            <a href="vote.php?cid=<?php echo $row['id']; ?>"
                               class="btn btn-success w-100"
                               style="padding: 10px;">

                                Vote for <?php echo $row['name']; ?>

                            </a>

                        <?php } else { ?>

                            <button class="btn btn-secondary w-100"
                                    disabled
                                    style="padding: 10px; opacity: 0.7;">

                                Voted

                            </button>

                        <?php } ?>

                    </div>

                </div>

            </div>

        </div>

    <?php } ?>

    </div>

    <!-- RESULT SECTION -->
    <?php
    if (isset($settings['is_result_published']) &&
        $settings['is_result_published'] == 1) {

        $winnerQ = $conn->query("
            SELECT candidates.*, COUNT(votes.id) as total_votes
            FROM candidates
            LEFT JOIN votes
            ON candidates.id = votes.candidate_id
            GROUP BY candidates.id
            ORDER BY total_votes DESC
            LIMIT 1
        ");

        $winner = $winnerQ->fetch_assoc();
    ?>

    <div class="card shadow-lg border-0 mt-5 mb-5">

        <div class="card-body text-center p-5">

            <h2 class="mb-4 text-success">
                🏆 Election Winner
            </h2>

            <img src="../assets/images/<?php echo $winner['picture']; ?>"
                 width="140"
                 height="140"
                 style="border-radius:50%;
                        object-fit:cover;
                        border:5px solid #16a34a;">

            <h3 class="mt-4">
                <?php echo $winner['name']; ?>
            </h3>

            <h5 class="text-muted">
                <?php echo $winner['party']; ?>
            </h5>

            <div class="mt-3">

                <span class="badge bg-success px-4 py-2 fs-5">

                    Total Votes:
                    <?php echo $winner['total_votes']; ?>

                </span>

            </div>

        </div>

    </div>

    <?php } ?>

</div>

</body>
</html>