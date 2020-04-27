<?php
include 'autenticador.php';
$conexion = conectar();

$id_mensaje = $_GET['id']; //Id del mensaje
$id_sesion = $_SESSION['usuario']['id'];
$sitio = $_GET['sitio'];

$autenticador = new autenticador();

try {
	$autenticador->estaAutorizado($id_mensaje, $id_sesion);
} catch (Exception $e) {
	$_SESSION['errores'] = '<li>El usuario no puede realizar esta accion.</li>';
	header('Location: '. $sitio);	
} 

header('Location: '. $sitio);