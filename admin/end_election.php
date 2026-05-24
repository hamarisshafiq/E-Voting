<?php
include "../db.php";

$conn->query("UPDATE settings SET is_election_active=0 WHERE id=1");

header("Location: dashboard.php");
?>