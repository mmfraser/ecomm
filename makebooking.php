<?php
	require_once('App.php');
	require_once('Classes/Movie.php');
	require_once('Classes/MovieScreening.php');
	require_once('Classes/MovieReservation.php');
	$pge = new Page("Make Reservation");	
	$pge->getHeader(); 

	if(!App::getAuthMember()) {
		App::fatalError($pge, "This page is restricted.  You must be logged in to make a booking.");
	}
	
?>

				<script>
   $(function() {
    var tooltips = $( "[title]" ).tooltip();
  });
   
  $(function() {
		$("input#submitBtn").button();
	});
	
	$(function() {
		$("a.button").button();
	});
  
</script>

<?php 	
	if(isset($_GET['movieID'])) {
		$movie = new Movie();
		$movie->populateId($_GET['movieID']);
		print '<h2>Booking movie: '.$movie->Name.'</h2>';
	
		if(isset($_GET['dateSelected'])) {
			if(isset($_GET['Time'])) {
				// Submit to database
				if($_GET['noAdults'] > 0 || $_GET['noChildren'] > 0 || $_GET['noStudents'] > 0 || $_GET['noConcession'] > 0) {
				
					$booking = new MovieReservation(App::getAuthMember()->memberId, $_GET['Time'], $_GET['noAdults'], $_GET['noChildren'], $_GET['noStudents'], $_GET['noConcession']);
					
					print_r($booking);
					$booking->save();	
			
				} else {
				print '<span><a class="button" href="?movieID='.$_GET['movieID'].'&amp;dateSelected='.$_GET['dateSelected'].'">Back</a></span>';
					App::fatalError($pge, "You must reserve at least one ticket.");
					
				}
			
			} else {
				print '<h3>Select Time</h3>';
			
			$timeArray = App::getDB()->getArrayFromDB("SELECT ScreeningID, Time from Movie_Screening WHERE Date = '".mysql_real_escape_string($_GET['dateSelected']) . "' ORDER BY Time asc");
			
				foreach($timeArray as $arr) {
						$timeSelectedDDL .= '<option value="'.$arr['ScreeningID'].'">'.$arr['Time'].'</option>';
				}
				?>
				<form method="GET">
				<input type="hidden" name="movieID"  value="<?=$_GET['movieID']?>">
				<input type="hidden" name="dateSelected"  value="<?=$_GET['dateSelected']?>">
				<table style="width:300px;">
				<tr>
					<td><label for="dateSelected">Date:</label></td>
					<td><input type="text" value="<?=$_GET['dateSelected']?>" disabled />
					</td>
				</tr>
				
				<tr>
					<td><label for="Time">Time:</label></td>
					<td><select name="Time">
							<?=$timeSelectedDDL?>
						</select>
					</td>
				</tr>
				
				<tr>
					<td><label for="noAdults">No Adults:</label></td>
					<td><select name="noAdults">
							<option value="0">0</option>
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
						</select>
					</td>
				</tr>
				
				<tr>
					<td><label for="noChildren">No Children:</label></td>
					<td><select name="noChildren">
							<option value="0">0</option>
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
						</select>
					</td>
				</tr>
				
				<tr>
					<td><label for="noStudent">No Student:</label></td>
					<td><select name="noStudent">
							<option value="0">0</option>
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
						</select>
					</td>
				</tr>
				
				<tr>
					<td><label for="noConcession">No Concession:</label></td>
					<td><select name="noConcession">
							<option value="0">0</option>
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
						</select>
					</td>
				</tr>
				
					<tr><td><span><a class="button" href="?movieID='<?=$_GET['movieID']?>">Back</a></span></td><td><input type="submit" id="submitBtn" value="Next" /></td></tr>
				</table>
				</form>
				
<?php
			}
		
		
		} else {
			// Show date selection form.
			print '<h3>Select Date</h3>';
			
			$screeningArr = App::getDB()->getArrayFromDB("SELECT distinct ScreeningID, Date from Movie_Screening WHERE Date >= NOW() AND MovieID = ".mysql_real_escape_string($_GET['movieID']) . " ORDER BY Date asc");
			
			if(count($screeningArr) != 0) {
				foreach($screeningArr as $arr) {
						$dateSelectedDDL .= '<option value="'.$arr['Date'].'">'.$arr['Date'].'</option>';
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
				
				<form method="GET">
				<input type="hidden" name="movieID"  value="<?=$_GET['movieID']?>">

				<table style="width:300px;">
				<tr>
					<td><label for="dateSelected">Dates available:</label></td>
					<td><select name="dateSelected">
						<?=$dateSelectedDDL ?>
						</select>
					</td>
				</tr>
				
					<tr><td></td><td><input type="submit" id="submitBtn" value="Next" /></td></tr>
				</table>
				</form>
	<?php
			} else {
				App::fatalError($pge, "No screening dates available for this movie.");
			}
		}
	
	} else {
		// Show movie form
		App::fatalError($pge, 'You must first select a movie to book.  Please go back to <a href="movies.php">movies</a> to select one');
	}
	
	$pge->getFooter();
?>