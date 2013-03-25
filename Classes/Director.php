<?php
require_once('../App.php');

class Director {
	private $directorId;
  	public $forename;
  	public $surname;
  	public $isLoaded;

	function __construct($forename = "", $surname = "") {
		$this->conn = App::getDB();
		$this->forename = $forename;
		$this->surname = $surname;
	}

	/*	This function gets the object with data given the userId.
	*/
	public function populateId($directorId){
		$sql = "SELECT * FROM directors WHERE DirectorID = '".mysql_real_escape_string($directorId)."'";
		$row = $this->conn->getDataRow($sql);
		if($row == null)
			return false;
		$this->getRow($row);
	}

	/*	This function populates the object with data given a datarow.
	*/
	public function getRow($row){
		$this->directorId = $row['DirectorId'];
		$this->forename = $row['Forename'];
		$this->surname = $row['Surname'];
	
		$this->isLoaded = true;
	}

	/* 	This function allows the object to be saved back to the database, whether it is a new object or 
		an object being updated.
	*/
	public function save() {	
		if($this->forename == null || $this->surname == null) {
			throw new Exception('One or more required fields are not completed.');
		}

		if ($this->isLoaded === true) {
			
			$SQL = "UPDATE directors SET 
					Forename = '".mysql_real_escape_string($this->forename)."', 
					Surname = '".mysql_real_escape_string($this->surname)."'
					WHERE DirectorID = '".mysql_real_escape_string($this->directorID)."'";

			$this->conn->execute($SQL);
		} else {
			$SQL = "INSERT INTO directors (Forename, Surname) 
			VALUES ('".mysql_real_escape_string($this->forename)."', '".mysql_real_escape_string($this->surname)."')";
			$this->isLoaded = true;
			$this->directorId = $this->conn->execute($SQL);
		}		
	}

	/* 	This function shuold be used for debugging only.  It outputs all the values of the object.
	*/
	public function toString() {
		$str = "<br />";
		$str .= "<br />directorId: " . $this->directorId;
		$str .= "<br />forename: " . $this->forename;
		$str .= "<br />surname: " . $this->surname;	
		return $str;
	}
}

// $test = new Director();
// $test->populateId(3);

// print $test->toString();

/*$test = new User();
$test->populateUsername("Marc");

//$test->save(); 
//$test->username = "marc3";


//$test->populateUsername("marc3");
//print "<br />" . $test->forename; 
print $test->toString();  */

?>