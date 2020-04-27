<?php 
include 'db.php';

$id = $_GET['id'];
$sitio = isset($_GET['sitio']) ? $_GET['sitio'] : '';

$link = conectar();

// se recupera la información de la imagen
if(!$sitio){
	$sql = "SELECT foto_contenido, foto_tipo FROM usuarios WHERE id = $id"; 
} else {
	$sql = "SELECT imagen_contenido, imagen_tipo FROM mensaje WHERE id = $id";
}	

$result = mysqli_query($link, $sql); 
$row = mysqli_fetch_array($result); 
mysqli_close($link); 

// se imprime la imagen y se le avisa al navegador que lo que se está 
// enviando no es texto, sino que es una imagen de un tipo en particular
if(!$sitio){
	header("Content-type:"  . $row['foto_tipo']);
	echo $row['foto_contenido'];
} else {
	header("Content-type:"  . $row['imagen_tipo']);
	echo $row['imagen_contenido'];
}
