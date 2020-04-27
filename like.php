<?php
include 'db.php';
session_start();

$conexion = conectar();

$usuario_id = $_SESSION['usuario']['id'];
$mensaje_id = $_GET['mensaje_id'];
$sitio = $_GET['sitio'];

$sql = "INSERT INTO me_gusta (usuarios_id, mensaje_id)
		VALUES ('$usuario_id', '$mensaje_id')";
$resultado = $conexion->query($sql);

header('Location: ' . $sitio);