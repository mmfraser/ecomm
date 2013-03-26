<?php
	require_once('App.php');
	require_once('Classes/MovieScreening.php');
	require_once('Classes/Movie.php');
	require_once('Classes/Screen.php');
	$pge = new Page("Admin - Manage Screening");	
	$pge->getHeader(); 

	if(!App::getAuthMember()->isAdmin) {
		App::fatalError($pge, "This page is restricted.  You must be an administrator to access it.");
	}
?>
<h2>Manage Screening</h2>
<?php
	if(isset($_GET['do']) && $_GET['do'] == "create") {
	  $MovieID = $_POST['MovieID'];
  	  $ScreenID = $_POST['ScreenID'];
	  $Date = $_POST['Date'];
	  $Time = $_POST['Time'];
	  
	  

	  try {
		$newScreening = new MovieScreening($MovieID, $ScreenID, $Date, $Time);
		$newScreening->save();
		
		App::infoAlert("Screening added");
		
	  } catch(Exception $e) {
		App::nonFatalError($e->getMessage());
	  }
	}
	
	$screeningArr = App::getDB()->getArrayFromDB("SELECT ScreeningID FROM Movie_Screening");
	
		foreach($screeningArr as $arr) {
			$screening = new MovieScreening();
			$screening->populateId($arr['ScreeningID']);
					
			$movie = new Movie();
			$movie->populateId($screening->MovieID);
			
			$screeningHtml .= "<tr id=\"".$arr['ScreeningID']."\">" . PHP_EOL;
			$screeningHtml .= "	<td>".$arr['ScreeningID']."</td>" . PHP_EOL;
			$screeningHtml .= "	<td>".$movie->Name."</td>" . PHP_EOL;
			$screeningHtml .= "	<td>".$screening->ScreenID."</td>" . PHP_EOL;
			$screeningHtml .= "	<td>".$screening->Date."</td>" . PHP_EOL;
			$screeningHtml .= "	<td>".$screening->Time."</td>" . PHP_EOL;
			//$screeningHtml .= '	<td class="options" style=""><a href="managescreens.php?do=modify&amp;screenID='.$arr['ScreenID'].'" title="Modify Screen"><span class="ui-icon ui-icon-pencil"></span><a title="Delete Screen" id="deleteScreen"><span class="ui-icon ui-icon-trash"></span></a></td>';
			$screeningHtml .= "</tr>" . PHP_EOL;
		}
		
		$movieArray = App::getDB()->getArrayFromDB("SELECT MovieID, Name from Movies");
		foreach($movieArray as $row) {
			$selcted = "";
			if($_POST['MovieID'] == $row['MovieID'])
				$selected = "selected";
			$movieDDL .= '<option value="'.$row['MovieID'].'" '.$selected.'>'.$row['Name'].'</option>';
		}
		
			$screenArray = App::getDB()->getArrayFromDB("SELECT ScreenID, NoRows, NoColumns from Screens");
		foreach($screenArray as $row) {
			$selcted = "";
			if($_POST['ScreenID'] == $row['ScreenID'])
				$selected = "selected";
			$screenDDL .= '<option value="'.$row['ScreenID'].'" '.$selected.'>'.$row['ScreenID'].' (Capacity: '.($row['NoRows']*$row['NoColumns']).')</option>';
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


	<legend>Add New Screening</legend>
	<form id="createScreenFrm"  method="post" action="?do=create">	
		<table style="width:300px;">
			<tr>
				<td><label for="MovieID">Movie:</label></td>
				<td><select name="MovieID">
						<?=$movieDDL?>
					</select>
				</td>
			</tr>
			<tr>
				<td><label for="ScreenID">Screen:</label></td>
				<td><select name="ScreenID">
						<?=$screenDDL?>
					</select>
				</td>
			</tr>
			
			<script>
			  $(function() {
				$( "#Date" ).datepicker();
			  });
			</script>
			
			<tr>
						<td><label for="Date">Date:</label></td>
						<td><input id="Date" name="Date"  type="text" value="<?=$_POST['Date'] ?>"  title="Enter the date in the format DD/MM/YYYY" /></td>
					</tr>
					
					<tr>
						<td><label for="Time">Time:</label></td>
						<td><input id="Time" name="Time"  type="text" value="<?=$_POST['Time'] ?>"  title="Enter the time in 24 hour clock" /></td>
					</tr>
			
			<tr><td></td><td><input type="submit" id="submitBtn" value="Create" /></td></tr>
		</table>
	</form>
</fieldset>


<table id="screenList">
	<thead>
		<tr>
			<th>Screening ID</th>
			<th>Movie Name</th>
			<th>Screen</th>
			<th>Date</th>
			<th>Time</th>
			<!--<th>Options</th>-->
		</tr>
	</thead>
	<tbody>
		<?=$screeningHtml;?>
	</tbody>
	<tfoot>
	</tfoot>
</table>

<?php	
	$pge->getFooter();
?>