<?php
	require_once('App.php');

	$pge = new Page("Login");	
	$pge->getHeader(); 
	
	if(App::checkAuth()) {
		App::fatalError($pge, "You are already logged in!");
	}
?>
<h2>Welcome to CineMagic</h2>
<p>  Please complete the form below in order to log in to our website; by doing so you'll be able to book tickets online, select your seats, and view details of our members' favourite movies as well as see details of soon to be released movies.</p>	

<?php

if(isset($_GET['do']) && $_GET['do'] == "login") {
	  $emailAddress = $_POST['email'];
  	  $password = $_POST['password'];

	  try {
			// Register new user
			if(App::authUser($emailAddress, $password)) {
				header("Location: index.php");
			} else 
				throw new Exception("Username and password combination entered is incorrect.");

	  } catch(Exception $e) {
			App::nonFatalError($e->getMessage());
	  }
	}
?>

<script>
   $(function() {
    var tooltips = $( "[title]" ).tooltip();
  });
   
  $(function() {
		$("input#submitBtn").button();
	});
  
</script>
<form id="registerForm"  method="post" action="?do=login">	
	<table style="width:300px;">
		<tr>
			<td><label for="email">Email Address:</label></td>
			<td><input id="email" name="email"  type="text" value="<?=$_POST['email'] ?>"  title="Please use the one you signed up with" /></td>
		</tr>
		<tr>
			<td><label for="password1">Password:</label></td>
			<td><input id="password" name="password" type="password" title="" value="<?=$_POST['password'] ?>" /></td>
		</tr>	
		
		<tr><td></td><td><input type="submit" id="submitBtn" value="Log In" /></td></tr>
	</table>
</form>
	
<p><em>Note: all field are required</em></p>
	
<?php	
	$pge->getFooter();
		
		


?>