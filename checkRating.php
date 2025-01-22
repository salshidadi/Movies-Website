<?php 
require_once "database.php";

// Ensure the response is JSON
header('Content-Type: application/json');

// Get the raw POST data
$input = file_get_contents('php://input');

// Decode the JSON into a PHP array
$data = json_decode($input, true);

// Check if data was received
if ( isset($data['id']) ) {

    $id = $data['id'];

    $sql = "SELECT MovieID FROM movies WHERE MovieID = $id";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_execute($stmt);

    






    // Create a response
    $response = [
        'status' => 'success' ];
} else {
    // Error response if data is missing
    $response = [
        'status' => 'error',
        'message' => 'Invalid data received.'
    ];
}

// Send the JSON response back to JavaScript
echo json_encode($response);

?>