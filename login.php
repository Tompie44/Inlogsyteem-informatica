<?php 
error_reporting(E_ALL);
ini_set('display_errors', '1');
ob_start();
if(isset($_POST['submit'])){
    $servername = "localhost";
    $usernameDB = "root";
    $passwordDB = "usbw";
    $DBname = 'wachtwoorden';
    $conn = new mysqli($servername, $usernameDB, $passwordDB, $DBname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $user = $_POST['username'];
    $pass = $_POST['password'];
    $stmt = $conn->prepare("SELECT wachtwoord FROM gegevens WHERE gebruikersnaam = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();
    if (!$result) {
        printf("Error: %s\n", $conn->error);
        exit();
    }
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $stored_hash = $row['wachtwoord'];
        if (password_verify($pass, $stored_hash)) {
            // Password is correct
            session_start();
            $_SESSION['user'] = $user;
            header("Location: account.php");
            ob_end_flush();
            exit();
        } else {
            // Password is incorrect
            echo "Incorrect password";
        }
    } else {
        // Username not found
        echo "Username not found";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Inloggen</title>
</head>
<body>
    <form action="" method="post" enctype="multipart/form-data" autocomplete="off">
        <div class="header">
            <h2>Inloggen</h2>
            <a href="index.php">Registreren</a>  
        </div>
        <h4>Beide velden zijn <span>vereist</span></h4>

        <label>Gebruikersnaam</label>
        <input type="text" name="username">

        <label>Wachtwoord</label>
        <input type="password" name="password">

        <button type="submit" name="submit">Inloggen</button>

        <p class="error"><?php echo @$error ?></p>
        <p class="success"><?php echo @$success ?></p>
    </form>
</body>
</html>

