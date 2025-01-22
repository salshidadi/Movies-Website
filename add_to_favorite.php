<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location:index.php");
    exit;
}

require_once 'database.php'; // Include the database connection

if (isset($_POST['movie_id'])) {
    $movie_id = $_POST['movie_id'];
    
    $username = $_SESSION['user'];
    $user_id = "";

    // Query to retrieve the UserID from the user table
    $query = "SELECT UserID FROM user WHERE Username = '$username'";

    // Execute the query
    $result = mysqli_query($conn, $query);

    // Fetch the result
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $user_id = $row['UserID']; // Extract the UserID
    } else {
        echo "Failed to retrieve UserID for the username: $username";
        exit;
    }
    
    $favorite_list_id = $user_id; // Assuming the FavoriteListID is the same as the UserID

    // Validate input
    if (!is_numeric($movie_id)) {
        echo "Invalid movie ID.";
        exit;
    }

    // Check if the movie already exists in the FavoriteMovies_has_Movies table
    $checkQuery = "SELECT * FROM FavoriteMovies_has_Movies WHERE FavoriteMovies_FavoriteListID = '$favorite_list_id' AND Movies_MovieID = '$movie_id'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        echo "Movie is already in your favorites.";
        exit;
    }

    // Check if the movie exists in the Movies table
    $checkMovieQuery = "SELECT * FROM Movies WHERE MovieID = $movie_id";
    $result = mysqli_query($conn, $checkMovieQuery);

    if (mysqli_num_rows($result) === 0) {
        // Insert the movie into the Movies table if it doesn't exist
        $insertMovieQuery = "INSERT INTO Movies (MovieID) VALUES ('$movie_id')";
        if (!mysqli_query($conn, $insertMovieQuery)) {
            echo "Failed to insert movie into Movies table: " . mysqli_error($conn);
            exit;
        }
    }

    // Insert the movie into the FavoriteMovies_has_Movies table
    $insertFavoriteQuery = "INSERT INTO FavoriteMovies_has_Movies (FavoriteMovies_FavoriteListID, Movies_MovieID) VALUES ('$favorite_list_id', '$movie_id')";
    if (mysqli_query($conn, $insertFavoriteQuery)) {
        echo "Movie successfully added to favorites.";
    } else {
        echo "Failed to add movie to favorites: " . mysqli_error($conn);
    }
} else {
    echo "Movie ID not received.";
}
