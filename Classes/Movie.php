<?php
require_once('../App.php');

class Movie {
	private $MovieID;
  	public $Name;
	public $Description;
	public $Cast;
	private $GenreID;
	private $DirectorID;
	private $LanguageID;
  	public $isLoaded;

	function __construct($Name = "", $Description = "", $Cast = "", $GenreID = "", $DirectorID = "", $LanguageID = "") {
		$this->conn = App::getDB();
		$this->Name = $Name;
		$this->Description = $Description;
		$this->Cast = $Cast;
		$this->GenreID = $GenreID;
		$this->DirectorID = $DirectorID;
		$this->LanguageID = $LanguageID;		
	}

	/*	This function gets the object with data given the userId.
	*/
	public function populateId($MovieID){
		$sql = "SELECT * FROM movies WHERE MovieID = '".mysql_real_escape_string($MovieID)."'";
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
		$this->Description = $row['Description'];
		$this->Cast = $row['Cast'];
		$this->GenreID = $row['GenreID'];
		$this->DirectorID = $row['DirectorID'];
		$this->LanguageID = $row['LanguageID'];
		
		$this->isLoaded = true;
	}

	/* 	This function allows the object to be saved back to the database, whether it is a new object or 
		an object being updated.
	*/
	public function save() {	
		if($this->Name == null, $this->Description == null, $this->Cast == null, $this->GenreID == null, $this->DirectorID == null, $this->LanguageID == null) {
			throw new Exception('One or more required fields are not completed.');
		}

		if ($this->isLoaded === true) {
			
			$SQL = "UPDATE movies SET 
					Name = '".mysql_real_escape_string($this->Name)."' , Description = '".mysql_real_escape_string($this->Description)."', Cast = '".mysql_real_escape_string($this->Cast)."', GenreID = ".mysql_real_escape_string($this->GenreID).", DirectorID = ".mysql_real_escape_string($this->DirectorID).", LanguageID = ".mysql_real_escape_string($this->LanguageID)."
					WHERE MovieID = '".mysql_real_escape_string($this->MovieID)."'";

			$this->conn->execute($SQL);
		} else {
			$SQL = "INSERT INTO movies (Name, GenreID, DirectorID, Descrption, LanguageID, Cast) 
					VALUES ('".mysql_real_escape_string($this->Name)."', ".mysql_real_escape_string($this->GenreID).", ".mysql_real_escape_string($this->DirectorID).", '".mysql_real_escape_string($this->Descrption)."', ".mysql_real_escape_string($this->LanguageID).", '".mysql_real_escape_string($this->Cast)."')";
			$this->isLoaded = true;
			$this->MovieID = $this->conn->execute($SQL);
		}		
	}

	/* 	This function shuold be used for debugging only.  It outputs all the values of the object.
	*/
	public function toString() {
		$str = "<br />";
		$str .= "<br />MovieID: " . $this->MovieID;
		$str .= "<br />Name: " . $this->Name;
		$str .= "<br />Description: " . $this->Description;
		$str .= "<br />Cast: " . $this->Cast;
		$str .= "<br />GenreID: " . $this->GenreID;
		$str .= "<br />LanguageID: " . $this->LanguageID;
		$str .= "<br />LanguageID: " . $this->DirectorID;
		
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