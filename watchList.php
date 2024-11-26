<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location:index.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="list2.css" type="text/css">
</head>

<body>
    <!--you will finde the templet in the watchList.html file-->
</body>

</html>