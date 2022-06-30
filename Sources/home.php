<div class="content homecon">

<?

var_dump($_SESSION);

if ( isset($_SESSION['error']) )
{
	?>
	<div class="alert alert-danger"><?=$_SESSION['error'];?></div>
	<?
	unset($_SESSION['error']);
}
?>
	<div class="col-xs-6" id="login">
		<h2>Iniciar sesión</h2>
		<form action="actions/login.php" method="POST" id="login">
			<b id="infoul" style="color: red;"></b>
			<label><b>Usuario (Nombre_Apellido):</b></label>
			<input type="text" name="user" class="form-control" size="50">
			<label><b>Contraseña:</b></label>
			<input type="password" name="pswd" class="form-control" size="50">
			<button class="btn btn-default">Iniciar Sesión</button>
		</form>
	</div>

	<div class="col-xs-6" id="signup">
		<h2>Registro</h2>
		<form action="actions/login.php" method="POST">
			<b id="infour" style="color: red;"></b>
			<label><b>Usuario (Nombre_Apellido):</b></label>
			<input type="text" name="user" class="form-control" size="50">
			<label><b>Contraseña:</b></label>
			<input type="password" name="pswd" class="form-control" size="50">
			<b id="infocr" style="color: red;"></b>
			<label><b>Correo electronico:</b></label>
			<input type="email" name="mail" class="form-control" size="50">
			<label><b>Genero:</b></label><br>
			<label style="float: right">
				<input type="radio" name="gen" value="wom">
				Mujer
			</label>
			<label>
				<input type="radio" name="gen" value="man">
				Hombre
			</label><br><br>
			<button class="btn btn-default">Registrarse</button>
		</form>
	</div>

	<? if ( isset($_GET['jq']) )
	{
	?>
	<div id="ishome">
	</div>
	<?
	}
	?>
<script type="text/javascript">
var allinputs = $("input");
var userlogin = $("#login input[name='user']");
var pswdlogin = $("#login input[name='pswd']");
var userreg = $("#signup input[name='user']");
var pwdreg = $("#signup input[name='pswd']");
var mailreg = $("#signup input[name='mail']");
var genreg = $("#signup input[name='gen']");

var allowsubmit;

$(userlogin).on("input", function(){
	$.post("actions/validateuser.php", { user: userlogin.val() }, function(data) {
		if ( data == 'true' )
		{
			$("#infoul").empty();
			allowsubmit = true;
			//console.log('test');
		}
		else
		{
			$("#infoul").empty().append("Usuario no valido o existente.<br>");
			allowsubmit = false;
		}
	});
});

$(userlogin).blur(function(){
	$.post("actions/validateuser.php", { user: userlogin.val() }, function(data) {
		if ( data == 'true' )
		{
			$("#infoul").empty();
			allowsubmit = true;
		}
		else
		{
			$("#infoul").empty().append("Usuario no valido o existente.<br>");
			allowsubmit = false;
		}
	});
});

$(allinputs).blur(function()
{
    if( !$(this).val() ) {
          $(this).css('background', '#F5A9A9');
    }

    console.log(allowsubmit);
});

</script>
</div>