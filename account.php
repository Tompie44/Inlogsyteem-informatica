<?php
session_start();
if(!isset($_SESSION['user'])){
    header("location: login.php");
    exit();
}

if(isset($_GET['logout'])){
    unset($_SESSION['user']);
    header("location: login.php");
    exit();
}

if(isset($_GET['storten'])){
    header("location: storten.php");
    exit();
}

if(isset($_GET['opnemen'])){
    header("location: opnemen.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "usbw";
$dbname = "wachtwoorden";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user = $_SESSION['user'];
$result = $conn->query("SELECT geld FROM geld WHERE gebruikersnaam ='".$user."'");
$row = $result->fetch_assoc();
$gelduser = $row['geld'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>User account</title>
</head>
<body>

    <div class="content">
		<header>
    		<div>
        		<h2>Welkom <?php echo $_SESSION['user']; ?></h2>
        		<h3>Rekeningsaldo: <?php echo $gelduser; ?></h3>
    		</div>
    		<div>
        		<a href="?logout">Log uit</a>  
    		</div>
		</header>  
		<main>
  			<a href="?opnemen" class="button-opnemen">opnemen</a>
  			<a href="?storten" class="button-storten">storten</a>
		</main>

    </div>
</body>
</html>


