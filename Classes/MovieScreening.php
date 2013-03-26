<?php
require_once('../App.php');

class MovieScreening {
	private $ScreeningID;
  	public $MovieID;
	public $ScreenID;
	public $DateTime;
  	public $isLoaded;

	function __construct($MovieID = "", $ScreenID = "", $DateTime = "") {
		$this->conn = App::getDB();
		$this->MovieID = $MovieID;
		$this->ScreenID = $ScreenID;
		$this->DateTime = $DateTime;
	}

	/*	This function gets the object with data given the userId.
	*/
	public function populateId($ScreeningID){
		$sql = "SELECT * FROM movie_screening WHERE ScreeningID = '".mysql_real_escape_string($ScreeningID)."'";
		$row = $this->conn->getDataRow($sql);
		if($row == null)
			return false;
		$this->getRow($row);
	}

	/*	This function populates the object with data given a datarow.
	*/
	public function getRow($row){
		$this->ScreeningID = $row['ScreeningID'];
		$this->MovieID = $row['MovieID'];
		$this->ScreenID = $row['ScreenID'];
		$this->DateTime = $row['DateTime'];
		$this->isLoaded = true;
	}

	/* 	This function allows the object to be saved back to the database, whether it is a new object or 
		an object being updated.
	*/
	public function save() {	
		if($this->Name == null || $this->MovieID == null || $this->ScreenID == null || $this->DateTime == null) {
			throw new Exception('One or more required fields are not completed.');
		}

		if ($this->isLoaded === true) {		
			$SQL = "UPDATE movie_screening SET 
					MovieID = ".mysql_real_escape_string($this->MovieID).",
					ScreenID = ".mysql_real_escape_string($this->ScreenID).",
					DateTime = '".mysql_real_escape_string($this->DateTime)."'
					WHERE ScreeningID = '".mysql_real_escape_string($this->ScreeningID)."'";

			$this->conn->execute($SQL);
		} else {
			$SQL = "INSERT INTO languages (MovieID, ScreenID, DateTime) 
			VALUES (".mysql_real_escape_string($this->MovieID).", ".mysql_real_escape_string($this->ScreenID).", '".mysql_real_escape_string($this->DateTime)."')";
			$this->isLoaded = true;
			$this->ScreeningID = $this->conn->execute($SQL);
		}		
	}

	/* 	This function shuold be used for debugging only.  It outputs all the values of the object.
	*/
	public function toString() {
		$str = "<br />";
		$str .= "<br />ScreeningID: " . $this->ScreeningID;
		$str .= "<br />MovieID: " . $this->MovieID;
		$str .= "<br />ScreenID: " . $this->ScreenID;
		$str .= "<br />DateTime: " . $this->DateTime;
		
		return $str;
	}
}

  // $test = new Language("");
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