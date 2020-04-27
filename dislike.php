<?php
include 'db.php';
session_start();

$conexion = conectar();

$usuario_id = $_SESSION['usuario']['id'];
$mensaje_id = $_GET['mensaje_id'];
$sitio = $_GET['sitio'];

$sql = "DELETE FROM me_gusta WHERE usuarios_id = '$usuario_id' and mensaje_id = '$mensaje_id'";
$resultado = $conexion->query($sql);

header('Location: ' . $sitio);
