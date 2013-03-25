<?php
	require_once('../App.php');
	require_once('Member.php');
	
	// TODO: add in active/disactive functionality.......
	class Authenticate {
		private $member;
		private $isAuthenticated;

		public function __construct() {
			$this->conn = App::getDB();
		}

		public function Authenticate() {
			$this->user = null;
			$this->isAuthenticated = false;
		}	

		public function doAuth($email, $password) {
			if(is_null($username) || is_null($password)) 
				return false;

			$query = "SELECT * FROM members WHERE EmailAddress = '".mysql_real_escape_string($email)."' AND password = '".App::secureString($password)."' AND IsActive=1";

			$row = $this->conn->getDataRow($query);

			if($row == null)
				return false;
			else {  
				$member = new Member();
				$member->populateId($row['userId']);
				$member->user = $user;
				$this->isAuthenticated = true;
				$_SESSION['loggedIn'] = serialize($this);
			} 
		}

		public function getAuthUser() {
			return $this->user;
		}

		public function isAuth() {
			return $this->isAuthenticated;
		}
	}
?>