<?php
require_once('App.php');
require_once('MovieTicketReservation.php');
require_once('Member.php');
require_once('Movie.php');
require_once('MovieScreening.php');


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
		$this->adult = $adult;
		$this->child = $child;
		$this->student = $student;
		$this->concession = $concession;
	}
	
	
	
	function GetReservationTicket() {
		$member = new Member();
		$member->populateId($this->MemberID);
		
		$movieScreening = new MovieScreening();
		$movieScreening->populateId($this->MovieScreeningID);
		
		$movie = new Movie();
		$movie->populateId($movieScreening->MovieID);
	
		$html = '<div style="width:90%; border:1px solid #000;  margin-top:20px; padding:10px;">
				<h2>Reservation Confirmation</h2>
				
				<p><em>Please print this confirmation and bring it to the cinema - it acts as your seat reservaion.  <br /> Note: this reservation still requires payment.</em></p>
				
				<div style="float:left; width:400px;">
				<p><strong>Reservation Number:</strong></p>
				<p>'.$this->ReservationID.'</p>
				
				<p><strong>Member:</strong></p>
				<p>'.$member->forename.' '.$member->surname.' (Member ID: '.$member->getMemberId().')</p>

				<p><strong>Movie:</strong></p>
				<p>'.$movie->Name.'</p>

				<p><strong>Screening Date:</strong></p>
				<p>'.$movieScreening->Date.' at '.$movieScreening->Time.'</p>

				<p><strong>Screen Number:</strong></p>
				<p>'.$movieScreening->ScreenID.'</p>
				</div>
				<div>
				<p><strong>Adults:</strong></p>
				<p>'.$this->adult.'</p>

				<p><strong>Children:</strong></p>
				<p>'.$this->child.'</p>

				<p><strong>Students:</strong></p>
				<p>'.$this->student.'</p>

				<p><strong>Concessions:</strong></p>
				<p>'.$this->concession.'</p>
				</div>
				<div class="clear"></div>
				<h5 style="margin-top:20px;">Thank you for choosing CineMagic!</h5>

				</div>';
		
		return $html;
	}

	/*	This function gets the object with data given the userId.
	*/
	public function populateId($ReservationID){
		$sql = "SELECT * FROM movie_reservation WHERE ReservationID = '".mysql_real_escape_string($ReservationID)."'";
		$row = $this->conn->getDataRow($sql);
		if($row == null)
			return false;
		
		
		// get ticket bookings
		$tixResArr = $this->conn->getArrayFromDB("SELECT * FROM movie_ticket_reservation WHERE ReservationID = '".mysql_real_escape_string($ReservationID)."'");
		
		$this->getRow($row, $tixResArr);
	}

	/*	This function populates the object with data given a datarow.
	*/
	public function getRow($row, $tixResArray){
		$this->ReservationID = $row['ReservationID'];
		$this->MemberID = $row['MemberID'];
		$this->MovieScreeningID = $row['MovieScreeningID'];
		$this->ReservationDate = $row['ReservationDate'];
		
		foreach($tixResArray as $tixRow) {
			if($tixRow['TicketCategoryID'] == 1) {
				$this->adult = $tixRow['NoSeats'];
			} else if($tixRow['TicketCategoryID'] == 2) {
				$this->child = $tixRow['NoSeats'];
			} else if($tixRow['TicketCategoryID'] == 3) {
				$this->student = $tixRow['NoSeats'];
			} else if($tixRow['TicketCategoryID'] == 4) {
				$this->concession = $tixRow['NoSeats'];
			}
		}

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
		if($this->MemberID == null || $this->MovieScreeningID == null || ($this->adult + $this->student + $this->child + $this->concession) <= 0) {
			throw new Exception('One or more required fields are not completed.');
		}
	

		if ($this->isLoaded === true) {
			
			$SQL = "UPDATE movie_reservation SET 
					MemberID = ".mysql_real_escape_string($this->MemberID)." , MovieScreeningID = ".mysql_real_escape_string($this->MovieScreeningID).", ReservationDate = '".mysql_real_escape_string($this->ReservationDate)."' 
					WHERE ReservationID = '".mysql_real_escape_string($this->ReservationID)."'";

			$this->conn->execute($SQL);
			$this->updateTicketReservation();
		} else {
			$SQL = "INSERT INTO movie_reservation (MemberID, MovieScreeningID) 
VALUES (".mysql_real_escape_string($this->MemberID).", ".mysql_real_escape_string($this->MovieScreeningID).")";
			$this->isLoaded = true;
			$this->ReservationID = $this->conn->execute($SQL);
			$this->updateTicketReservation();
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