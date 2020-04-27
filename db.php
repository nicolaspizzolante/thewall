<?php  

function conectar(){
	$conexion = new mysqli('localhost', 'root', '', 'trabajo');
	if($conexion->connect_errno){
		die('Error de conexion a la db.');
	}
	return $conexion;
}
