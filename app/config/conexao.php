<?php
$host = "localhost";
$user = "root";
$pass = "52461309";
$banco = "delivery";

$conn = new mysqli($host, $user, $pass, $banco);

if ($conn->connect_errno) {
    die("Erro ao conectar: " . $conn->connect_error);
}
?>
