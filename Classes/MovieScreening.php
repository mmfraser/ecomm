<?php
require_once('App.php');

class MovieScreening {
	private $ScreeningID;
  	public $MovieID;
	public $ScreenID;
	public $Date;
	public $Time;
  	public $isLoaded;

	function __construct($MovieID = "", $ScreenID = "", $Date = "", $Time = "") {
		$this->conn = App::getDB();
		$this->MovieID = $MovieID;
		$this->ScreenID = $ScreenID;
		$this->Date = $Date;
		$this->Time = $Time;
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
		$this->Date = $row['Date'];
		$this->Time = $row['Time'];
		$this->isLoaded = true;
	}

	/* 	This function allows the object to be saved back to the database, whether it is a new object or 
		an object being updated.
	*/
	public function save() {	
		if($this->MovieID == null || $this->ScreenID == null || $this->Date == null || $this->Time == null) {
			throw new Exception('One or more required fields are not completed.');
		}
		
		try {
			if(strpos($this->Date, '/') !== false) {
				$dateArr = date_parse_from_format("m/d/Y", $this->Date);
				$dateFormat = $dateArr["year"] . "-" . $dateArr["month"] . "-" . $dateArr["day"];
			} else if(strpos($this->Date, '-') !== false) {
				$dateFormat = $this->Date;
			} else 
				throw new Exception("Invalid date format.  Expected dd/mm/yyyy or yyyy-mm-dd.");
		} catch(Exception $e) {
			throw $e;
		}
			$this->Date = $dateFormat;
	
			if((int)$this->Time > 2400 || (int)$this->Time < 0) {
				throw new Exception("Invalid time format. Expected HHMM");
			}
			
			$movie = new Movie();
			$movie->populateId($this->MovieID);
			
		// Check to see if screen isn't being used at the time.
			$conflicting = App::getDB()->getArrayFromDB("SELECT * FROM Movie_Screening ms LEFT JOIN Movies m on m.MovieID = ms.MovieID WHERE ms.ScreenID = '".mysql_real_escape_string($this->ScreenID)."' AND ms.Date = '".mysql_real_escape_string($this->Date)."'");
			
			$movieStartTime = strtotime($this->Time);
			$movieEndTime = $movieStartTime + ((int) $movie->duration*60);

			foreach($conflicting as $row) {
				$startTime = strtotime($row['Time']);
				$endTime = $startTime + ((int)$row['duration']*60);
				if($movieStartTime<=$endTime && $startTime<=$movieEndTime) { 
					throw new Exception("Error: film (".$movie->Name.") showing in screen, causing movie overlap.  Please choose a different time or screen.");
				}
			}

			if ($this->isLoaded === true) {		
			$SQL = "UPDATE movie_screening SET 
					MovieID = ".mysql_real_escape_string($this->MovieID).",
					ScreenID = ".mysql_real_escape_string($this->ScreenID).",
					Date = '".mysql_real_escape_string($this->Date)."',
					Time = '".mysql_real_escape_string($this->Time)."',
					WHERE ScreeningID = '".mysql_real_escape_string($this->ScreeningID)."'";

			$this->conn->execute($SQL);
		} else {
			$SQL = "INSERT INTO movie_screening (MovieID, ScreenID, Date, Time) 
			VALUES (".mysql_real_escape_string($this->MovieID).", ".mysql_real_escape_string($this->ScreenID).", '".mysql_real_escape_string($this->Date)."', '".mysql_real_escape_string($this->Time)."')";
			$this->isLoaded = true;
			$this->ScreeningID = $this->conn->execute($SQL);
			print $SQL;
		}		
	}

	/* 	This function shuold be used for debugging only.  It outputs all the values of the object.
	*/
	public function toString() {
		$str = "<br />";
		$str .= "<br />ScreeningID: " . $this->ScreeningID;
		$str .= "<br />MovieID: " . $this->MovieID;
		$str .= "<br />ScreenID: " . $this->ScreenID;
		$str .= "<br />Date: " . $this->Date;
		$str .= "<br />Time: " . $this->Time;
		
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