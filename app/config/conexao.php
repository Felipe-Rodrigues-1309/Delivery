<?php
$host = "localhost";
$user = "delivery";
$pass = "1234562";
$banco = "delivery";

$conn = new mysqli($host, $user, $pass, $banco);

if ($conn->connect_errno) {
    die("Erro ao conectar: " . $conn->connect_error);
}
?>
