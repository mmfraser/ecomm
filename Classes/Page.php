<?php
	class Page {
		public $title;
		private $headJS = "";
		
		public function __construct($title) {
			$this->title = $title;
		}
		
		public function getHeader() {
			
			$output = '<!DOCTYPE html>' . PHP_EOL;
			$output .= '<html>' . PHP_EOL;
			$output .= '	<head>' . PHP_EOL;
			$output .= '		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />' . PHP_EOL;
			$output .= '		<title>' . $this->title . '</title>' . PHP_EOL;
			$output .= '		<link href="style.css" rel="stylesheet" type="text/css" />' . PHP_EOL;
			$output .= '		<link type="text/css" href="css/ui-lightness/jquery-ui-1.10.2.custom.css" rel="Stylesheet" />' . PHP_EOL;
			$output .= '		<script type="text/javascript" src="js/jquery-1.9.1.js"></script>' . PHP_EOL;
			$output .= '		<script type="text/javascript" src="js/jquery.cookie.js"></script>' . PHP_EOL;
			$output .= '		<script type="text/javascript" src="js/jquery-ui-1.10.2.custom.js"></script>' . PHP_EOL;
			$output .= '		<script type="text/javascript" src="js/jquery.dataTables.js"></script>' . PHP_EOL;
			$output .= '	</head>' . PHP_EOL;
			$output .= '	<body>' .PHP_EOL;
			$output .= '			<div id="wrap">' . PHP_EOL;
			$output .= 			$this->header();
			$output .= '				<div id="contentContainer">' . PHP_EOL;
			$output .= '					<div id="contents">' . PHP_EOL;
			echo $output; 
		
		}
		
		private function header() { 
			$output = '' . PHP_EOL;
			$output .= '	<div id="head">' . PHP_EOL;
			$output .= '		<div id="headLeft"><h1>' . $this->title. '</h1></div>' .PHP_EOL;
			$output .= '		<div id="headRight">' . PHP_EOL;
			$output .= '			<ul>' . PHP_EOL;
			$output .= '				<li '.$homeActive.'><a href="index.php">Home</a></li>' . PHP_EOL;
			$output .= '				<li '.$moviesActive.'><a href="movies.php">Movies</a></li>' . PHP_EOL;
			$output .= '				<li '.$directorsActive.'><a href="directors.php">Directors</a></li>' . PHP_EOL;
			$output .= '				<li '.$contactUsActive.'><a href="contactus.php">Contact Us</a></li>' . PHP_EOL;
			
			if(App::checkAuth()) {
				$output .= '				<li '.$accountActive.'><a href="App.php?do=logout">Log Out</a></li>' . PHP_EOL;
			} else {
				$output .= '				<li '.$accountActive.'><a href="login.php">Login</a></li>' . PHP_EOL;
				$output .= '				<li '.$accountActive.'><a href="register.php">Register</a></li>' . PHP_EOL;
			}
			
			$output .= '			</ul>' . PHP_EOL;
			$output .= '		</div>' . PHP_EOL;
			$output .= ' 		<div class="clear"></div>';
			$output .= '	</div>' . PHP_EOL;
			return $output;
		}
		
		
		private function footerContent() {
			$output = '' . PHP_EOL;
			
			return $output;
		}
		
		public function getSidebarWidgets() {
			$output = '';
			$member = App::getAuthMember();
			
			if(App::checkAuth()) {
				// Show account widget (logged in)
				$output .= '<div class="widget">'.PHP_EOL;
				$output .= '<h4><a href="manageaccount.php?memberId='.$member->getMemberId().'">My Account</a></h4>'.PHP_EOL;
				$output .= '<p>Welcome, '.$member->forename.'.</p>'.PHP_EOL;
				$output .= '</div>'.PHP_EOL;
			} else if($this->title != "Login") {
				// Show login widget (not logged in)
				$output .= '<div class="widget">'.PHP_EOL;
				$output .= '<h4>Login</h4>'.PHP_EOL;
				$output .= '
										<script>
											   $(function() {
												var tooltips = $( "[title]" ).tooltip();
											  });
											   
											  $(function() {
													$("input#submitBtn").button();
												});
  
										</script>
				<form id="registerForm"  method="post" action="login.php?do=login">	
					<table style="width:300px;">
						<tr>
							<td><label for="email">Email Address:</label><br/><input id="email" name="email"  type="text" value="'.$_POST['email'].'"  title="Please use the one you signed up with" /></td>
						</tr>
						<tr>
							<td><label for="password1">Password:</label><br/><input id="password" name="password" type="password" title="" value="'.$_POST['password'].'" /></td>
						</tr>	
						
						<tr><td><input type="submit" id="submitBtn" value="Log In" /></td></tr>
					</table>
				</form>'.PHP_EOL;
				$output .= '</div>'.PHP_EOL;
			}
			
			// $output .= '<div class="widget">'.PHP_EOL;
			// $output .= '<h4>My Account</h4>'.PHP_EOL;
			// $output .= '<p>Account details are here...</p>'.PHP_EOL;
			// $output .= '</div>'.PHP_EOL;
			
			if($member->isAdmin) {
				$output .= '<div class="widget">'.PHP_EOL;
				$output .= '<h4>Admin Controls</h4>'.PHP_EOL;
				$output .= '<ul>'.PHP_EOL;
					$output .= '<li><a href="managemovies.php">Manage Movies</a></li>'.PHP_EOL;
					$output .= '<li><a href="managescreenings.php">Manage Screenings</a></li>'.PHP_EOL;
					$output .= '<li><a href="managescreens.php">Manage Screens</a></li>'.PHP_EOL;
					$output .= '<li><a href="managemisc.php">Manage Directors, Genre &amp; Languages</a></li>'.PHP_EOL;
					$output .= '<li>Manage Members</li>'.PHP_EOL;
				$output .= '</ul>'.PHP_EOL;
				$output .= '</div>'.PHP_EOL;
			}
			return $output;
		}
		
		public function getFooter() {
			$output = '' .PHP_EOL;
			$output .= '					</div>'.PHP_EOL;
			$output .= '					<div id="sidebar">'.PHP_EOL;
			$output .= 	$this->getSidebarWidgets();
			
			
			$output .= '					</div>'.PHP_EOL;
			$output .= '					<div class="clear"></div>'.PHP_EOL;
			$output .= '				</div>'.PHP_EOL;
			$output .= '				<div id="pagebottom"></div>	'.PHP_EOL;
		$output .= '					<div id="footer">'.PHP_EOL;
		$output .= 					$this->footerContent();
		$output .= '					</div>'.PHP_EOL;
			$output .= '			</div>'.PHP_EOL;
			$output .= '		</body>'.PHP_EOL;
			$output .= '	</html>'.PHP_EOL;
			
			echo $output;
		}	

		public function addHeadJS($js) {
			$this->headJS .= $js;
		}
		
		public function error($err) {
			print '<div class="ui-state-error ui-corner-all">
						<span class="ui-icon ui-icon-alert" style="float:left;margin:2px 5px 0 0;"></span>
						<span>'.$err.'</span>
				   </div>';
		}
		
	}

	
?>
