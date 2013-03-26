<?php
	require_once('App.php');
	require_once('Classes/Director.php');
	require_once('Classes/Genre.php');
	require_once('Classes/Language.php');
	
	$pge = new Page("Admin - Manage Cinema");	
	$pge->getHeader(); 

	if(!App::getAuthMember()->isAdmin) {
		App::fatalError($pge, "This page is restricted.  You must be an administrator to access it.");
	}
?>

<?php
	if(isset($_GET['do']) && $_GET['do'] == "createDirector") {
	  $forename = $_POST['forename'];
  	  $surname = $_POST['surname'];

	  try {
		$newDirector = new Director($forename, $surname);
		$newDirector->save();
		
		App::infoAlert("Director added");
		unset($_POST);
		unset($_GET);
	  } catch(Exception $e) {
		App::nonFatalError($e->getMessage());
	  }
	} else if(isset($_GET['do']) && $_GET['do'] == "createGenre") {
		  $Name = $_POST['Name'];
		  $Description = $_POST['Description'];

		  try {
			$newGenre= new Genre($Name, $Description);
			$newGenre->save();
			
			App::infoAlert("Genre added");
			unset($_POST);
			unset($_GET);
		  } catch(Exception $e) {
			App::nonFatalError($e->getMessage());
		  }
	} else if(isset($_GET['do']) && $_GET['do'] == "createLanguage") {
		  $Name = $_POST['Name'];
		  try {
			$newLanguage = new Language($Name);
			$newLanguage->save();
			
			App::infoAlert("Language added");
			unset($_POST);
			unset($_GET);
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

	$genreArr = App::getDB()->getArrayFromDB("SELECT GenreID FROM Genre");
	foreach($genreArr as $arr) {
		$genre = new Genre(null,null);
		$genre->populateId($arr['GenreID']);
		$genreHtml .= "<tr id=\"".$arr['GenreID']."\">" . PHP_EOL;
		$genreHtml .= "	<td style=\"width:15%\">".$arr['GenreID']."</td>" . PHP_EOL;
		$genreHtml .= "	<td style=\"width:30%\">".$genre->Name."</td>" . PHP_EOL;
		$genreHtml .= "	<td style=\"width:65%\">".$genre->Description."</td>" . PHP_EOL;
		//$screensHtml .= '	<td class="options" style=""><a href="managedirectors.php?do=modify&amp;GenreID='.$arr['GenreID'].'" title="Modify Screen"><span class="ui-icon ui-icon-pencil"></span><a title="Delete Screen" id="deleteScreen"><span class="ui-icon ui-icon-trash"></span></a></td>';
		$genreHtml .= "</tr>" . PHP_EOL;
	}
	
	$languageArr = App::getDB()->getArrayFromDB("SELECT LanguageID FROM Languages");
	foreach($languageArr as $arr) {
		$language = new Language(null,null);
		$language->populateId($arr['LanguageID']);
		$languageHtml .= "<tr id=\"".$arr['LanguageID']."\">" . PHP_EOL;
		$languageHtml .= "	<td>".$arr['LanguageID']."</td>" . PHP_EOL;
		$languageHtml .= "	<td>".$language->Name."</td>" . PHP_EOL;
		//$screensHtml .= '	<td class="options" style=""><a href="managedirectors.php?do=modify&amp;LanguageID='.$arr['LanguageID'].'" title="Modify Screen"><span class="ui-icon ui-icon-pencil"></span><a title="Delete Screen" id="deleteScreen"><span class="ui-icon ui-icon-trash"></span></a></td>';
		$languageHtml .= "</tr>" . PHP_EOL;
	}
	
?>

<script>
   $(function() {
    var tooltips = $( "[title]" ).tooltip();
  });
   
  $(function() {
		$("input#submitBtn").button();
		$( "#accordion" ).accordion();
	});
  
  
  
</script>

<div id="accordion">
	<h3>Manage Movie Directors</h3>
	<div>
		<p>The list of directors below represents all movie directors currently in the system.</p>	

		<fieldset>
			<legend>Add New Director</legend>
			<form id="createScreenFrm"  method="post" action="?do=createDirector">	
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
		</table>

	</div>

	<h3>Manage Movie Genres</h3>
	<div>
		<p>The list of genres below represents all genres currently in the system.</p>	
		<fieldset>
			<legend>Add New Genre</legend>
			<form id="createGenreFrm"  method="post" action="?do=createGenre">	
				<table style="width:300px;">
					<tr>
						<td><label for="Name">Name:</label></td>
						<td><input id="Name" name="Name"  type="text" value="<?=$_POST['Name'] ?>"  title="Enter the genre name" /></td>
					</tr>
					<tr>
						<td><label for="Description">Description:</label></td>
						<td><input id="Description" name="Description"  type="text" value="<?=$_POST['Description'] ?>"  title="Enter genre description" /></td>
					</tr>	
					
					<tr><td></td><td><input type="submit" id="submitBtn" value="Create" /></td></tr>
				</table>
			</form>
		</fieldset>

		<table id="screenList" style="">
			<thead>
				<tr>
					<th >Genre ID</th>
					<th style="width:30%">Genre Name</th>
					<th style="width:65%">Genre Description</th>
					<!--<th>Options</th>-->
				</tr>
			</thead>
			<tbody>
				<?=$genreHtml;?>
			</tbody>
		</table>
	</div>
	
	<h3>Manage Movie Languages</h3>
	<div>
	<p>The list of languages below represents all languages currently in the system.</p>	
		<fieldset>
			<legend>Add New Language</legend>
			<form id="createLanguageFrm"  method="post" action="?do=createLanguage">	
				<table style="width:300px;">
					<tr>
						<td><label for="Name">Name:</label></td>
						<td><input id="Name" name="Name"  type="text" value="<?=$_POST['Name'] ?>"  title="Enter the language name" /></td>
					</tr>
										
					<tr><td></td><td><input type="submit" id="submitBtn" value="Create" /></td></tr>
				</table>
			</form>
		</fieldset>

		<table id="screenList" style="width:300px;">
			<thead>
				<tr>
					<th>Language ID</th>
					<th>Language Name</th>
					<!--<th>Options</th>-->
				</tr>
			</thead>
			<tbody>
				<?=$languageHtml;?>
			</tbody>
		</table>
	</div>
	
	</div>
<?php	
	$pge->getFooter();
?>