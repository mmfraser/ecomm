<?php
	require_once('App.php');
	require_once('Classes/Director.php');
	$pge = new Page("Admin - Manage Directors");	
	$pge->getHeader(); 

	if(!App::getAuthMember()->isAdmin) {
		App::fatalError($pge, "This page is restricted.  You must be an administrator to access it.");
	}
?>
<h2>Manage Directors</h2>
<p>The list of directors below represents all movie directors currently in the system.</p>	

<?php

	
	if(isset($_GET['do']) && $_GET['do'] == "create") {
	  $forename = $_POST['forename'];
  	  $surname = $_POST['surname'];

	  try {
		$newDirector = new Director($forename, $surname);
		$newDirector->save();
		
		App::infoAlert("Director added");
		
	  } catch(Exception $e) {
		App::nonFatalError($e->getMessage());
	  }
	}
	
	$direcotrsArr = App::getDB()->getArrayFromDB("SELECT DirectorID FROM Directors");
	
		
		foreach($direcotrsArr as $arr) {
			$director = new Director(null,null);
			$director->populateId($arr['DirectorID']);
			$directorHtml .= "<tr id=\"".$arr['DirectorID']."\">" . PHP_EOL;
			$directorHtml .= "	<td>".$arr['DirectorID']."</td>" . PHP_EOL;
			$directorHtml .= "	<td>".$director->forename."</td>" . PHP_EOL;
			$directorHtml .= "	<td>".$director->surname."</td>" . PHP_EOL;
			//$screensHtml .= '	<td class="options" style=""><a href="managedirectors.php?do=modify&amp;DirectorID='.$arr['DirectorID'].'" title="Modify Screen"><span class="ui-icon ui-icon-pencil"></span><a title="Delete Screen" id="deleteScreen"><span class="ui-icon ui-icon-trash"></span></a></td>';
			$directorHtml .= "</tr>" . PHP_EOL;
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
<fieldset>


	<legend>Add New Director</legend>
	<form id="createScreenFrm"  method="post" action="?do=create">	
		<table style="width:300px;">
			<tr>
				<td><label for="forename">Forename:</label></td>
				<td><input id="forename" name="forename"  type="text" value="<?=$_POST['forename'] ?>"  title="Enter the director's forename" /></td>
			</tr>
			<tr>
				<td><label for="surname">Surname:</label></td>
				<td><input id="surname" name="surname"  type="text" value="<?=$_POST['surname'] ?>"  title="Enter the number of columns in the cinema" /></td>
			</tr>	
			
			<tr><td></td><td><input type="submit" id="submitBtn" value="Create" /></td></tr>
		</table>
	</form>
</fieldset>


<table id="screenList" style="width:200px;">
	<thead>
		<tr>
			<th>Director ID</th>
			<th>Forename</th>
			<th>Surname</th>
			<!--<th>Options</th>-->
		</tr>
	</thead>
	<tbody>
		<?=$directorHtml;?>
	</tbody>
	<tfoot>
	</tfoot>
</table>

<?php	
	$pge->getFooter();
?>