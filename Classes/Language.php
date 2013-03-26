<?php
require_once('App.php');

class Language {
	private $LanguageID;
  	public $Name;
  	public $isLoaded;

	function __construct($Name = "") {
		$this->conn = App::getDB();
		$this->Name = $Name;
	}

	/*	This function gets the object with data given the userId.
	*/
	public function populateId($languageID){
		$sql = "SELECT * FROM languages WHERE LanguageID = '".mysql_real_escape_string($languageID)."'";
		$row = $this->conn->getDataRow($sql);
		if($row == null)
			return false;
		$this->getRow($row);
	}

	/*	This function populates the object with data given a datarow.
	*/
	public function getRow($row){
		$this->LanguageID = $row['LanguageID'];
		$this->Name = $row['Name'];
		
		$this->isLoaded = true;
	}

	/* 	This function allows the object to be saved back to the database, whether it is a new object or 
		an object being updated.
	*/
	public function save() {	
		if($this->Name == null) {
			throw new Exception('One or more required fields are not completed.');
		}

		if ($this->isLoaded === true) {
			
			$SQL = "UPDATE languages SET 
					Name = '".mysql_real_escape_string($this->Name)."'
					WHERE LanguageID = '".mysql_real_escape_string($this->LanguageID)."'";

			$this->conn->execute($SQL);
		} else {
			$SQL = "INSERT INTO languages (Name) 
			VALUES ('".mysql_real_escape_string($this->Name)."')";
			$this->isLoaded = true;
			$this->LanguageID = $this->conn->execute($SQL);
		}		
	}

	/* 	This function shuold be used for debugging only.  It outputs all the values of the object.
	*/
	public function toString() {
		$str = "<br />";
		$str .= "<br />LanguageID: " . $this->LanguageID;
		$str .= "<br />Name: " . $this->Name;
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