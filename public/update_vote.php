<?php
session_start();
require_once('connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['voter_id'])) {
    $voterId = $_POST['voter_id'];

    // Check if the user has already voted in this session
    if (isset($_SESSION['voted_' . $voterId])) {
        echo "You have already voted for this candidate.";
        exit;
    }

    // Update vote count in the database
    $sql_update_vote = "UPDATE voters SET vote_counter = vote_counter + 1 WHERE id = :id";
    $stmt = $conn->prepare($sql_update_vote);
    $stmt->bindParam(':id', $voterId);
    $stmt->execute();

    // Set session variable to mark that the user has voted for this candidate
    $_SESSION['voted_' . $voterId] = true;

    // Return a response (optional)
    echo "Vote counted successfully";
}
?>
