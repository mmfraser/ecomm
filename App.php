<?php
	error_reporting(E_ERROR | E_WARNING);
	ob_start();
	session_start();
	require_once('Classes/DB.php');
	require_once('Classes/Authenticate.php');
	require_once('Classes/Page.php');

	class App {
		public static $auth = false; // Set to false if not authenticated

		public static function getDB() {
			return DB::getInstance();
		}

		public static function secureString($str) {
			return md5($str);
		}

		public static function authUser($email, $password) {
		
			if(isset($_SESSION['loggedIn']) && !is_object(self::$auth)) {
				self::$auth = unserialize($_SESSION['loggedIn']);
			} else {
		
				self::$auth = new Authenticate();
				self::$auth->doAuth($email, $password);
			}

			if(self::$auth->isAuth()) {
				return true;
			} else
				return false;
		}
		
		public static function getAuthMember() {
			if(self::checkAuth()) 
				return self::$auth->getAuthMember();
			else 
				return null;
		}

		public static function logoutUser() {
			session_destroy();
			self::$auth = false;
		}

		public static function checkAuth() {
			if(isset($_SESSION['loggedIn']) && !is_object(self::$auth)) {
				self::$auth = unserialize($_SESSION['loggedIn']);
			}
			if(!is_object(self::$auth)) {
				return false;
			} else {
				if(self::$auth->isAuth())
					return true;
				return false;
			}
		}

		public static function fatalError($page, $err) {
			print '<div class="ui-state-error ui-corner-all"><span class="ui-icon ui-icon-alert" style="float:left;margin:2px 5px 0 0;"></span><span>'.$err.'</span></div>';
			$page->getfooter();
			die();
		}
		
		public static function nonFatalError($err) {
			print '<div class="ui-state-error ui-corner-all"><span class="ui-icon ui-icon-alert" style="float:left;margin:2px 5px 0 0;"></span><span>'.$err.'</span></div>';

		}
		
		public static function infoAlert($msg) {
			print '<div class="ui-state-highlight ui-corner-all"><span class="ui-icon ui-icon-info" style="float:left;margin:2px 5px 0 0;"></span><span>'.$msg.'</span></div>';

		}
	}
	
	if(isset($_GET) && $_GET['do'] == "logout") {
		App::logoutUser();
		header('Location: index.php');
	}
	
?>