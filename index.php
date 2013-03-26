<?php
	require_once('App.php');
	
	$pge = new Page("Home");	
	$pge->getHeader(); 

?>
<h2>Welcome to CineMagic, the UK's favourite Cinema company!</h2>	

<?php	
	$pge->getFooter();
?>