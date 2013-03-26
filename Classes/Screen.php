<?php
require_once('App.php');

class Screen {
	private $ScreenID;
  	public $NoRows;
	public $NoColumns;
  	public $isLoaded;

	function __construct($NoRows = "", $NoColumns) {
		$this->conn = App::getDB();
		$this->NoRows = $NoRows;
		$this->NoColumns = $NoColumns;
	}

	/*	This function gets the object with data given the userId.
	*/
	public function populateId($ScreenID){
		$sql = "SELECT * FROM screens WHERE ScreenID = '".mysql_real_escape_string($ScreenID)."'";
		$row = $this->conn->getDataRow($sql);
		if($row == null)
			return false;
		$this->getRow($row);
	}

	/*	This function populates the object with data given a datarow.
	*/
	public function getRow($row){
		$this->ScreenID = $row['ScreenID'];
		$this->NoRows = $row['NoRows'];
		$this->NoColumns = $row['NoColumns'];
		$this->isLoaded = true;
	}

	/* 	This function allows the object to be saved back to the database, whether it is a new object or 
		an object being updated.
	*/
	public function save() {	
		if($this->NoColumns == null || $this->NoRows == null) {
			throw new Exception('One or more required fields are not completed.');
		}

		if ($this->isLoaded === true) {
			
			$SQL = "UPDATE screens SET 
					NoColumns = '".mysql_real_escape_string($this->NoColumns)."',
					NoRows = '".mysql_real_escape_string($this->NoRows)."'
					WHERE ScreenID = '".mysql_real_escape_string($this->ScreenID)."'";

			$this->conn->execute($SQL);
		} else {
			$SQL = "INSERT INTO screens (NoRows, NoColumns) 
			VALUES ('".mysql_real_escape_string($this->NoRows)."','".mysql_real_escape_string($this->NoColumns)."')";
			$this->isLoaded = true;
			$this->ScreenID = $this->conn->execute($SQL);
		}		
	}

	/* 	This function shuold be used for debugging only.  It outputs all the values of the object.
	*/
	public function toString() {
		$str = "<br />";
		$str .= "<br />ScreenID: " . $this->ScreenID;
		$str .= "<br />NoRows: " . $this->NoRows;
		$str .= "<br />NoRows: " . $this->NoColumns;
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