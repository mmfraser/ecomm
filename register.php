<?php
	require_once('App.php');
	require_once('Classes/Member.php');

	$pge = new Page("Register");	
	$pge->getHeader(); 
	
		
	if(App::checkAuth()) {
		App::fatalError($pge, "You already have a user account.  Please log out if you wish to create a new one.");
	}
?>
<p>Welcome to CineMagic.  Please complete the form below in order to register to our website; by doing so you'll be able to book tickets online, select your seats, and view details of our members' favourite movies as well as see details of soon to be released movies.</p>	

<p>Your information will be stored securely in our database, and it shall not be shared with any external parties without your prior permission.</p>

<?php

if(isset($_GET['do']) && $_GET['do'] == "register") {
	  $forename = $_POST['forename'];
  	  $surname = $_POST['surname'];
  	  $password = $_POST['password'];
	  $emailAddress = $_POST['email'];
	  $addressLine1 = $_POST['AddressLine1'];
	  $addressLine2 = $_POST['AddressLine2'];
	  $town = $_POST['town'];
	  $postcode = $_POST['postcode'];
	  $password1 = $_POST['password1'];
	  $password2 = $_POST['password2'];
	  	  
	  try {
		  if($password1 == $password2 && !empty($password1)) {
			// Register new user
			$newMember = new Member($forename, $surname, $password1, $emailAddress, $addressLine1, $addressLine2, $town, $postcode ,$isActive = 1, $isAdmin = 0);
			$newMember->save();
			
			// Create session and redirect to home page
			App::authUser($emailAddress, $password1);
			
			$message = "Thank you for registering with CineMagic.  Below are your account details for future reference. \n\n";
			$message .= $newMember->toString();
			
			mail($emailAddress, "CineMagic Registration Details", $message);
			unset($_POST);
			header("Location: index.php");
		  
			
		  } else 
				throw new Exception('Password fields must match and be non-null'); 
				
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
		$("input#submit").button();
	});
  
</script>
<form id="registerForm"  method="post" action="?do=register">	
	<table style="width:300px;">
		<tr>
			<td><label for="forename">Forename:</label></td>
			<td><input id="forename" name="forename" type="text" title="" value="<?=$_POST['forename'] ?>" /></td>
		</tr>
		<tr>
			<td><label for="surname">Surname:</label></td>
			<td><input id="surname" name="surname" type="text" title="" value="<?=$_POST['surname'] ?>"  /></td>
		</tr>
		<tr>
			<td><label for="email">Email Address:</label></td>
			<td><input id="email" name="email"  type="text" value="<?=$_POST['email'] ?>"  title="Please enter this info so we can contact you!" /></td>
		</tr>
		<tr>
			<td><label for="AddressLine1">Address:</label></td>
			<td><input id="AddressLine1" name="AddressLine1" type="text" title="Address Line 1" value="<?=$_POST['AddressLine1'] ?>" /><br/>
				<input id="AddressLine2" name="AddressLine2" type="text" title="Address Line 2" value="<?=$_POST['AddressLine2'] ?>" /></td>
		</tr>
		<tr>
			<td><label for="town">Town:</label></td>
			<td><input id="town" name="town" type="text" title="" value="<?=$_POST['town'] ?>" /></td>
		</tr>
		<tr>
			<td><label for="email">Postcode:</label></td>
			<td><input id="postcode" name="postcode" type="text" title="" value="<?=$_POST['postcode'] ?>" /></td>
		</tr>
		<tr>
			<td><label for="password1">Password:</label></td>
			<td><input id="password1" name="password1" type="password" title="" value="<?=$_POST['password1'] ?>" /></td>
		</tr>
		<tr>
			<td><label for="password2">Password<br/>(Please confirm):</label></td>
			<td><input id="password2" name="password2" title="" type="password" value="<?=$_POST['password2'] ?>" /></td>
		</tr>
		
		
		<tr><td></td><td><input type="submit" id="submitBtn" value="Register" /></td></tr>
	</table>
</form>
	
<p><em>Note: all field are required</em></p>
	
<?php	
	$pge->getFooter();
		
		


?>