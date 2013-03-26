<?php
	require_once('App.php');
	require_once('Member.php');
	
	class Authenticate {
		private $member;
		private $isAuthenticated;

		public function __construct() {
			$this->conn = App::getDB();
		}

		public function Authenticate() {
			$this->member = null;
			$this->isAuthenticated = false;
		}	

		public function doAuth($email, $password) {
			
			if(is_null($email) || is_null($password)) 
				return false;
			

			$query = "SELECT * FROM members WHERE EmailAddress = '".mysql_real_escape_string($email)."' AND password = '".App::secureString($password)."' AND IsActive=1";

			$row = $this->conn->getDataRow($query);

			if($row == null)
				return false;
			else {  
				$member = new Member();
				$member->populateId($row['MemberID']);
				$this->member = $member;
				$this->isAuthenticated = true;
				$_SESSION['loggedIn'] = serialize($this);
			} 
		}

		public function getAuthMember() {
			return $this->member;
		}

		public function isAuth() {
			return $this->isAuthenticated;
		}
	}
?>