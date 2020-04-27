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
	<form action="crearmensaje.php" method="post" class="publicar" onsubmit="return validarPublicacion(this);"  enctype="multipart/form-data">
		<textarea id="enter" class="input_publicar" name="texto" placeholder="Escribe algo..."></textarea>
		<input type="hidden" name="ubicacion" value="index.php">
		<div class="botones_publicar">
			<input type="submit" value="Publicar" class="boton_publicar"></input>
			<input type="file" value="Adjuntar Imagen" name="imagen" class="custom-file-input"></input>
		</div>
	</form>

	<?php if (isset($_SESSION['errores'])): ?>
		<ul id="errores" class="errores_mensaje">
			<?php 
			  echo $_SESSION['errores']; 
			  unset($_SESSION['errores']);
			?>
		</ul>
	<?php endif ?>

	<?php 	
			$conexion = conectar();
			$sesion_id = $_SESSION['usuario']['id'];
			
			$pagina = isset($_GET['p']) ? (int)$_GET['p'] : 1; 
			$mensajesPorPagina = 10;

			$inicio = ($pagina > 1) ? ($pagina * $mensajesPorPagina - $mensajesPorPagina) : 0;
			
			$sql = "SELECT DISTINCT(m.id) 
							FROM mensaje m
							INNER JOIN siguiendo s 
							INNER JOIN usuarios u
							ON (m.usuarios_id = s.usuarioseguido_id 
							AND u.id = s.usuarioseguido_id) 
							WHERE s.usuarios_id = '$sesion_id' OR m.usuarios_id = '$sesion_id'  
							ORDER BY fechayhora DESC";
			
			$total_mensajes = $conexion->query($sql);
			$cant = $total_mensajes->num_rows;
			$numero_paginas = ceil($cant / $mensajesPorPagina);
		?>

		<div class="paginacion">
			<ul>
				<?php if($pagina == 1){ ?>
					<li class="disabled">&laquo;</li>
				<?php } else { ?>
					<a href="index.php?p=<?php echo $pagina - 1; ?>"><li>&laquo;</li></a>
				<?php } ?>

				<?php for($i = 1; $i<=$numero_paginas; $i++){ ?>
					<?php if ($i == $pagina){ ?>
						<a href="index.php?p=<?php echo $i; ?>"><li class="actual"><?php echo $i; ?></li></a>
					<?php } else { ?>
						<a href="index.php?p=<?php echo $i; ?>"><li><?php echo $i; ?></li></a>
					<?php } ?>
				<?php } ?>
				
				<?php if($pagina == $numero_paginas){ ?>
					<li class="disabled">&raquo;</li>
				<?php } else { ?>
					<a href="index.php?p=<?php echo $pagina + 1; ?>"><li>&raquo;</li></a>
				<?php } ?>
			</ul>
		</div>

	<div class="publicaciones">
		<?php
			if(isset($_GET['p']) && ($_GET['p'] > $numero_paginas)){
				header('Location: index.php');
			}

			$sql = "SELECT DISTINCT(m.id), m.texto, u.nombreusuario, m.fechayhora, m.usuarios_id, m.imagen_tipo 
							FROM mensaje m
							INNER JOIN siguiendo s 
							INNER JOIN usuarios u
							ON (m.usuarios_id = s.usuarioseguido_id 
							AND u.id = s.usuarioseguido_id) 
							WHERE s.usuarios_id = '$sesion_id' OR m.usuarios_id = '$sesion_id'  
							ORDER BY fechayhora DESC
							LIMIT $inicio, $mensajesPorPagina";

			$mensajes = $conexion->query($sql);

			$msg = $mensajes->num_rows;
		?>
		
		<?php while ($mensaje = $mensajes->fetch_assoc()) { ?>
			<div class="publicacion">

				<div class="todo">



					<div class="foto_perfil_publicacion">
						<a href="perfil.php?id=<?php echo $mensaje['usuarios_id']; ?>">
							<img src="mostrarimagen.php?id=<?php echo $mensaje['usuarios_id']; ?>">
						</a>
					</div>			
				
					<div class="publicacion_contenido" style="width:100%;">
						<a href="perfil.php?id=<?php echo $mensaje['usuarios_id']; ?>" class="usuario">
							<?php
								echo '@'.$mensaje['nombreusuario'];
							?>
						</a>
						<p><?php echo $mensaje['texto']; ?></p>
						
						<?php if ($mensaje['imagen_tipo']){ ?>
								<img src="mostrarimagen.php?id=<?php echo $mensaje['id']; ?>&sitio=mensaje" class="imagen_publicacion">
						<?php } ?>

						<div class="info_publicacion">
							<span class="fecha"><?php echo $mensaje['fechayhora']; ?></span>
							
							<?php
									$mensaje_id = $mensaje['id'];
									$sql = "SELECT * FROM me_gusta 
													WHERE usuarios_id = '$sesion_id' 
													AND mensaje_id = $mensaje_id";
									$like = $conexion->query($sql); // si le diste like o no
								?>
								
								<?php
									$total_likes = $conexion->query("SELECT * FROM me_gusta WHERE mensaje_id = '$mensaje_id'");
									$cant_likes = $total_likes->num_rows;
								?>
								
								<?php if ($like->fetch_assoc() != null) { ?>
									<a href="dislike.php?mensaje_id=<?php echo $mensaje['id']; ?>&
										sitio=index.php">
										<button class="boton_me_gusta">
											<i class="fas fa-thumbs-up likeado"></i>
										</button>
									</a>
									<span class="likeado cant_likes">
										<?php echo $cant_likes; ?>
									</span>
							  <?php } else { ?>
									<a href="like.php?mensaje_id=<?php echo $mensaje['id']; ?>&
										sitio=index.php">
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
					
					<?php 
						$id = $mensaje['usuarios_id'];
						if ($sesion_id == $id){ ?>
								<a class="eliminar" href="eliminarmensaje.php?id=<?php echo $mensaje['id']; ?>&sitio=index.php"><button><i class="fas fa-times"></i></button></a>
					<?php } ?>

					</div>

				<!-- COLOQUIO -->
				<div class="respuestas">
					
					<!-- muestro el formulario cuando corresponda -->
					<?php if ($sesion_id != $id){ ?>
						<form method="post" action="responder.php" class="responder">
							<input type="hidden" name="mensaje_id" value="<?php echo $mensaje_id; ?>">
							<input type="hidden" name="usuarios_id" value="<?php echo $sesion_id; ?>">
							<textarea name="texto"></textarea>
							<input type="submit">
						</form>
					<?php } ?>
					
					<!-- Consulta para traerme las respuestas respuestas -->
					<?php	
						$sql = "SELECT * FROM respuesta_mensaje WHERE mensaje_id = $mensaje_id";
						$resultado = $conexion->query($sql);
					?>

					<?php if($resultado->num_rows > 0){ ?> 
							<?php while($respuesta = $resultado->fetch_assoc()){	?>
								
								<?php
									// me traigo el nombre de usuario de la respuesta
									$usuario_respuesta_id = $respuesta['usuarios_id'];
									$sql = "SELECT nombreusuario FROM usuarios WHERE id = $usuario_respuesta_id";
									$resultado2 = $conexion->query($sql);
									$nombre_usuario_respuesta = $resultado2->fetch_assoc();
								?>		
								<div class="respuesta">
									<img class="imagen_respuesta" src="mostrarimagen.php?id=<?php echo $usuario_respuesta_id; ?>">
									
									<div class="respuesta_contenido">
										<div class="nombre_respuesta">@<?php echo $nombre_usuario_respuesta['nombreusuario']; ?></div>
										<div class="texto_respuesta"><?php echo $respuesta['texto']; ?></div>
										<div class="hora_respuesta"><?php echo $respuesta['fechayhora']; ?></div>
									</div>
								</div>
							<?php	} ?> 
					<?php } ?>

					
				</div>
				
			</div>
		<?php } ?>
	</div>
</div>

<?php include 'views/footer.php'; ?>