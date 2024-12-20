<?php 

require_once __DIR__.'/../../boot/boot.php';

use Hotel\Review;
use Hotel\User;
use Hotel\Room;

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

$review = new Review();
// Add new review for room
$status = $review->insert($roomId, User::getCurrentUserId(), $_REQUEST['rate'], $_REQUEST['comment']);
$roomReviews = [];
$roomAvgReviews = 0;
if($status) {
	$roomReviews = $review->getRoomReviews($roomId);
	$room = new Room();
	$roomAvgReviews = $room->getRoomAvgReviews($roomId);
}
echo json_encode([
	'status' => $status,
	'reviews' => $roomReviews,
	'roomAvg' => $roomAvgReviews['avg_reviews'],
]);
