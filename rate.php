<?php
function checkAndRateMovie($userId, $movieId, $dbConnection) {
    // Check if the user has already rated the movie
    $checkQuery = "SELECT COUNT(*) as count FROM RatedMovies WHERE MovieID = ? AND User_UserID = ?";
    $stmt = $dbConnection->prepare($checkQuery);
    $stmt->bind_param("ii", $movieId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['count'] > 0) {
        // The user has already rated this movie
        return 1;
    }

    // User hasn't rated this movie, so insert the rating
    $insertQuery = "INSERT INTO RatedMovies (MovieID, User_UserID) VALUES (?, ?)";
    $stmt = $dbConnection->prepare($insertQuery);
    $stmt->bind_param("ii", $movieId, $userId);

    if ($stmt->execute()) {
        return -1;
    }

    // Return error if something went wrong
    return 0;
}