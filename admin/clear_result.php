<?php
session_start();
include "../db.php";

// ADMIN CHECK
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}


// 1️⃣ CLEAR RESULT
$conn->query("
    UPDATE settings
    SET is_result_declared = 0,
        winner_id = NULL
    WHERE id = 1
");


// 2️⃣ CLEAR ALL VOTES
$conn->query("DELETE FROM votes");


// 3️⃣ RESET USERS VOTING STATUS
$conn->query("
    UPDATE users
    SET has_voted = 0
");


// 4️⃣ OPTIONAL: STOP ELECTION TOO
$conn->query("
    UPDATE settings
    SET is_election_active = 0
    WHERE id = 1
");


// REDIRECT
header("Location: dashboard.php");
exit();
?>