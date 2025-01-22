<?php
session_start();


// Database connection
$dbConnection = new mysqli("localhost", "root", "", "mydb");

if ($dbConnection->connect_error) {
    die("Connection failed: " . $dbConnection->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
     // Assuming a logged-in user with ID 1 (replace dynamically)
    $movieId = $_POST['movieId'];
    $rating = $_POST['rating'];
    $apiRating = $_POST['apiRating'];
    $apiCount = $_POST['apiCount'];


    $username = $_SESSION['user'];
    $user_id = "";

    // Query to retrieve the UserID from the user table
    $query = "SELECT UserID FROM user WHERE Username = '$username'";

    // Execute the query
    $result = mysqli_query($dbConnection, $query);

    // Fetch the result
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $user_id = $row['UserID']; // Extract the UserID
    } else {
        echo "Failed to retrieve UserID for the username: $username";
        exit;
    }



    // Check if the user already rated the movie
    $checkQuery = "SELECT COUNT(*) as count FROM RatedMovies WHERE MovieID = ? AND User_UserID = ?";
    $stmt = $dbConnection->prepare($checkQuery);
    $stmt->bind_param("ii", $movieId, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['count'] > 0) {
        echo "You have already rated this movie.";
    } else {
        // Insert the rating into the database
        $insertQuery = "INSERT INTO RatedMovies (MovieID, User_UserID) VALUES (?, ?)";
        $stmt = $dbConnection->prepare($insertQuery);
        $stmt->bind_param("ii", $movieId, $user_id);

        if ($stmt->execute()) {
            echo "Thank you for your rating!";
        } else {
            echo "Error: Could not submit your rating.";
        }


        // Check if the movie exists in the Movies table
        $checkMovieQuery = "SELECT Rating, Count FROM Movies WHERE MovieID = '$movieId'";
        $result = mysqli_query($dbConnection, $checkMovieQuery);

        if (mysqli_num_rows($result) === 0) {
            // Insert the movie into the Movies table if it doesn't exist

            $rating = (($apiRating * $apiCount)+$rating)/($apiCount+1);
            $apiCount = $apiCount + 1;
            
            $insertRate = "INSERT INTO movies (MovieID, Rating, Count) VALUES ('$movieId', '$rating', '$apiCount')";
            if (!mysqli_query($dbConnection, $insertRate)) {
                echo "Failed to insert movie into Movies table: " . mysqli_error($conn);
                exit;
            }
        }else{

            $row = mysqli_fetch_assoc($result);
            $ratingDb = $row['Rating'];
            $countDb = $row['Count'];

            if($ratingDb != null){
                $rating = (($ratingDb * $countDb)+$rating)/($countDb+1);
                $countDb = $countDb + 1;
                
                $stmt = $dbConnection->prepare("UPDATE movies SET Rating = ?, Count = ? WHERE MovieID = ?");
                $stmt->bind_param("si", $rating,$countDb, $movieId);
                $stmt->execute();
            }else{
                $rating = (($apiRating * $apiCount)+$rating)/($apiCount+1);
                $apiCount = $apiCount + 1;
                $stmt = $dbConnection->prepare("UPDATE movies SET Rating = ?, Count = ? WHERE MovieID = ?");
                $stmt->bind_param("dii", $rating,$apiCount,$movieId);
                $stmt->execute();
            }


        }

        

    }
}


$dbConnection->close();
?>