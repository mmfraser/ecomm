<?php
	require_once('App.php');
	require_once('Classes/Movie.php');
	require_once('Classes/Genre.php');
	require_once('Classes/Language.php');
	require_once('Classes/Director.php');
	
	$pge = new Page("Admin - Manage Movies");	
	$pge->getHeader(); 

	if(!App::getAuthMember()->isAdmin) {
		App::fatalError($pge, "This page is restricted.  You must be an administrator to access it.");
	}
?>
<h2>Manage Movies</h2>
<p>The list of movies below represents all movies currently in the system.</p>	

<?php

	
		// private $MovieID;
  	// public $Name;
	// public $Description;
	// public $Cast;
	// private $GenreID;
	// private $DirectorID;
	// private $LanguageID;
  	// public $isLoaded;
	
	if(isset($_GET['do']) && $_GET['do'] == "create") {
	  $Name = $_POST['Name'];
  	  $Description = $_POST['Description'];
	  $Cast = $_POST['Cast'];
	  $GenreID = $_POST['GenreID'];
	  $DirectorID = $_POST['DirectorID'];
	  $LanguageID = $_POST['LanguageID'];
	  $ReleaseDate = $_POST['ReleaseDate'];
	  $duration = $_POST['duration'];

	  try {
		$newMovie = new Movie($Name, $Description, $Cast, $GenreID, $DirectorID, $LanguageID, $ReleaseDate, $duration);
		$newMovie->save();
		
		App::infoAlert("Movie added");
		
	  } catch(Exception $e) {
		App::nonFatalError($e->getMessage());
	  }
	}
	
	$moviesArr = App::getDB()->getArrayFromDB("SELECT MovieID FROM Movies");
	
	foreach($moviesArr as $arr) {
			$movie = new Movie(null,null,null,null,null,null, null);
			$movie->populateId($arr['MovieID']);
			
			$director = new Director(null, null);
			$director->populateId($movie->DirectorID);
			
			$genre = new Genre(null, null);
			$genre->populateId($movie->GenreID);
			
			$language = new Language(null);
			$language->populateId($movie->LanguageID);
			
			$movieHtml .= "<tr id=\"".$arr['MovieID']."\">" . PHP_EOL;
			$movieHtml .= "	<td>".$arr['MovieID']."</td>" . PHP_EOL;
			$movieHtml .= "	<td>".$movie->Name."</td>" . PHP_EOL;
			$movieHtml .= "	<td>".$movie->Description."</td>" . PHP_EOL;
			$movieHtml .= "	<td>".$movie->Cast."</td>" . PHP_EOL;
			$movieHtml .= "	<td>".$movie->ReleaseDate."</td>" . PHP_EOL;
			$movieHtml .= "	<td>".$movie->duration."</td>" . PHP_EOL;
			$movieHtml .= "	<td>".$director->forename." ".$director->surname."</td>" . PHP_EOL;
			$movieHtml .= "	<td>".$genre->Name."</td>" . PHP_EOL;
			$movieHtml .= "	<td>".$language->Name."</td>" . PHP_EOL;
			//$screensHtml .= '	<td class="options" style=""><a href="managedirectors.php?do=modify&amp;DirectorID='.$arr['DirectorID'].'" title="Modify Screen"><span class="ui-icon ui-icon-pencil"></span><a title="Delete Screen" id="deleteScreen"><span class="ui-icon ui-icon-trash"></span></a></td>';
			$movieHtml .= "</tr>" . PHP_EOL;
		}

	$genreArray = App::getDB()->getArrayFromDB("SELECT GenreID, Name from Genre");
	foreach($genreArray as $row) {
		$genreDDL .= '<option value="'.$row['GenreID'].'">'.$row['Name'].'</option>';
	}

	$directorArray = App::getDB()->getArrayFromDB("SELECT DirectorID, forename, surname from Directors");
	foreach($directorArray as $row) {
		$directorDDL .= '<option value="'.$row['DirectorID'].'">'.$row['forename'].' '.$row['surname'].'</option>';
	}
	
	$languageArray = App::getDB()->getArrayFromDB("SELECT LanguageID, Name from Languages");
	foreach($languageArray as $row) {
		$languageDDL .= '<option value="'.$row['LanguageID'].'">'.$row['Name'].'</option>';
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
	<legend>Add New Movie</legend>
	<form id="createScreenFrm"  method="post" action="?do=create">	
		<table style="width:300px;">
			<tr>
				<td><label for="Name">Name:</label></td>
				<td><input id="Name" name="Name"  type="text" value="<?=$_POST['Name'] ?>"  title="" /></td>
			</tr>
			<tr>
				<td><label for="Description">Description:</label></td>
				<td><textarea id="Description" name="Description"  type="text" value="<?=$_POST['Description'] ?>"  title=""></textarea></td>
			</tr>	
			<tr>
				<td><label for="Cast">Cast:</label></td>
				<td><textarea id="Cast" name="Cast"  type="text" value="<?=$_POST['Cast'] ?>"  title=""></textarea></td>
			</tr>	
			<tr>
			<script>
			  $(function() {
				$( "#ReleaseDate" ).datepicker({ dateFormat: 'yy-mm-dd' });
			  });
			</script>
				<td><label for="ReleaseDate">Release Date:</label></td>
				<td><input id="ReleaseDate" name="ReleaseDate"  type="text" value="<?=$_POST['ReleaseDate'] ?>"  title="" /></td>
			</tr>
			<tr>
				<td><label for="duration">Duration:</label></td>
				<td><input id="duration" name="duration"  type="text" value="<?=$_POST['duration'] ?>"  title="In hours" /></td>
			</tr>
			<tr>
				<td><label for="GenreID">Genre:</label></td>
				<td><select name="GenreID">
						<?=$genreDDL?>
					</select>
				</td>
			</tr>	
			<tr>
				<td><label for="DirectorID">Director:</label></td>
				<td><select name="DirectorID">
						<?=$directorDDL?>
					</select>
				</td>
			</tr>
			<tr>
				<td><label for="LanguageID">Language:</label></td>
				<td><select name="LanguageID">
						<?=$languageDDL?>
					</select>
				</td>
			</tr>
			<tr><td></td><td><input type="submit" id="submitBtn" value="Create" /></td></tr>
		</table>
	</form>
</fieldset>


<table id="screenList">
	<thead>
		<tr>
			<th>Movie ID</th>
			<th>Name</th>
			<th>Description</th>
			<th>Cast</th>
			<th>Release Date</th>
			<th>Duration</th>
			<th>Director</th>
			<th>Genre</th>
			<th>Language</th>
			<!--<th>Options</th>-->
		</tr>
	</thead>
	<tbody>
		<?=$movieHtml;?>
	</tbody>
	<tfoot>
	</tfoot>
</table>

<?php	
	$pge->getFooter();
?>