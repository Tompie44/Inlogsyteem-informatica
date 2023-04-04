<?php 
class RegisterUser{
	// Class properties
	private $username;
	private $raw_password;
	private $encrypted_password;
	public $error;
	public $success;
	private $storage = "data.json";
	private $stored_users;
	private $new_user; // array 
	private $uppercase;
	private $lowercase;
	private $number;
	private $specialChars;
	public function __construct($username, $password){

		$this->username = trim($username ?? '');
		$this->username = filter_var($username, FILTER_SANITIZE_SPECIAL_CHARS);
	
		$this->raw_password = filter_var(trim($password), FILTER_SANITIZE_SPECIAL_CHARS);
		$this->encrypted_password = password_hash($this->raw_password, PASSWORD_BCRYPT);
		$this->uppercase = preg_match('@[A-Z]@', $password);
		$this->lowercase = preg_match('@[a-z]@', $password);
		$this->number    = preg_match('@[0-9]@', $password);
		$this->specialChars = preg_match('@[^\w]@', $password);
	
		$this->new_user = [
			"username" => $this->username,
			"password" => $this->encrypted_password,
		];
	
		$servername = "localhost";
		$usernameDB = "root";
		$passwordDB = "usbw";
		$DBname = 'wachtwoorden';
		$geld = rand(0,5000);
		
	
		// Create connection
		$conn = new mysqli($servername, $usernameDB, $passwordDB, $DBname);
		// Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
	
		if ($this->usernameExists() == false && $this->checkFieldValues() == true && $this->passwordMeetsCriteria() == true) {
			$sql = "INSERT INTO  gegevens(gebruikersnaam, wachtwoord) VALUES ('$this->username', '$this->encrypted_password')";
			$sqlgeld = "INSERT INTO  geld(gebruikersnaam, geld) VALUES ('$username', '$geld')";
            if ($conn->query($sql) === TRUE && $conn->query($sqlgeld) === TRUE) {
                $this->success = "New record created successfully";
			} else {
				$this->error = "Error: " . $sql . "<br>" . $conn->error;
			}
			$conn->close();
            
		} else {
			$this->error = "Error:Zorg ervoor dat je beide velden hebt ingevuld en dat je wachtwoord een Hoofletter, Kleine letter, speciaal teken en 8 tekens heeft.";
			$conn->close();
		}

	}
	private function passwordMeetsCriteria() {
		if (!$this->uppercase || !$this->lowercase || !$this->number || !$this->specialChars) {
			$this->error = 'Wachtwoord moet 8 tekens lang zijn en een hoofdletter, kleine letter en een speciaal teken bevatten.';
			return false;
		} else {
			return true;
		}
	}


	private function checkFieldValues(){
		if(empty($this->username) || empty($this->raw_password)){
			$this->error = "Beide velden zijn vereist.";
			return false;
		}else{
			return true;
		}
	}


	private function usernameExists(){
		foreach($this->stored_users as $user){
			if($this->username == $user['username']){
				$this->error = "Gebruikersnaam bestaat kies een andere.";
				return true;
			}
		}
	}



} // end of class