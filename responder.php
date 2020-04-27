<?php
include 'db.php';

session_start();

$conexion = conectar();

$texto = trim($_POST['texto']);
$usuarios_id = $_POST['usuarios_id'];
$mensaje_id = $_POST['mensaje_id'];

$sql = "INSERT INTO respuesta_mensaje (texto, usuarios_id, fechayhora, mensaje_id) 
						VALUES ('$texto', '$usuarios_id', now(), $mensaje_id)";
$resultado = $conexion->query($sql);

header('Location: index.php');