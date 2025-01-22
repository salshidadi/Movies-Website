<?php

$db_server = "localhost";

$db_user = "root";

$db_password = "";

$db_name = "mydb";

$conn = "";

try {
    $conn = mysqli_connect(
        $db_server,
        $db_user,
        $db_password,
        $db_name
    );
    if ($conn) {
    }
} catch (mysqli_sql_exception) {
    echo "unable to connect to the database<br>";
}
