<?php
require_once('App.php');
require_once('MovieTicketReservation.php');

class MovieReservation {
	private $ReservationID;
  	public $MemberID;
	public $MovieScreeningID;
	public $ReservationDate;
	public $adult;
	public $child;
	public $student;
	public $concession;
  	public $isLoaded;

	function __construct($MemberID = "", $MovieScreeningID = "", $adult = 0, $child = 0, $student = 0, $concession = 0) {
		$this->conn = App::getDB();
		$this->MemberID = $MemberID;
		$this->MovieScreeningID = $MovieScreeningID;
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
		$this->ReservationDate = $row['ReservationDate'];
		
		$this->isLoaded = true;
	}
	
	public function updateTicketReservation() {
		App::getDB()->execute("DELETE FROM movie_ticket_reservation WHERE ReservationID = ".$this->ReservationID."");
		
		if($this->adult > 0) {
			$tcktRes = new MovieTicketReservation($this->ReservationID, 1, $this->adult);
			$tcktRes->save();
		}
		
		if($this->child > 0) {
			$tcktRes = new MovieTicketReservation($this->ReservationID, 2, $this->child);
			$tcktRes->save();
		}
		
		if($this->student > 0) {
			$tcktRes = new MovieTicketReservation($this->ReservationID, 3, $this->student);
			$tcktRes->save();
		}
		
		if($this->concession > 0) {
			$tcktRes = new MovieTicketReservation($this->ReservationID, 4, $this->concession);
			$tcktRes->save();
		}
	
	
	}

	/* 	This function allows the object to be saved back to the database, whether it is a new object or 
		an object being updated.
	*/
	public function save() {	
		if($this->MemberID == null || $this->MovieScreeningID == null) {
			throw new Exception('One or more required fields are not completed.');
		}
		updateTicketReservation();

		if ($this->isLoaded === true) {
			$SQL = "UPDATE movie_reservation SET 
					MemberID = ".mysql_real_escape_string($this->MemberID)." , MovieScreeningID = ".mysql_real_escape_string($this->MovieScreeningID).", ReservationDate = '".mysql_real_escape_string($this->ReservationDate)."' 
					WHERE ReservationID = '".mysql_real_escape_string($this->ReservationID)."'";

			$this->conn->execute($SQL);
		} else {
			$SQL = "INSERT INTO movie_reservation (MemberID, MovieScreeningID) 
VALUES (".mysql_real_escape_string($this->MemberID).", ".mysql_real_escape_string($this->MovieScreeningID).")";
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
		$str .= "<br />ReservationDate: " . $this->ReservationDate;

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