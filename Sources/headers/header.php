<header <? if ( isset($_SESSION['loginid']) ) { echo " id=\"userheader\""; } ?>>
	<? 


	$s1 = explode('/', $_SERVER['PHP_SELF']);

	if ( $s1[2] == 'home.php' && !isset($_SESSION['loginid']) )
	{
	?>
		<center>
			<?=$config['context']['logo'];?>
		</center>
	<?
	}
	else
	{
	?>
	<div align="left">
		<?=$config['context']['logo'];?>
	</div>
	<nav>
		<a href="#">Inicio</a>
		<a href="#">Estadisticas</a>
		<a href="#">Actualizaciones</a>
	</nav>
	<?
	}
	?>
</header>