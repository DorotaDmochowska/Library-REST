<?php

define ("DB_HOST", "127.0.0.1");
define("DB_USER", "root");
define("DB_PASS", "coderslab");
define("DB_DATABASE", "Library");

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_DATABASE);

if ($conn->connect_error == FALSE) {
    echo " ";
} else {
    echo "Problem with connection. Error: " . $conn->connect_error;
    die;
}

$conn->set_charset('utf8');

?>

