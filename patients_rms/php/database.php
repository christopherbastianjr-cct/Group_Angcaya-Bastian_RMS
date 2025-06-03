<?php
$db_server = "localhost";
$db_user = "Andrew ";
$db_pass = "pogiako";
$db_name = "gms";

$password = getenv("DB_PASSWORD"); // or from config
$conn = new mysqli("localhost", "root", $password, "gms");


if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
