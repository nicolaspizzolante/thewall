<?php
	include 'autenticador.php';
	$autenticador = new autenticador();
	
	if (!$autenticador->estaLogeado()) {
		header('Location: login.php');
		exit;
	}
	
	include 'views/header.php'; 

	if ($_GET['busqueda'] == '') {
		header('Location: buscar.php?busqueda=+');
	}

	$busqueda = $_GET['busqueda'];
	$id = $_SESSION['usuario']['id'];

	$conexion = conectar();
	
	$sql = "SELECT * FROM usuarios 
					WHERE id <> '$id' 
					AND (nombre like '%$busqueda%' or apellido like '%$busqueda%' or nombreusuario like '%$busqueda%')";
	
	$resultado = $conexion->query($sql);
?>
	
<div class="container">
	<h1>Resultados de la busqueda:</h1>
	<?php if ($resultado->num_rows == 0){ ?>
		<h3>No se encontraron resultados para: <?php echo $busqueda; ?></h3>
	<?php } ?>
	
	<div class="resultados">
		<?php while ($fila = $resultado->fetch_assoc()){ ?>
			<?php  
				$usuarioseguido_id = $fila['id'];
				$sql = "SELECT * FROM siguiendo 
								WHERE usuarios_id = '$id' 
								AND usuarioseguido_id = '$usuarioseguido_id'";
				$resultado2 = $conexion->query($sql);
			?>
	
			<div class="resultado">
				<a href="perfil.php?id=<?php echo $usuarioseguido_id; ?>">
					<div class="foto_resultado">
						<img src="mostrarImagen.php?id=<?php echo $fila['id']; ?>">
					</div>
				</a>
	
				<a href="perfil.php?id=<?php echo $usuarioseguido_id; ?>">
					<div class="datos">
						<h2><?php echo $fila['nombre'] . ' ' . $fila['apellido']; ?></h2>
						<p>@<?php echo $fila['nombreusuario'] ?></p>
					</div>
				</a>
				
				<?php if ($resultado2->fetch_assoc() != null) { ?>
					<a href="dejardeseguir.php?usuarioseguido_id=<?php echo $usuarioseguido_id; ?>&busqueda=<?php echo $busqueda; ?>" class="boton_seguir seguido"><i class="fas fa-times cruz"></i>Dejar de seguir</a>
				<?php } else { ?>
					<a href="seguir.php?usuarioseguido_id=<?php echo $usuarioseguido_id; ?>&busqueda=<?php echo $busqueda; ?>" class="boton_seguir"><i class="fas fa-check tilde"></i>Seguir</a>
				<?php } ?>	
				
			</div>
		 <?php } ?>
	</div>
</div>

<?php include 'views/footer.php'; ?>