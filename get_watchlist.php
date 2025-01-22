<?php
session_start();
if (!isset($_SESSION["user"])) {
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

require_once 'database.php';

$username = $_SESSION['user'];
$user_id = "";

// Query to retrieve the UserID from the users table
$query = "SELECT UserID FROM user WHERE Username = '$username'";

$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $user_id = $row['UserID'];
} else {
    echo json_encode(["error" => "Failed to retrieve UserID for the username: $username"]);
    exit;
}

// Query to get all movie IDs from the user's watchlist
$sql = "SELECT m.MovieID 
        FROM Movies m
        JOIN WatchList_has_Movies w ON m.MovieID = w.Movies_MovieID
        WHERE w.WatchList_WatchListID = '$user_id'";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $movies = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        $movie_id = $row['MovieID'];

        // Fetch movie details from the API using MovieID
        $api_url = "https://api.themoviedb.org/3/movie/$movie_id?api_key=a7bd821b8f02542c2ebda2c469352fe0";
        $movie_data = json_decode(file_get_contents($api_url), true);
        
        // Extract movie details from the API response
        $poster_path = $movie_data['poster_path'] ? 'https://image.tmdb.org/t/p/w500' . $movie_data['poster_path'] : 'default-poster.jpg';
        $title = $movie_data['title'];
        $overview = $movie_data['overview'];
        $vote_average = $movie_data['vote_average'];

        $movies[] = [
            "MovieID" => $movie_id,
            "title" => $title,
            "vote_average" => $vote_average,
            "overview" => $overview,
            "poster_path" => $poster_path
        ];
    }
    echo json_encode($movies);
} else {
    echo json_encode(["message" => "No movies found in the watchlist."]);
}
?>
