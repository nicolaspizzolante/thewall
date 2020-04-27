<?php
	include 'autenticador.php';
	$autenticador = new autenticador();
	
	if (!$autenticador->estaLogeado()) {
		header('Location: login.php');
		exit;
	}

	include 'views/header.php';
?>

<div class="container">
	<!-- Datos del usuario loggeado -->
	<div class="datos">
		<div class="">
			<div class="nombre"><?php echo $_SESSION['usuario']['nombre']; ?></div>
			<div class="apellido"><?php echo $_SESSION['usuario']['apellido']; ?></div>
			<div class="usuario">@<?php echo $_SESSION['usuario']['nombreusuario']; ?></div>
		</div>
		<img class="foto_de_perfil" src="mostrarimagen.php?id=<?php echo $_SESSION['usuario']['id']; ?>">
	</div>
	
	<!-- Formulario para crear mensaje -->
	<form action="crearMensaje.php" method="post" class="publicar" onsubmit="return validarPublicacion(this);" enctype="multipart/form-data">
		<textarea id="enter" class="input_publicar" name="texto" placeholder="Escribe algo..."></textarea>
		<input type="hidden" name="ubicacion" value="muro.php">
		<div class="botones_publicar">
			<input type="submit" value="Publicar" class="boton_publicar"></input>
			<input type="file" value="Adjuntar Imagen" name="imagen" class="custom-file-input"></input>
		</div>
	</form>

	<?php if (isset($_SESSION['errores'])): ?>
		<ul id="errores" class="errores_mensaje" style="display:block;">
			<?php 
				echo $_SESSION['errores']; 
				unset($_SESSION['errores']);
			?>
		</ul>
	<?php endif ?>

	<?php
		$conexion = conectar();

		// Logica para la paginacion
		$pagina = isset($_GET['p']) ? (int)$_GET['p'] : 1; 
		$mensajesPorPagina = 10;

		$inicio = ($pagina > 1) ? ($pagina * $mensajesPorPagina - $mensajesPorPagina) : 0;

		$id = $_SESSION['usuario']['id'];
		$sql = "SELECT * FROM mensaje WHERE usuarios_id = '$id'";
		$total_mensajes = $conexion->query($sql);
		$total_mensajes = $total_mensajes->num_rows;
		
		$numero_paginas = ceil($total_mensajes / $mensajesPorPagina);
	?>

	<!-- Paginacion -->
	<div class="paginacion">
		<ul>
			<?php if($pagina == 1){ ?>
				<li class="disabled">&laquo;</li>
			<?php } else { ?>
				<a href="muro.php?p=<?php echo $pagina - 1; ?>"><li>&laquo;</li></a>
			<?php } ?>

			<?php for($i = 1; $i<=$numero_paginas; $i++){ ?>
				<?php if ($i == $pagina){ ?>
					<a href="muro.php?p=<?php echo $i; ?>"><li class="actual"><?php echo $i; ?></li></a>
				<?php } else { ?>
					<a href="muro.php?p=<?php echo $i; ?>"><li><?php echo $i; ?></li></a>
				<?php } ?>
			<?php } ?>
			
			<?php if($pagina == $numero_paginas){ ?>
				<li class="disabled">&raquo;</li>
			<?php } else { ?>
				<a href="muro.php?p=<?php echo $pagina + 1; ?>"><li>&raquo;</li></a>
			<?php } ?>
		</ul>
	</div>
	
	<?php
		$sql = "SELECT * FROM siguiendo WHERE usuarios_id = '$id'";
		$resultado = $conexion->query($sql);
	?>
	
	<div class="main_muro">	
		
		<?php if ($resultado->num_rows > 0) { ?>
		<!-- Lista con perfiles que sigue el usuario loggeado -->
			<div class="perfiles_siguiendo">
				<h3>Perfiles que sigues:</h3>
				
				<?php while ($fila = $resultado->fetch_assoc()) { ?>
					<?php  
						$usuarioseguido_id = $fila['usuarioseguido_id'];
						$sql = "SELECT id, nombre, apellido, nombreusuario 
										FROM usuarios 
										WHERE id = '$usuarioseguido_id'";
						$usuarios_que_sigo = $conexion->query($sql);
					?>
					
					<div class="perfil_siguiendo">
						<?php $user = $usuarios_que_sigo->fetch_assoc(); ?>
										
						<div class="foto_siguiendo">
							<a href="perfil.php?id=<?php echo $user['id']; ?>">
								<img src="mostrarimagen.php?id=<?php echo $user['id']; ?>">
							</a>
						</div>
						
						<a href="perfil.php?id=<?php echo $user['id']; ?>">
							<div class="usuario_siguiendo">
								<div class="nombre_siguiendo"><?php echo $user['nombre']; ?></div>
								<div class="apellido_siguiendo"><?php echo $user['apellido']; ?></div>
								<div class="usuario_siguiendo">@<?php echo $user['nombreusuario']; ?></div>
							</div>
						</a>				
						
						<a href="dejardeseguir.php?usuarioseguido_id=<?php echo $user['id']; ?>&sitio=muro.php">
							<button class="boton_seguir_perfil siguiendo">Dejar de seguir</button>
						</a>
					</div>
				<?php } ?>
			</div>
		<?php } ?>

		<!-- Publicaciones del usuario loggeado -->
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

			<?php while ($mensaje = $mensajes->fetch_assoc()){ ?>
				<div class="publicacion">
					<div class="foto_perfil_publicacion">
						<a href="perfil.php"><img src="mostrarImagen.php?id=<?php echo $id; ?>"></a>
					</div>
					
					<div class="publicacion_contenido" style="width:100%;">
						<a href="perfil.php" class="usuario">@<?php echo $_SESSION['usuario']['nombreusuario']; ?></a>
						<p><?php echo $mensaje['texto']; ?></p>
						
						<!-- Si el mensaje tiene imagen la muestro -->
						<?php if ($mensaje['imagen_contenido']){ ?>
							<img src="mostrarimagen.php?id=<?php echo $mensaje['id']; ?>&sitio=mensaje" class="imagen_publicacion">	
						<?php } ?>

						<!-- Fecha, hora y likes -->
						<div class="info_publicacion">
							<span class="fecha"><?php echo $mensaje['fechayhora'] ?></span>

							<?php
								$mensaje_id = $mensaje['id'];
								$sql = "SELECT * FROM me_gusta 
												WHERE usuarios_id = '$id' 
												AND mensaje_id = $mensaje_id";
								$like = $conexion->query($sql);
							?>
							
							<!-- Calculo la cantidad de likes -->
							<?php
								$total_likes = $conexion->query("SELECT * FROM me_gusta WHERE mensaje_id = '$mensaje_id'");
								$cant_likes = $total_likes->num_rows;
							?>
												
							<!-- Se muestra el boton que corresponde si dio like o no -->
							<?php if ($like->fetch_assoc() != null) { ?>
								<a href="dislike.php?mensaje_id=<?php echo $mensaje['id']; ?>&
									sitio=muro.php">
									<button class="boton_me_gusta">
										<i class="fas fa-thumbs-up likeado"></i>
									</button>
								</a>
								<span class="likeado cant_likes">
									<?php echo $cant_likes; ?>
								</span>
							<?php } else { ?>
								<a href="like.php?mensaje_id=<?php echo $mensaje['id']; ?>&
									sitio=muro.php">
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
					
					<!-- Boton para eliminar mensaje -->
					<div class="eliminar">
						<a href="eliminarmensaje.php?id=<?php echo $mensaje['id']; ?>&sitio=muro.php"><button><i class="fas fa-times"></i></button></a>
					</div>
				
				</div>
			<?php } ?>
		</div>
	</div>
</div>

<?php include 'views/footer.php'; ?>