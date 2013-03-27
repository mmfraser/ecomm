<?php
	require_once('App.php');
	require_once('Classes/MovieReservation.php');
	
	$pge = new Page("Movies");	
	$pge->getHeader(); 

	if(!App::getAuthMember()) {
		App::fatalError($pge, "This page is restricted.  You must be logged in to view an account.");
	}
?>
<h2>My Account</h2>	
<h3>Current Reservations</h3>

<?php 
	$reservationArr = App::getDB()->getArrayFromDB("SELECT ReservationID from movie_reservation mr left join movie_screening ms on mr.MovieScreeningID = ms.ScreeningID where mr.MemberID = ".App::getAuthMember()->getMemberId()." AND ms.Date >= NOW() order by ms.Date asc");

	foreach($reservationArr as $arr) {
		$reservation = new MovieReservation();
		$reservation->populateId($arr['ReservationID']);
		
		print $reservation->GetReservationTicket();
		
	}
	
	$pge->getFooter();
?>
