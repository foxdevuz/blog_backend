<?php
$server = "localhost";
$server_username = "root";
$server_password = "";
$db_name = "blog";

$conn = mysqli_connect($server, $server_username,$server_password, $db_name);

function realstring($text) {
    global $conn;
    return mysqli_real_escape_string($conn, $text);
}
?>