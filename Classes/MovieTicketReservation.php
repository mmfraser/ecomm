<?php
require_once('../App.php');

class MovieTicketReservation {
	private $MovieTicketReservationID;
  	public $ReservationID;
	public $MovieTicketCostID;
	public $NoSeats;
  	public $isLoaded;

	function __construct($ReservationID = "", $MovieTicketCostID = "", $NoSeats = "") {
		$this->conn = App::getDB();
		$this->ReservationID = $ReservationID;
		$this->MovieTicketCostID = $MovieTicketCostID;
		$this->NoSeats = $NoSeats;
	}

	/*	This function gets the object with data given the userId.
	*/
	public function populateId($MovieTicketReservationID){
		$sql = "SELECT * FROM movie_ticket_reservation WHERE MovieTicketReservationID = '".mysql_real_escape_string($MovieTicketReservationID)."'";
		$row = $this->conn->getDataRow($sql);
		if($row == null)
			return false;
		$this->getRow($row);
	}

	/*	This function populates the object with data given a datarow.
	*/
	public function getRow($row){
		$this->MovieTicketReservationID = $row['MovieTicketReservationID'];
		$this->ReservationID = $row['ReservationID'];
		$this->MovieTicketCostID = $row['MovieTicketCostID'];
		$this->NoSeats = $row['NoSeats'];
		$this->isLoaded = true;
	}

	/* 	This function allows the object to be saved back to the database, whether it is a new object or 
		an object being updated.
	*/
	public function save() {	
		if($this->ReservationID == null || $this->MovieTicketCostID == null || $this->NoSeats == null) {
			throw new Exception('One or more required fields are not completed.');
		}

		if ($this->isLoaded === true) {		
			$SQL = "UPDATE movie_ticket_reservation SET 
					ReservationID = ".mysql_real_escape_string($this->ReservationID).",
					MovieTicketCostID = ".mysql_real_escape_string($this->MovieTicketCostID).",
					NoSeats = '".mysql_real_escape_string($this->NoSeats)."'
					WHERE MovieTicketReservationID = '".mysql_real_escape_string($this->MovieTicketReservationID)."'";

			$this->conn->execute($SQL);
		} else {
			$SQL = "INSERT INTO languages (ReservationID, MovieTicketCostID, NoSeats) 
			VALUES (".mysql_real_escape_string($this->ReservationID).", ".mysql_real_escape_string($this->MovieTicketCostID).", '".mysql_real_escape_string($this->NoSeats)."')";
			$this->isLoaded = true;
			$this->MovieTicketReservationID = $this->conn->execute($SQL);
		}		
	}

	/* 	This function shuold be used for debugging only.  It outputs all the values of the object.
	*/
	public function toString() {
		$str = "<br />";
		$str .= "<br />MovieTicketReservationID: " . $this->MovieTicketReservationID;
		$str .= "<br />ReservationID: " . $this->ReservationID;
		$str .= "<br />MovieTicketCostID: " . $this->MovieTicketCostID;
		$str .= "<br />NoSeats: " . $this->NoSeats;
		
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