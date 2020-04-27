<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="css/estilos.css">
	<link rel="stylesheet" href="fontawesome/css/all.min.css">
	<title>The Wall</title>
</head>
<body>
<?php if($autenticador->estaLogeado()){ ?>
	<div class="header">
		<div class="container">
			<ul class="nav">
				<li>
					<a href="index.php" class="logo">The Wall</a>
				</li>
				<li>
					<form action="buscar.php" onsubmit="return validarBusqueda(this);">
						<input placeholder="Buscar... " type="text" class="buscar" name="busqueda">
					</form>
				</li>
				<li>
					<a href="muro.php">Perfil</a>
				</li>
				<li>
					<a href="cambiarcontrasenia.php">Cambiar Pass</a>
				</li>
				<li>
					<a href="editar.php">Editar Perfil</a>
				</li>
				<li>
					<a href="cerrarsesion.php">Cerrar Sesion</a>
				</li>
			</ul>
		</div>
	</div>
<?php } ?>