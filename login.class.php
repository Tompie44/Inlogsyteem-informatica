<?php 
class LoginUser{
	// class properties
	private $username;
	private $password;
	public $error;
	public $success;
	private $storage = "data.json";
	private $stored_users;

	// class methods
	public function __construct($username, $password){
		$this->username = $username;
		$this->password = $password;
		$servername = "localhost";
		$usernameDB = "root";
		$passwordDB = "usbw";
		$DBname = 'wachtwoorden';
		$conn = new mysqli($servername, $usernameDB, $passwordDB, $DBname);
		// Check connection
		if ($conn->connect_error) {
  			die("Connection failed: " . $conn->connect_error);
        }
        $sqli = "SELECT gebruikersnaam FROM gegevens WHERE gebruikersnaam ='".$username."'";
		$this->username = $conn->query($sqli);
		$sql = "SELECT wachtwoord FROM gegevens WHERE wachtwoord ='".$password."'";
		$this->password = $conn->query($sql);
		$this->login();
	}


	private function login(){
		foreach ($this->stored_users as $user) {
			if($user['username'] == $this->username){
				if($this->password == $user['password']){
					session_start();
					$_SESSION['user'] = $this->username;
					header("location: account.php"); exit();
				}
			}
		}
		return $this->error = "Wrong username or password";
	}

}