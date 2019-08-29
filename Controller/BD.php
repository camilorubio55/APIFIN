<?php
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $host = "localhost";
    $db_name = "Proyecto";
    $username = "root";
    $password = "root";
}
else {
    http_response_code(405);
}
$projects = [];
?>