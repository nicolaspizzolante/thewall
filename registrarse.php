<?php
	include 'autenticador.php';
	$autenticador = new autenticador();
	
	if ($autenticador->estaLogeado()) {
		header('Location: index.php');
		exit;
	} 

	include 'views/header.php'; 
?>

<div class="formulario_inicio">

	<h1>Registrarse:</h1>
	<form action="validarRegistro.php" onsubmit="return validarRegistro(this);" method="post"  enctype="multipart/form-data">
		<div class="input">
			<input type="text" name="email" placeholder="Email">
		</div>
		
		<div class="input">
			<input type="text" name="nombre" placeholder="Nombre">
		</div>
		
		<div class="input">
			<input type="text" name="apellido" placeholder="Apellido">
		</div>
		
		<div class="input">
			<input type="file" name="foto_de_perfil" placeholder="Foto de perfil">
		</div>

		<div class="input">
			<input type="text" name="nombreusuario" placeholder="Usuario">
		</div>

		<div class="input">
			<input type="password" name="contrasenia" placeholder="Contraseña">
		</div>

		<div class="input">
			<input type="password" name="confirmar_pass" placeholder="Confirmar">
		</div>

		<div class="input">
			<input type="submit">
		</div>
	</form>
	

	<ul id="errores" style="display:none"></ul>

	<?php if (isset($_SESSION['errores'])): ?>
		<ul id="errores" style="display:block;">
			<?php 
				echo $_SESSION['errores']; 
				unset($_SESSION['errores']);
			?>
		</ul>
	<?php endif ?>

</div>
	
<?php include 'views/footer.php'; ?> 