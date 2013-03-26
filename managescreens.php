<?php
	require_once('App.php');
	require_once('Classes/Screen.php');
	$pge = new Page("Admin - Manage Screens");	
	$pge->getHeader(); 

	if(!App::getAuthMember()->isAdmin) {
		App::fatalError($pge, "This page is restricted.  You must be an administrator to access it.");
	}
?>
<h2>Manage Screens</h2>
<p>The list of screens below represents all available screens in the cinema.</p>	

<?php
	if(isset($_GET['do']) && $_GET['do'] == "create") {
	  $NoRows = $_POST['NoRows'];
  	  $NoColumns = $_POST['NoColumns'];

	  try {
		$newScreen = new Screen($NoRows, $NoColumns);
		$newScreen->save();
		
		App::infoAlert("Screen added");
		
	  } catch(Exception $e) {
		App::nonFatalError($e->getMessage());
	  }
	}
	
	$screensArr = App::getDB()->getArrayFromDB("SELECT ScreenID FROM Screens");
	
		
		foreach($screensArr as $arr) {
			$screen = new Screen(null,null);
			$screen->populateId($arr['ScreenID']);
			$screensHtml .= "<tr id=\"".$arr['ScreenID']."\">" . PHP_EOL;
			$screensHtml .= "	<td>".$arr['ScreenID']."</td>" . PHP_EOL;
			$screensHtml .= "	<td>".$screen->NoRows."</td>" . PHP_EOL;
			$screensHtml .= "	<td>".$screen->NoColumns."</td>" . PHP_EOL;
			//$screensHtml .= '	<td class="options" style=""><a href="managescreens.php?do=modify&amp;screenID='.$arr['ScreenID'].'" title="Modify Screen"><span class="ui-icon ui-icon-pencil"></span><a title="Delete Screen" id="deleteScreen"><span class="ui-icon ui-icon-trash"></span></a></td>';
			$screensHtml .= "</tr>" . PHP_EOL;
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


	<legend>Add New Screen</legend>
	<form id="createScreenFrm"  method="post" action="?do=create">	
		<table style="width:300px;">
			<tr>
				<td><label for="NoRows">No. Rows:</label></td>
				<td><input id="NoRows" name="NoRows"  type="text" value="<?=$_POST['NoRows'] ?>"  title="Enter the number of rows in the cinema" /></td>
			</tr>
			<tr>
				<td><label for="password1">No. Columns:</label></td>
				<td><input id="NoColumns" name="NoColumns"  type="text" value="<?=$_POST['NoColumns'] ?>"  title="Enter the number of columns in the cinema" /></td>
			</tr>	
			
			<tr><td></td><td><input type="submit" id="submitBtn" value="Create" /></td></tr>
		</table>
	</form>
</fieldset>


<table id="screenList" style="width:200px;">
	<thead>
		<tr>
			<th>Screen ID</th>
			<th>No Rows</th>
			<th>No Columns</th>
			<!--<th>Options</th>-->
		</tr>
	</thead>
	<tbody>
		<?=$screensHtml;?>
	</tbody>
	<tfoot>
	</tfoot>
</table>

<?php	
	$pge->getFooter();
?>