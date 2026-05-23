$settings = $conn->query("SELECT * FROM settings WHERE id=1")->fetch_assoc();

$now = date("Y-m-d H:i:s");

if ($now < $settings['start_time'] || $now > $settings['end_time']) {
    echo "Voting is closed!";
    exit();
}
<?php
session_start();
include "../db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$candidate_id = $_GET['cid'];

// check if user already voted
$check = $conn->query("SELECT * FROM users WHERE id=$user_id");
$user = $check->fetch_assoc();

if ($user['has_voted'] == 1) {
    echo "You already voted!";
    exit();
}

// insert vote
$conn->query("INSERT INTO votes (user_id, candidate_id) VALUES ($user_id, $candidate_id)");

// mark user as voted
$conn->query("UPDATE users SET has_voted=1 WHERE id=$user_id");

header("Location: dashboard.php");
?>