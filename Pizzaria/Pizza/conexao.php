<?php

$hostname = "localhost";
$db = "pizzaria";
$user = "root";
$pass = "";

$mysqli = new mysqli($hostname, $user, $pass, $db);

if($mysqli->connect_errno) {
    echo "Falha ao conectar: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

