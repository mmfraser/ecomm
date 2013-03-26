<?php
require_once('App.php');

class Movie {
	private $MovieID;
  	public $Name;
	public $Description;
	public $Cast;
	public $GenreID;
	public $DirectorID;
	public $LanguageID;
	public $ReleaseDate;
	public $duration;
  	public $isLoaded;

	function __construct($Name = "", $Description = "", $Cast = "", $GenreID = "", $DirectorID = "", $LanguageID = "", $ReleaseDate = "", $duration = "") {
		$this->conn = App::getDB();
		$this->Name = $Name;
		$this->Description = $Description;
		$this->Cast = $Cast;
		$this->GenreID = $GenreID;
		$this->DirectorID = $DirectorID;
		$this->LanguageID = $LanguageID;		
		$this->ReleaseDate = $ReleaseDate;
		$this->duration = $duration;
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
		$this->ReleaseDate = $row['ReleaseDate'];
		$this->duration = $row['duration'];
		
		$this->isLoaded = true;
	}

	/* 	This function allows the object to be saved back to the database, whether it is a new object or 
		an object being updated.
	*/
	public function save() {	
		if($this->Name == null || $this->Description == null || $this->Cast == null || $this->GenreID == null || $this->DirectorID == null || $this->LanguageID == null || $this->ReleaseDate == null || $this->duration == null) {
			throw new Exception('One or more required fields are not completed.');
		}
		
			try {
			if(strpos($this->ReleaseDate, '/') !== false) {
				$dateArr = date_parse_from_format("d/m/Y", $this->ReleaseDate);
				$dateFormat = $dateArr["year"] . "-" . $dateArr["month"] . "-" . $dateArr["day"];
			} else if(strpos($this->ReleaseDate, '-') !== false) {
				$dateFormat = $this->ReleaseDate;
			} else 
				throw new Exception("Invalid date format.  Expected dd/mm/yyyy or yyyy-mm-dd.");
		} catch(Exception $e) {
			throw $e;
		}
			$this->ReleaseDate = $dateFormat;

		if ($this->isLoaded === true) {
			
			$SQL = "UPDATE movies SET 
					Name = '".mysql_real_escape_string($this->Name)."' , Description = '".mysql_real_escape_string($this->Description)."', Cast = '".mysql_real_escape_string($this->Cast)."', GenreID = ".mysql_real_escape_string($this->GenreID).", DirectorID = ".mysql_real_escape_string($this->DirectorID).", LanguageID = ".mysql_real_escape_string($this->LanguageID).",
					duration = ".mysql_real_escape_string($this->duration).",
					ReleaseDate = '".mysql_real_escape_string($this->ReleaseDate)."'
					WHERE MovieID = '".mysql_real_escape_string($this->MovieID)."'";

			$this->conn->execute($SQL);
		} else {
			$SQL = "INSERT INTO movies (Name, GenreID, DirectorID, Description, LanguageID, Cast, ReleaseDate, duration) 
					VALUES ('".mysql_real_escape_string($this->Name)."', ".mysql_real_escape_string($this->GenreID).", ".mysql_real_escape_string($this->DirectorID).", '".mysql_real_escape_string($this->Description)."', ".mysql_real_escape_string($this->LanguageID).", '".mysql_real_escape_string($this->Cast)."', '".mysql_real_escape_string($this->ReleaseDate)."', ".mysql_real_escape_string($this->duration).")";
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
		$str .= "<br />ReleaseDate: " . $this->ReleaseDate;
		$str .= "<br />DirectorID: " . $this->DirectorID;
		$str .= "<br />duration: " . $this->duration;
		
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