<?php echo phpversion();
?>
<?php require("register.class.php") ?>
<?php
	if(isset($_POST['submit'])){
		$user = new RegisterUser($_POST['username'], $_POST['password']);
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	 <link rel="stylesheet" href="styles.css">
	<title>Registeren</title>
</head>
<body>

	<form action="" method="post" enctype="multipart/form-data" autocomplete="off">
		<div class="header">
    		<h2>Registeren</h2>
    		<a href="login.php">Inloggen</a>
		</div>
		<h4>Beide velden zijn <span>vereist</span></h4>

		<label>gebruikersnaam</label>
		<input type="text" name="username">

		<label>Wachtwoord</label>
		<input type="text" name="password">

		<button type="submit" name="submit">Registeren</button>

		<p class="error"><?php echo @$user->error ?></p>
		<p class="success"><?php echo @$user->success ?></p>
	</form>
	<button onclick="document.location='login.php'">HTML Tutorial</button> 

</body>
</html>