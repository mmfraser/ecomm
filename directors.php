<?php
	require_once('App.php');
	require_once('Classes/Genre.php');
	require_once('Classes/Movie.php');
	require_once('Classes/Director.php');
	require_once('Classes/Language.php');
	$pge = new Page("Movies");	
	$pge->getHeader(); 

	if(!App::getAuthMember()) {
		App::fatalError($pge, "This page is restricted.  You must be logged in to view directors.");
	}
?>
<h2>CineMagic Directors' Database</h2>	
<?php 
	if(!isset($_GET['directorID'])) {
		$directorsArr = App::getDB()->getArrayFromDB("SELECT DirectorID from Directors");
		foreach($directorsArr as $row) {
			$director = new Director();
			$director->populateId($row['DirectorID']);
			print '		<h4><a href="directors.php?directorID='.$row['DirectorID'].'">'.$director->forename.' '.$director->surname.'</a></h4>';
			
		}
	} else {
		$director = new Director();
			$director->populateId($_GET['directorID']);
			print '<h3>'.$director->forename.' '.$director->surname.'</h3>';
			$directorsMovies = App::getDB()->getArrayFromDB("SELECT MovieID from Movies Where DirectorID = ".mysql_real_escape_string($_GET['directorID'])."");
			
			print '<h4>Directors Movies:</h4>';
		if(count($directorsMovies) != 0) {
			foreach($directorsMovies as $arr) {
			$movie = new Movie();
			$movie->populateId($arr['MovieID']);
				print '<div style="width:100%; border: 1px solid #000;">';
				print '		<h4><a href="movies.php?movieFilter='.$arr['MovieID'].'">'.$movie->Name.'</a></h4>';
				print ' 	<p></p>';
				print '		<td>'.$movie->Description.'</td>';
				print '<table style="width:300px;"><tr><td><strong>Release Date:</strong></td><td>'.$movie->ReleaseDate.'</td><td><strong>Duration:</strong></td><td>'.$movie->duration.' mins</td></table>';
				print '</div>';
			}
		} else {
			print '<p>This director currently has no movies in the system.</p>';
		}
			
			
	}
	$pge->getFooter();
?>