<?php
require_once('App.php');

class TicketCost {
	private $MovieTicketCostID;
  	public $TicketCategoryID;
	public $MovieID;
	public $TicketCost;
  	public $isLoaded;

	function __construct($TicketCategoryID = "", $MovieID = "", $TicketCost = "") {
		$this->conn = App::getDB();
		$this->TicketCategoryID = $TicketCategoryID;
		$this->MovieID = $MovieID;
		$this->TicketCost = $TicketCost;
	}

	/*	This function gets the object with data given the userId.
	*/
	public function populateId($MovieTicketCostID){
		$sql = "SELECT * FROM ticket_cost WHERE MovieTicketCostID = '".mysql_real_escape_string($MovieTicketCostID)."'";
		$row = $this->conn->getDataRow($sql);
		if($row == null)
			return false;
		$this->getRow($row);
	}

	/*	This function populates the object with data given a datarow.
	*/
	public function getRow($row){
		$this->MovieTicketCostID = $row['MovieTicketCostID'];
		$this->TicketCategoryID = $row['TicketCategoryID'];
		$this->MovieID = $row['MovieID'];
		$this->TicketCost = $row['TicketCost'];
		
		$this->isLoaded = true;
	}

	/* 	This function allows the object to be saved back to the database, whether it is a new object or 
		an object being updated.
	*/
	public function save() {	
		if($this->TicketCategoryID == null || $this->MovieID == null || $this->TicketCost == null) {
			throw new Exception('One or more required fields are not completed.');
		}

		if ($this->isLoaded === true) {
			
			$SQL = "UPDATE ticket_cost SET 
					TicketCategoryID = '".mysql_real_escape_string($this->TicketCategoryID)."',
					MovieID = '".mysql_real_escape_string($this->MovieID)."',
					TicketCost = '".mysql_real_escape_string($this->TicketCost)."'
					WHERE MovieTicketCostID = '".mysql_real_escape_string($this->MovieTicketCostID)."'";

			$this->conn->execute($SQL);
		} else {
			$SQL = "INSERT INTO ticket_cost (TicketCategoryID, MovieID, TicketCost) 
			VALUES (".mysql_real_escape_string($this->TicketCategoryID).", ".mysql_real_escape_string($this->MovieID).", ".mysql_real_escape_string($this->TicketCost).")";
			$this->isLoaded = true;
			$this->MovieTicketCostID = $this->conn->execute($SQL);
		}		
	}

	/* 	This function shuold be used for debugging only.  It outputs all the values of the object.
	*/
	public function toString() {
		$str = "<br />";
		$str .= "<br />MovieTicketCostID: " . $this->MovieTicketCostID;
		$str .= "<br />TicketCategoryID: " . $this->TicketCategoryID;
		$str .= "<br />MovieID: " . $this->MovieID;
		$str .= "<br />TicketCost: " . $this->TicketCost;
	
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