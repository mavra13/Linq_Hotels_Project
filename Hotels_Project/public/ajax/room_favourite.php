<?php 

require_once __DIR__.'/../../boot/boot.php';

use Hotel\Favourite;
use Hotel\User;

if (strtolower($_SERVER['REQUEST_METHOD']) != 'post') {
	die;
}

if(empty(User::getCurrentUserId())) {
	die;
}

$roomId = $_REQUEST['room_id'];
if(empty($roomId)) {
	die;
}

// Verify csrf
$csrf = $_REQUEST['csrf'];
if(empty($csrf) || !User::verifyCsrf($csrf)) {
	die;
}

$favourite = new Favourite();
// Add or remove room from favourites
$isFavourite = $_REQUEST['is_favourite'];
if(!$isFavourite){
	$status = $favourite->addToFavourites($roomId,User::getCurrentUserId());
} else {
	$status = $favourite->removeFromFavourites($roomId,User::getCurrentUserId());
}

echo json_encode([
	'status' => $status,
	'is_favourite' => !$isFavourite,
]);
