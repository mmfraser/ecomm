<?php
	require_once('App.php');
	
	$pge = new Page("Home");	
	$pge->getHeader(); 

?>
<h2>Welcome to CineMagic, the UK's favourite Cinema company!</h2>	

<?php 

	if(!App::checkAuth()) {
		// User not logged in
		print '<p>In order to view the latest movie details, and also make reservations, please log in.  If you don\'t already have an account, please register:</p>'; 
		
		?>
		<script>
   $(function() {
    var tooltips = $( "[title]" ).tooltip();
  });
   
  $(function() {
		$("input#submit").button();
	});
  
</script>
<form id="registerForm"  method="post" action="register.php?do=register">	
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
	} else {
		print '<p>Hi ' . App::getAuthMember()->forename . '.</p>';
		print '<p>Please use the controls at the top of the page to make movie reservations. </p>';
	}

	$pge->getFooter();
?>