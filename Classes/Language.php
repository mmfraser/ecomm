<?php
require_once('../App.php');

class Genre {
	private $GenreID;
  	public $Name;
  	public $Description;
  	public $isLoaded;

	function __construct($Name = "", $Description = "") {
		$this->conn = App::getDB();
		$this->Name = $Name;
		$this->Description = $Description;
	}

	/*	This function gets the object with data given the userId.
	*/
	public function populateId($genreID){
		$sql = "SELECT * FROM genre WHERE GenreID = '".mysql_real_escape_string($genreID)."'";
		$row = $this->conn->getDataRow($sql);
		if($row == null)
			return false;
		$this->getRow($row);
	}

	/*	This function populates the object with data given a datarow.
	*/
	public function getRow($row){
		$this->GenreID = $row['GenreID'];
		$this->Name = $row['Name'];
		$this->Description = $row['Description'];
	
		$this->isLoaded = true;
	}

	/* 	This function allows the object to be saved back to the database, whether it is a new object or 
		an object being updated.
	*/
	public function save() {	
		if($this->Name == null || $this->Description == null) {
			throw new Exception('One or more required fields are not completed.');
		}

		if ($this->isLoaded === true) {
			
			$SQL = "UPDATE genre SET 
					Name = '".mysql_real_escape_string($this->Name)."', 
					Description = '".mysql_real_escape_string($this->Description)."'
					WHERE GenreID = '".mysql_real_escape_string($this->GenreID)."'";

			$this->conn->execute($SQL);
		} else {
			$SQL = "INSERT INTO genre (Name, Description) 
			VALUES ('".mysql_real_escape_string($this->Name)."', '".mysql_real_escape_string($this->Description)."')";
			$this->isLoaded = true;
			$this->GenreID = $this->conn->execute($SQL);
		}		
	}

	/* 	This function shuold be used for debugging only.  It outputs all the values of the object.
	*/
	public function toString() {
		$str = "<br />";
		$str .= "<br />GenreID: " . $this->GenreID;
		$str .= "<br />Name: " . $this->Name;
		$str .= "<br />Description: " . $this->Description;	
		return $str;
	}
}

 // $test = new Genre();
 // $test->populateId(2);

 // print $test->toString();

/*$test = new User();
$test->populateUsername("Marc");

//$test->save(); 
//$test->username = "marc3";


//$test->populateUsername("marc3");
//print "<br />" . $test->forename; 
print $test->toString();  */

?>