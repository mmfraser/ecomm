<?php
require_once('App.php');

class Member {
	private $memberId;
  	public $forename;
  	public $surname;
  	public $password;
	public $emailAddress;
	public $addressLine1;
	public $addressLine2;
	public $town;
	public $postcode;
	public $isActive;
	public $isAdmin;
	private $oldPassword;
  	public $isLoaded;

	function __construct($forename = "", $surname = "", $password = "", $emailAddress = "", $addressLine1 = "", $addressLine2 = "", $town = "", $postcode = "", $isActive = 1, $isAdmin = 0) {
		$this->conn = App::getDB();
		$this->forename = $forename;
		$this->surname = $surname;
		$this->password = $password;
		$this->emailAddress = $emailAddress;
		$this->addressLine1 = $addressLine1;
		$this->addressLine2 = $addressLine2;
		$this->town = $town;
		$this->postcode = $postcode;
		$this->isActive = $isActive;
		$this->isAdmin = $isAdmin;
	}

	/*	This function gets the object with data given the userId.
	*/
	public function populateId($memberId){
		$sql = "SELECT * FROM members WHERE MemberID = '".mysql_real_escape_string($memberId)."'";
		$row = $this->conn->getDataRow($sql);
		if($row == null)
			return false;
		$this->getRow($row);
	}

	/*	This function get the object with data given the username.
	*/
	public function populateEmail($emailAddress){
		$sql = "SELECT * FROM members WHERE EmailAddress = '".mysql_real_escape_string($emailAddress)."'";
		$row = $this->conn->getDataRow($sql);
		if($row == null)
			return false;
		$this->getRow($row);
	}

	/*	This function populates the object with data given a datarow.
	*/
	public function getRow($row){
		$this->memberId = $row['MemberId'];
		$this->forename = $row['Forename'];
		$this->surname = $row['Surname'];
		$this->password = $row['Password'];
		$this->emailAddress = $row['EmailAddress'];
		$this->addressLine1 = $row['AddressLine1'];
		$this->addressLine2 = $row['AddressLine2'];
		$this->town = $row['Town'];
		$this->postcode = $row['Postcode'];
		$this->isActive = $row['IsActive'];
		$this->isAdmin = $row['IsAdmin'];

		$this->isLoaded = true;
	}

	/*	This function checks that the username is not in use before adding a new user.
	*/
	private function validEmail() {
		if($this->conn->getNumResults("SELECT EmailAddress FROM members WHERE EmailAddress = '".mysql_real_escape_string($this->emailAddress)."'") == 0)
			return true;
		return false;
	}

	/* 	This function allows the object to be saved back to the database, whether it is a new object or 
		an object being updated.
	*/
	public function save() {	
		if($this->forename == null || $this->surname == null || $this->password == null || $this->addressLine1 == null || $this->addressLine2 == null || $this->town == null || $this->postcode == null) {
			throw new Exception('One or more required fields are not completed.');
		}
		
		if(!filter_var($this->emailAddress, FILTER_VALIDATE_EMAIL))
			throw new Exception('Email address provided must be valid.');

		if($this->isActive == null)
			$this->isActive = 0;

		if ($this->isLoaded === true) {
			$pass = App::secureString($this->password);

			$SQL = "UPDATE members SET 
					Forename = '".mysql_real_escape_string($this->forename)."', 
					Surname = '".mysql_real_escape_string($this->surname)."', 
					AddressLine1 = '".mysql_real_escape_string($this->addressLine1)."', 
					AddressLine2 = '".mysql_real_escape_string($this->addressLine2)."', 
					Town = '".mysql_real_escape_string($this->town)."', 
					Postcode = '".mysql_real_escape_string($this->postcode)."', 
					IsAdmin = ".mysql_real_escape_string($this->isAdmin).", 
					IsActive = ".mysql_real_escape_string($this->isActive)." 
					WHERE MemberID = '".mysql_real_escape_string($this->memberId)."'";

			$this->conn->execute($SQL);
		} else {
			if(!$this->validEmail()) 
				throw new Exception('Email address already in use.');
			

			$SQL = "INSERT INTO members (Forename, Surname, EmailAddress, Password, AddressLine1, AddressLine2, Town, Postcode, IsAdmin, IsActive) 
			VALUES ('".mysql_real_escape_string($this->forename)."', '".mysql_real_escape_string($this->surname)."', '".mysql_real_escape_string($this->emailAddress)."', '".App::secureString($this->password)."', '".mysql_real_escape_string($this->addressLine1)."', '".mysql_real_escape_string($this->addressLine2)."', '".mysql_real_escape_string($this->town)."', '".mysql_real_escape_string($this->postcode)."', ".mysql_real_escape_string($this->isAdmin).", ".mysql_real_escape_string($this->isActive).")";
			$this->isLoaded = true;
			$this->memberId = $this->conn->execute($SQL);
		}		
	}

	public function getMemberId() {
		return $this->memberId;
	}
	
	/* 	This function shuold be used for debugging only.  It outputs all the values of the object.
	*/
	public function toString() {
		$str = "<br />";
		$str .= "<br />memberId: " . $this->getMemberId();
		$str .= "<br />email: " . $this->emailAddress;
		$str .= "<br />forename: " . $this->forename;
		$str .= "<br />surname: " . $this->surname;
		$str .= "<br />password: " . $this->password;
		$str .= "<br />active: " . $this->isActive;
		$str .= "<br />addressLine1: " . $this->addressLine1;
		$str .= "<br />addressLine2: " . $this->addressLine2;
		$str .= "<br />isAdmin: " . $this->isAdmin;
		
		return $str;
	}
}

// $test = new Member();
// $test->populateEmail('mf111@hw.ac.uk');

// print $test->toString();

// $test = new Member("Marc", "Fraser", "test", "mf111@hw.ac.uk", "11 East Bay", "The Shores", "North Queensferry", "KY11 1JX", 1, 1);
// $test->save();



/*$test = new User();
$test->populateUsername("Marc");

//$test->save(); 
//$test->username = "marc3";


//$test->populateUsername("marc3");
//print "<br />" . $test->forename; 
print $test->toString();  */

?>