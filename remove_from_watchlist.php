<?php
session_start();
header('Content-Type: application/json');
if (!isset($_SESSION['user'])) {
    echo json_encode(['error' => 'User not authenticated', 'session' => $_SESSION]);
    exit;
}


require_once 'database.php'; // Ensure this includes your database connection logic

// Get the raw input data
$input = json_decode(file_get_contents('php://input'), true);
if (!isset($input['movieID']) || !is_numeric($input['movieID'])) {
    echo json_encode(['error' => 'Invalid movie ID']);
    exit;
}

$movieID = $input['movieID'];
$username = $_SESSION['user'];

// Get the user's WatchListID
$query = "SELECT UserID FROM user WHERE Username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['error' => 'User not found']);
    exit;
}

$row = $result->fetch_assoc();
$userID = $row['UserID'];

// Delete the movie from the watchlist
$deleteQuery = "DELETE FROM WatchList_has_Movies WHERE WatchList_WatchListID = ? AND Movies_MovieID = ?";
$stmt = $conn->prepare($deleteQuery);
$stmt->bind_param("ii", $userID, $movieID);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Failed to remove movie']);
}
