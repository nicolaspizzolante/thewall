<?php 
session_start();
include 'db.php';
$conexion = conectar();

$id = $_SESSION['usuario']['id'];
$sitio = isset($_GET['sitio']) ? $_GET['sitio'] : '';
$usuarioseguido_id = $_GET['usuarioseguido_id'];
$busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : '';

$sql = "INSERT INTO siguiendo (usuarios_id, usuarioseguido_id) VALUES ('$id', '$usuarioseguido_id')";
$resultado = $conexion->query($sql);

if ($busqueda){
	header('Location: buscar.php?busqueda='.$busqueda);
} else {
	header('Location: ' . $sitio);
}