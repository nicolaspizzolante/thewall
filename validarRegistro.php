<?php 
include 'db.php';
include 'validarPassword.php';
session_start();

$conexion = conectar();

$email = trim($_POST["email"]);
$nombre = trim($_POST["nombre"]);
$apellido = trim($_POST["apellido"]);
$nombreusuario = trim($_POST["nombreusuario"]);
$contrasenia = $_POST["contrasenia"];
$confirmar = $_POST["confirmar_pass"];

if (($email == '') or (!preg_match('[@]',$email))) {
	$_SESSION['errores'] .= '<li>Ingrese una direccion de email valida.</li>';
}

if (($nombre == '') or (!preg_match('/^[A-Za-z\s]+$/',$nombre))) {
	$_SESSION['errores'] .= '<li>Ingrese un nombre valido.</li>';
}

if (($apellido == '') or (!preg_match('/^[A-Za-z\s]+$/',$apellido))) {
	$_SESSION['errores'] .= '<li>Ingrese un apellido valido.</li>';
}

if (($nombreusuario == '') or (strlen($nombreusuario) < 6) or !(preg_match('/^[A-Za-z0-9\s]+$/',$nombreusuario))) {
	$_SESSION['errores'] .= '<li>El nombre de usuario debe tener al menos 6 caracteres alfanumericos.</li>';
}

if ($_FILES['foto_de_perfil']['name'] == ''){
	$_SESSION['errores'] .= '<li>Ingrese una foto de perfil.</li>';
}

$error_password = '';
if (!validar_password($contrasenia, $error_password)){
	$_SESSION['errores'] .= $error_password;
} else {
	if ($contrasenia != $confirmar){
		$_SESSION['errores'] .= '<li>Las contraseñas deben coincidir.</li>';
	}
}

if ($_SESSION['errores']){
	header('Location: registrarse.php');
	exit;
}

// consulta para saber si el nombre de usuario ya existe en la db
$sql = "SELECT id, nombreusuario FROM usuarios WHERE nombreusuario = '$nombreusuario'";
$resultado = $conexion->query($sql);
$usuario = $resultado->fetch_assoc();

if($usuario != null) {
	$_SESSION['errores'] .= '<li>El nombre de usuario ya existe.</li>';
	header('Location: registrarse.php'); 
} else {
	$arreglo = explode('/', $_FILES['foto_de_perfil']['type']);
	$foto_contenido = file_get_contents($_FILES['foto_de_perfil']['tmp_name']);
	$foto_contenido = addslashes($foto_contenido); 

	$sql = "INSERT INTO usuarios (apellido, nombre, email, nombreusuario, contrasenia, foto_contenido, foto_tipo) 
					VALUES('$apellido', '$nombre', '$email', '$nombreusuario', '$contrasenia', '$foto_contenido', '$arreglo[1]')";

	try {
		$resultado = $conexion->query($sql);
		$_SESSION['exito'] = '<li>Te registraste con exito.</li>';
		header('Location: index.php');
	} catch(Exception $e) {
		$_SESSION['errores'] = '<li>Error de la base de datos.</li>';
		header('Location: registrarse.php');
	}
	
	$_SESSION['id'] = mysqli_insert_id($conexion);
}