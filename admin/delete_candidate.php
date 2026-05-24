<?php
include "../db.php";

$id = $_GET['id'];

// delete query
$sql = "DELETE FROM candidates WHERE id=$id";

if ($conn->query($sql)) {
    header("Location: view_candidates.php");
} else {
    echo "Error deleting candidate";
}
?>