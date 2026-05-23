<?php
session_start();
include "../db.php";

// CHECK ADMIN LOGIN
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// GET SETTINGS
$settings = $conn->query("SELECT * FROM settings WHERE id=1")->fetch_assoc();


// ❌ DO NOT DECLARE RESULT IF ELECTION IS RUNNING
if ($settings['is_election_active'] == 1) {

    echo "
    <!DOCTYPE html><html><head><link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'></head>
    <body style='background:#f8fafc; display:flex; justify-content:center; align-items:center; height:100vh;'>
    <div style='background:white; padding:40px; border-radius:15px; box-shadow:0 10px 25px rgba(0,0,0,0.05); text-align:center;'>
        <div style='font-size: 3rem; margin-bottom: 20px;'>🛑</div>
        <h2 style='color:#ef4444; font-weight:700; margin-bottom: 10px;'>Stop Election First</h2>
        <p class='text-muted mb-4'>You must stop the active election before declaring a result.</p>
        <a href='dashboard.php' class='btn btn-primary px-4 py-2'>⬅ Back to Dashboard</a>
    </div>
    </body></html>
    ";

    exit();
}


// FIND WINNER (HIGHEST VOTES)

$sql = "
SELECT candidate_id, COUNT(*) as total_votes
FROM votes
GROUP BY candidate_id
ORDER BY total_votes DESC
LIMIT 1
";

$result = $conn->query($sql);


// CHECK IF VOTES EXIST

if ($result->num_rows > 0) {

    $row = $result->fetch_assoc();

    $winner_id = $row['candidate_id'];

    // UPDATE SETTINGS TABLE
    $conn->query("
        UPDATE settings 
        SET is_result_declared=1,
            winner_id=$winner_id
        WHERE id=1
    ");

    header("Location: results.php");
    exit();

} else {

    echo "
    <!DOCTYPE html><html><head><link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'></head>
    <body style='background:#f8fafc; display:flex; justify-content:center; align-items:center; height:100vh;'>
    <div style='background:white; padding:40px; border-radius:15px; box-shadow:0 10px 25px rgba(0,0,0,0.05); text-align:center;'>
        <div style='font-size: 3rem; margin-bottom: 20px;'>⚠️</div>
        <h2 style='color:#ef4444; font-weight:700; margin-bottom: 10px;'>No Votes Found</h2>
        <p class='text-muted mb-4'>Result cannot be declared because no votes have been cast yet.</p>
        <a href='dashboard.php' class='btn btn-primary px-4 py-2'>⬅ Back to Dashboard</a>
    </div>
    </body></html>
    ";
}
?>