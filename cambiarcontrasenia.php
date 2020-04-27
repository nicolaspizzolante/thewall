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
	<h1>Cambiar contraseña:</h1>

	<form action="validarcambio.php" method="post" onsubmit="return validarCambio(this);">
		<input type="hidden" name="id" value="<?php echo $_SESSION['usuario']['id']; ?>">
		<div class="input">
			<input type="password" name="pass" placeholder="Contraseña actual">
		</div>

		<div class="input">
			<input type="password" name="nueva_pass" placeholder="Nueva contraseña">
		</div>

		<div class="input">
			<input type="password" name="repetir_pass" placeholder="Repetir contraseña">
		</div>

		<div class="input">
			<input type="submit">
		</div>

			
	</form>
	
	<ul id="errores" class="asd" style="display: none"></ul>

	<?php if(isset($_SESSION['exito'])){ ?>
		<ul id="exito" class="asd">
				<?php 
					echo $_SESSION['exito']; 
					unset($_SESSION['exito']);
				?>
		</ul>
	<?php } ?>
	
	<?php if(isset($_SESSION['errores'])){ ?>
		<ul id="errores" class="asd">
				<?php 
					echo $_SESSION['errores']; 
					unset($_SESSION['errores']);
				?>
		</ul>
	<?php } ?>


</div>

<?php include 'views/footer.php'; ?>