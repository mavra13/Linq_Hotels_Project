<?php

require __DIR__.'/../../boot/boot.php';

use Hotel\User;
use Hotel\Room;

$room = new Room();

$searchTerms = [
	'city' => $_REQUEST['city'],
	'roomType' => $_REQUEST['roomType'],
	'checkInDate' => $_REQUEST['checkInDate'],
	'checkOutDate' => $_REQUEST['checkOutDate'],
	'guests' => $_REQUEST['guests'],
	'minPrice' => $_REQUEST['minPrice'],
	'maxPrice' => $_REQUEST['maxPrice'],
];

$searchResults = $room->searchRoom($searchTerms['city'], new DateTime($searchTerms['checkInDate']), new DateTime($searchTerms['checkOutDate']), $searchTerms['roomType'], $searchTerms['guests'], $searchTerms['minPrice'], $searchTerms['maxPrice']);
echo json_encode($searchResults);

?>
