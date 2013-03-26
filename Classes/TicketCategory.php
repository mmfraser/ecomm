<?php
require_once('../App.php');

class TicketCategory {
	private $TicketCategoryID;
  	public $CategoryName;
	public $DefaultPrice;
  	public $isLoaded;

	function __construct($CategoryName = "", $DefaultPrice = "") {
		$this->conn = App::getDB();
		$this->CategoryName = $CategoryName;
		$this->DefaultPrice = $DefaultPrice;
	}

	/*	This function gets the object with data given the userId.
	*/
	public function populateId($TicketCategoryID){
		$sql = "SELECT * FROM ticket_category WHERE TicketCategoryID = '".mysql_real_escape_string($TicketCategoryID)."'";
		$row = $this->conn->getDataRow($sql);
		if($row == null)
			return false;
		$this->getRow($row);
	}

	/*	This function populates the object with data given a datarow.
	*/
	public function getRow($row){
		$this->TicketCategoryID = $row['TicketCategoryID'];
		$this->CategoryName = $row['CategoryName'];
		$this->DefaultPrice = $row['DefaultPrice'];
		
		$this->isLoaded = true;
	}

	/* 	This function allows the object to be saved back to the database, whether it is a new object or 
		an object being updated.
	*/
	public function save() {	
		if($this->CategoryName == null || $this->DefaultPrice == null) {
			throw new Exception('One or more required fields are not completed.');
		}

		if ($this->isLoaded === true) {
			
			$SQL = "UPDATE ticket_category SET 
					CategoryName = '".mysql_real_escape_string($this->CategoryName)."',
					DefaultPrice = '".mysql_real_escape_string($this->DefaultPrice)."'
					WHERE TicketCategoryID = '".mysql_real_escape_string($this->TicketCategoryID)."'";

			$this->conn->execute($SQL);
		} else {
			$SQL = "INSERT INTO ticket_category (CategoryName, DefaultPrice) 
			VALUES ('".mysql_real_escape_string($this->CategoryName)."', '".mysql_real_escape_string($this->DefaultPrice)."')";
			$this->isLoaded = true;
			$this->TicketCategoryID = $this->conn->execute($SQL);
		}		
	}

	/* 	This function shuold be used for debugging only.  It outputs all the values of the object.
	*/
	public function toString() {
		$str = "<br />";
		$str .= "<br />TicketCategoryID: " . $this->TicketCategoryID;
		$str .= "<br />CategoryName: " . $this->CategoryName;
		$str .= "<br />DefaultPrice: " . $this->DefaultPrice;
	
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