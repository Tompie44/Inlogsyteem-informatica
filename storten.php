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

// Handle deposit form submission
if (isset($_POST['deposit_amount'])) {
    $deposit_amount = floatval($_POST['deposit_amount']);
    
    // Make sure deposit amount is greater than zero
    if ($deposit_amount <= 0) {
        $error_message = "Deposit amount must be greater than zero.";
    } else {
        // Update user's account balance
        $result = $conn->query("UPDATE geld SET geld = geld + ".$deposit_amount." WHERE gebruikersnaam = '".$user."'");
        if ($result === TRUE) {
            $success_message = "Storten van ".$deposit_amount." gelukt.";
        } else {
            $error_message = "Error bij het toevoegen van geld aan uw rekening.";
        }
    }
}

// Get user's current account balance
$result = $conn->query("SELECT geld FROM geld WHERE gebruikersnaam ='".$user."'");
$gelduser = $result->fetch_assoc()['geld'];
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
        <h2>Welkom<?php echo $_SESSION['user']; ?></h2>
        <h3>Rekeningsaldo: <?php echo $gelduser; ?></h3>
    </div>
    <div>
        <a href="?logout">Log uit</a>  
    </div>
</header>
    
    <!-- Deposit form -->
    <h3>Geld storten:</h3>
    <?php if (isset($error_message)): ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php endif; ?>
    <?php if (isset($success_message)): ?>
        <p style="color: green;"><?php echo $success_message; ?></p>
    <?php endif; ?>
    <form method="post">
        <label for="deposit_amount">Hoeveel geld wil je storten:</label>
        <input type="number" id="deposit_amount" name="deposit_amount" step="0.01" required>
        <button type="submit">Storten</button>
    </form>
</div>

</body>
</html> 