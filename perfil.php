<?php
	include 'autenticador.php';
	$autenticador = new autenticador();
	
	if (!$autenticador->estaLogeado()) {
		header('Location: login.php');
		exit;
	}

	include 'views/header.php';

	$id = isset($_GET['id']) ? $_GET['id'] : $_SESSION['usuario']['id']; //id del usuario

	if ($id == $_SESSION['usuario']['id']){
		header('Location: muro.php');
	}

	$conexion = conectar();
	$sql = "SELECT nombre, apellido, nombreusuario FROM usuarios WHERE id = '$id'";
	$resultado = $conexion->query($sql);

	$user = $resultado->fetch_assoc();
?>

	<div class="container">
		<div class="datos">
			<div class="">	
				<div class="nombre"><?php echo $user['nombre']; ?></div>
				<div class="apellido"><?php echo $user['apellido']; ?></div>
				<div class="usuario">@<?php echo $user['nombreusuario']; ?></div>
			</div>
		
		<?php
			$id_sesion = $_SESSION['usuario']['id'];  
			$sql = "SELECT * FROM siguiendo 
							WHERE usuarios_id = '$id_sesion'
							AND usuarioseguido_id = '$id'";
			$resultado2 = $conexion->query($sql);
		?>

		<?php if ($resultado2->fetch_assoc() != null){ ?>
			<a href="dejardeseguir.php?usuarioseguido_id=<?php echo $id; ?>&sitio=perfil.php?id=<?php echo $id; ?>" class="boton_seguir seguido">
				<i class="fas fa-times cruz"></i>Dejar de seguir
			</a>
		<?php } else { ?>
			<a href="seguir.php?usuarioseguido_id=<?php echo $id; ?>&sitio=perfil.php?id=<?php echo $id; ?>" class="boton_seguir">
				<i class="fas fa-check tilde"></i>Seguir
			</a>
		<?php } ?>
		</div>
		
		<?php 
			$pagina = isset($_GET['p']) ? (int)$_GET['p'] : 1; 
			$mensajesPorPagina = 10;

			$inicio = ($pagina > 1) ? ($pagina * $mensajesPorPagina - $mensajesPorPagina) : 0;
			
			$sql = "SELECT * FROM mensaje WHERE usuarios_id = '$id'";
			$total_mensajes = $conexion->query($sql);
			$total_mensajes = $total_mensajes->num_rows;
			
			$numero_paginas = ceil($total_mensajes / $mensajesPorPagina);
		?>

		<div class="paginacion">
			<ul>
				<?php if($pagina == 1){ ?>
					<li class="disabled">&laquo;</li>
				<?php } else { ?>
					<a href="perfil.php?id=<?php echo $id; ?>&p=<?php echo $pagina - 1; ?>"><li>&laquo;</li></a>
				<?php } ?>

				<?php for($i = 1; $i<=$numero_paginas; $i++){ ?>
					<?php if ($i == $pagina){ ?>
						<a href="perfil.php?id=<?php echo $id; ?>&p=<?php echo $i; ?>"><li class="actual"><?php echo $i; ?></li></a>
					<?php } else { ?>
						<a href="perfil.php?id=<?php echo $id; ?>&p=<?php echo $i; ?>"><li><?php echo $i; ?></li></a>
					<?php } ?>
				<?php } ?>
				
				<?php if($pagina == $numero_paginas){ ?>
					<li class="disabled">&raquo;</li>
				<?php } else { ?>
					<a href="perfil.php?id=<?php echo $id; ?>&p=<?php echo $pagina + 1; ?>"><li>&raquo;</li></a>
				<?php } ?>
			</ul>
		</div>

		<div class="publicaciones">
			<?php
				$sql = "SELECT * FROM mensaje 
								WHERE usuarios_id = '$id' 
								ORDER BY fechayhora DESC 
								LIMIT $inicio, $mensajesPorPagina";

				$mensajes = $conexion->query($sql);
				$msg = $mensajes->num_rows;

				if($msg == 0){
					$_SESSION['usuario']['errores'] = 'No hay publicaciones';
				}
			?>
			
			<h3>
				<?php if(isset($_SESSION['usuario']['errores'])){ ?>
					<h2 style="text-align:center; color:white;">
						<?php 
							echo $_SESSION['usuario']['errores']; 
							$_SESSION['usuario']['errores'] = '';
						?>
					</h2>
				<?php } ?>
			</h3>
				
			<?php while ($mensaje = $mensajes->fetch_assoc()): ?>
				<div class="publicacion">
					<div class="foto_perfil_publicacion">
						<a href="perfil.php?id=<?php echo $id; ?>"><img src="mostrarImagen.php?id=<?php echo $id; ?>"></a>
					</div>
					
					<div class="publicacion_contenido" style="width:100%;">
						<a href="perfil.php?id=<?php echo $id; ?>" class="usuario">
							<?php
								$usuarios_id = $mensaje['usuarios_id'];
								$sql = "SELECT nombreusuario FROM usuarios WHERE id = '$usuarios_id'";
								$nombre = $conexion->query($sql)->fetch_assoc()['nombreusuario'];

								echo '@'.$nombre;
							?>
						</a>
						<p><?php echo $mensaje['texto']; ?></p>
						
						<?php if ($mensaje['imagen_contenido']){ ?>
							<img src="mostrarimagen.php?id=<?php echo $mensaje['id']; ?>&sitio=mensaje" class="imagen_publicacion">	
						<?php } ?>

						<div class="info_publicacion">
							<span class="fecha"><?php echo $mensaje['fechayhora'] ?></span>

							<?php
								$mensaje_id = $mensaje['id'];
								$id_sesion = $_SESSION['usuario']['id'];
								$sql = "SELECT * FROM me_gusta 
												WHERE usuarios_id = '$id_sesion' 
												AND mensaje_id = $mensaje_id";
								
								$like = $conexion->query($sql);
							?>

							<?php
								$total_likes = $conexion->query("SELECT * FROM me_gusta WHERE mensaje_id = '$mensaje_id'");
								$cant_likes = $total_likes->num_rows;
							?>

							<?php if ($like->fetch_assoc() != null) { ?>
								<a href="dislike.php?mensaje_id=<?php echo $mensaje['id']; ?>&
									sitio=perfil.php?id=<?php echo $id; ?>">
									<button class="boton_me_gusta">
										<i class="fas fa-thumbs-up likeado"></i>
									</button>
								</a>
								<span class="likeado cant_likes">
									<?php echo $cant_likes; ?>
								</span>
						  <?php } else { ?>
								<a href="like.php?mensaje_id=<?php echo $mensaje['id']; ?>&
									sitio=perfil.php?id=<?php echo $id; ?>">
									<button class="boton_me_gusta">
										<i class="fas fa-thumbs-up"></i>
									</button>
								</a>
								<span class="cant_likes">
									<?php echo $cant_likes; ?>
								</span>
						  <?php } ?>	
						</div>
					</div>
				</div>
			<?php endwhile; ?>
	</div>
</div>

<?php include 'views/footer.php'; ?>