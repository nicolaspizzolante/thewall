<?php 
include 'db.php';
include 'validarPassword.php';
session_start();

$conexion = conectar();

$id = $_POST['id'];
$pass = $_POST['pass'];
$nueva_pass = $_POST['nueva_pass'];
$repetir = $_POST['repetir_pass'];

$sql = "SELECT contrasenia FROM usuarios WHERE contrasenia = '$pass' and id = '$id'";
$vieja_pass = $conexion->query($sql)->fetch_assoc()['contrasenia'];

if ($vieja_pass){ //si ingreso bien su vieja contrasenia

	$errores = '';
	if(!validar_password($nueva_pass,$errores)){ //si la contraseña nueva no cumple condiciones
		$_SESSION['errores'] = $errores;
		header('Location: cambiarcontrasenia.php');
		exit;
	}
	
	if ($nueva_pass != $repetir) {
		$_SESSION['errores'] = "<li>Repita bien la contraseña.</li>";
	} else {
		if($nueva_pass == $vieja_pass){
			$_SESSION['errores'] = "<li>Su nueva contraseña no puede ser igual a la anterior.</li>";
		} else {
			$sql = "UPDATE usuarios SET contrasenia = '$nueva_pass' WHERE id = '$id'";
			$resultado = $conexion->query($sql);
			$_SESSION['exito'] = "<li>Cambio de contraseña con exito.</li>";
		}
	}

} else {
	$_SESSION['errores'] = "<li>Contraseña incorrecta.</li>";
}

header('Location: cambiarcontrasenia.php');