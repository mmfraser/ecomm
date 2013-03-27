<?php
	require_once('App.php');
	require_once('Classes/Genre.php');
	require_once('Classes/Movie.php');
	require_once('Classes/Director.php');
	require_once('Classes/Language.php');
	$pge = new Page("Movies");	
	$pge->getHeader(); 

?>
<h2>CineMagic Movie Database</h2>	

<div class="container">
	<div class="moviesLeft">
		<h4>Latest Releases</h4>
		<ul>
		<?php 
			$newReleases = App::getDB()->getArrayFromDB("SELECT MovieID from Movies WHERE ReleaseDate <= NOW() order by ReleaseDate desc limit 10");
			foreach($newReleases as $arr) {
				$movie = new Movie();
				$movie->populateId($arr['MovieID']);
				print '	<li><a class="movieLink" href="movies.php?movieFilter='.$arr['MovieID'].'">'.$movie->Name.'</a> <span class="movieDate">'.$movie->ReleaseDate.'</span></li>';
			}	
		?>
		</ul>
	</div>
	
	<div class="moviesLeft">
		<h4>Upcoming Releases</h4>
		<ul>
		<?php 
			$upcomingReleases = App::getDB()->getArrayFromDB("SELECT MovieID from Movies WHERE ReleaseDate > NOW() order by ReleaseDate desc limit 10");
			foreach($upcomingReleases as $arr) {
				$movie = new Movie();
				$movie->populateId($arr['MovieID']);
				print '	<li><a class="movieLink" href="movies.php?movieFilter='.$arr['MovieID'].'">'.$movie->Name.'</a> <span class="movieDate">'.$movie->ReleaseDate.'</span></li>';
			}	
		?>
		</ul>
	</div>
	
	<div class="moviesLeft">
		<h4>Genre Selection</h4>
		<ul>
		<?php 
			$genreArr = App::getDB()->getArrayFromDB("SELECT GenreID from Genre");
			foreach($genreArr as $arr) {
				$genre = new Genre();
				$genre->populateId($arr['GenreID']);
				print '	<li><a href="movies.php?genreFilter='.$arr['GenreID'].'">'.$genre->Name.'</a></li>';
			}	
		?>
		</ul>
	</div>
</div>

<div class="movieMain">

<?php

	if(!isset($_GET['movieFilter']) && !isset($_GET['genreFilter'])) {
?>
	<h3>Movies</h3>
	<p>Use the search bar below to find your favourite movies, or alternatively select a genre and take it from there!</p>
	
	<script>
   $(function() {
    var tooltips = $( "[title]" ).tooltip();
  });
   
  $(function() {
		$("input#submitBtn").button();
	});
  
</script>
	
	<form id="searchMovieFrm"  method="post" action="?do=search">	
		<table style="width:300px;">
			<tr>
				<td><label for="MovieID">Movie Name:</label></td>
				<td><input id="nameSearch" name="nameSearch"  type="text" value="<?=$_POST['nameSearch'] ?>"  title="Enter a full or partial name." />
				</td>
			</tr>
			<tr><td></td><td><input type="submit" id="submitBtn" value="Search" /></td></tr>
		</table>
	</form>
	

	
<?php 
		if(isset($_GET['do']) && $_GET['do'] == "search" && !empty($_POST['nameSearch'])) {
		
		
			$searchArr = App::getDB()->getArrayFromDB("SELECT MovieID from Movies WHERE Name like '%".mysql_real_escape_string($_POST['nameSearch'])."%'");
			
			
			if(count($searchArr) != 0) {
				foreach($searchArr as $arr) {
				$movie = new Movie();
				$movie->populateId($arr['MovieID']);
					print '		<h4><a href="movies.php?movieFilter='.$arr['MovieID'].'">'.$movie->Name.'</a></h4>';
					print ' 	<p></p>';
					print '		<td>'.$movie->Description.'</td>';
					print '<table><tr><td><strong>Director:</strong></td><td>'.$movie->DirectorID.'</td><td><strong>Release Date:</strong></td><td>'.$movie->ReleaseDate.'</td><td><strong>Duration:</strong></td><td>'.$movie->duration.' mins</td></table>';
				}
		} else {
			print '<p>There are currently no movies which match your search.</p>';
		}
			
			
			print_r($screeningsArr);
		}

	} else if(isset($_GET['movieFilter'])) {
		$movie = new Movie();
		$movie->populateId($_GET['movieFilter']);
		
		$director = new Director();
		$director->populateId($movie->DirectorID);
		
		$genre = new Genre();
		$genre->populateId($movie->GenreID);
		
		$language = new Language();
		$language->populateId($movie->LanguageID);
		
		print '<h3>'.$movie->Name.'</h3>';
		
		print '<h5>Director:</h5>';
		print '<p><a href="directors.php?directorID='.$movie->DirectorID.'">'.$director->forename.' '.$director->surname.'</a></p>';
		
		print '<h5>Genre:</h5>';
		print '<p><a href="movieFilter.php?genreFilter='.$movie->GenreID.'">'.$genre->Name.'</a></p>';
		
		print '<h5>Cast:</h5>';
		print '<p>'.$movie->Cast.'</p>';
		
		print '<h5>Language:</h5>';
		print '<p>'.$language->Name.'</p>';
		
		$screeningsArr = App::getDB()->getArrayFromDB("SELECT distinct Date from Movie_Screening WHERE MovieID = ".$_GET['movieFilter']." AND Date >= NOW()");
		
		print '<h5>Screenings:</h5>';
		if(count($screeningsArr) != 0) {
			print '<ul class="screeningTime">';
			foreach($screeningsArr as $arr) {
				print '	<li><a href="makebooking.php?movieID='.$_GET['movieFilter'].'&amp;dateSelected='.$arr['Date'].'">'.$arr['Date'].'</a></li>';
			}	
			
			print '</ul>';
		} else {
			print "<p>There are currently no screenings set for this movie.  Please check back soon.";
		}
	} else {
		
		$genre = new Genre();
		$genre->populateId($_GET['genreFilter']);
	
		print '<h3>Genre: '.$genre->Name.'</h3>';
		print '<p>'.$genre->Description.'</p>';
		
		$movieArr = App::getDB()->getArrayFromDB("SELECT MovieID from Movies where GenreID = '".mysql_real_escape_string($_GET['genreFilter'])."'");
		
		
		if(count($movieArr) != 0) {
		
				foreach($movieArr as $arr) {
				$movie = new Movie();
				$movie->populateId($arr['MovieID']);
					print '		<h4><a href="movies.php?movieFilter='.$arr['MovieID'].'">'.$movie->Name.'</a></h4>';
					print ' 	<p></p>';
					print '		<td>'.$movie->Description.'</td>';
					print '<table><tr><td><strong>Director:</strong></td><td>'.$movie->DirectorID.'</td><td><strong>Release Date:</strong></td><td>'.$movie->ReleaseDate.'</td><td><strong>Duration:</strong></td><td>'.$movie->duration.' mins</td></table>';
				}
		} else {
			print '<p>There are currently no movies in this genre</p>';
		}
	}
	
?>
</div>

<?php	
	$pge->getFooter();
?>