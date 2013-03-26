<?php
require_once('App.php');

class MovieReservation {
	private $ReservationID;
  	public $MemberID;
	public $MovieScreeningID;
	public $PurchaseDate;
  	public $isLoaded;

	function __construct($MemberID = "", $MovieScreeningID = "", $PurchaseDate = "") {
		$this->conn = App::getDB();
		$this->MemberID = $MemberID;
		$this->MovieScreeningID = $MovieScreeningID;
		$this->PurchaseDate = $PurchaseDate;
	}

	/*	This function gets the object with data given the userId.
	*/
	public function populateId($ReservationID){
		$sql = "SELECT * FROM movie_reservation WHERE ReservationID = '".mysql_real_escape_string($ReservationID)."'";
		$row = $this->conn->getDataRow($sql);
		if($row == null)
			return false;
		$this->getRow($row);
	}

	/*	This function populates the object with data given a datarow.
	*/
	public function getRow($row){
		$this->ReservationID = $row['ReservationID'];
		$this->MemberID = $row['MemberID'];
		$this->MovieScreeningID = $row['MovieScreeningID'];
		$this->PurchaseDate = $row['PurchaseDate'];
		
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
			$SQL = "UPDATE movie_reservation SET 
					MemberID = ".mysql_real_escape_string($this->MemberID)." , MovieScreeningID = ".mysql_real_escape_string($this->MovieScreeningID).", PurchaseDate = '".mysql_real_escape_string($this->PurchaseDate)."' 
					WHERE ReservationID = '".mysql_real_escape_string($this->ReservationID)."'";

			$this->conn->execute($SQL);
		} else {
			$SQL = "INSERT INTO movie_reservation (MemberID, MovieScreeningID, PurchaseDate) 
VALUES (".mysql_real_escape_string($this->MemberID).", ".mysql_real_escape_string($this->MovieScreeningID).", '".mysql_real_escape_string($this->PurchaseDate)."')";
			$this->isLoaded = true;
			$this->ReservationID = $this->conn->execute($SQL);
		}		
	}

	/* 	This function shuold be used for debugging only.  It outputs all the values of the object.
	*/
	public function toString() {
		$str = "<br />";
		$str .= "<br />ReservationID: " . $this->ReservationID;
		$str .= "<br />MemberID: " . $this->MemberID;
		$str .= "<br />MovieScreeningID: " . $this->MovieScreeningID;
		$str .= "<br />PurchaseDate: " . $this->PurchaseDate;

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