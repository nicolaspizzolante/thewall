<?php  
include 'db.php';

session_start();

$conexion = conectar();

$texto = trim($_POST['texto']);
$ubicacion = $_POST['ubicacion'];
$id = $_SESSION['usuario']['id'];

if($texto){

	if(strlen($texto) > 140){
		$_SESSION['errores'] = '<li>La publicacion no puede superar los 140 caracteres.</li>';
		header('Location: '.$ubicacion);
		exit;
	}
	
	if($_FILES['imagen']['name'] == ''){
		$sql = "INSERT INTO mensaje (texto, usuarios_id, fechayhora) 
						VALUES ('$texto', '$id', now())";	
	} else {
		$arreglo = explode('/', $_FILES['imagen']['type']);
		$imagen_contenido = file_get_contents($_FILES['imagen']['tmp_name']);
		$imagen_contenido = addslashes($imagen_contenido);

		$sql = "INSERT INTO mensaje (texto, usuarios_id, imagen_contenido, imagen_tipo, fechayhora) 
						VALUES ('$texto', '$id', '$imagen_contenido', '$arreglo[1]', now())";
	}
	
	try {
		$resultado = $conexion->query($sql);
		header('Location: '.$ubicacion);
	} catch(Exception $e) {
		$_SESSION['errores'] = '<li>Error de la base de datos.</li>';
		header('Location: '.$ubicacion);
	}

} else {
	$_SESSION['errores'] = '<li>La publicacion debe tener al menos un caracter.</li>';
	header('Location: '.$ubicacion);
}
