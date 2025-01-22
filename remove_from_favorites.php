<?php
session_start();
header('Content-Type: application/json');  // Ensure the response is JSON

if (!isset($_SESSION['user'])) {
    echo json_encode(['error' => 'User not authenticated', 'session' => $_SESSION]);
    exit;
}

// Existing code...


require_once 'database.php';

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['movieID']) || !is_numeric($input['movieID'])) {
    echo json_encode(['error' => 'Invalid movie ID']);
    exit;
}

$movieID = $input['movieID'];
$username = $_SESSION['user'];

$query = "SELECT UserID FROM user WHERE Username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);

if (!$stmt->execute()) {
    echo json_encode(['error' => 'Failed to fetch user ID']);
    exit;
}

$result = $stmt->get_result();
if ($result->num_rows === 0) {
    echo json_encode(['error' => 'User not found']);
    exit;
}

$row = $result->fetch_assoc();
$userID = $row['UserID'];

$deleteQuery = "DELETE FROM favoritemovies_has_movies WHERE FavoriteMovies_FavoriteListID = ? AND Movies_MovieID = ?";
$stmt = $conn->prepare($deleteQuery);
$stmt->bind_param("ii", $userID, $movieID);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Movie not found in favorites']);
    }
} else {
    echo json_encode(['error' => 'Failed to remove movie']);
}
