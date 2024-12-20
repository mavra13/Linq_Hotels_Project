<?php 

require_once __DIR__.'/../../boot/boot.php';

use Hotel\Booking;

if (strtolower($_SERVER['REQUEST_METHOD']) != 'post') {
	die;
}

$roomId = $_REQUEST['room_id'];
$checkInDate = $_REQUEST['checkInDate'];
$checkOutDate = $_REQUEST['checkOutDate'];
if(empty($roomId) || empty($checkInDate) || empty($checkOutDate)) {
	die;
}
$booking = new Booking();
$isAvailable = $booking->roomAvailable($roomId,new DateTime($checkInDate),new DateTime($checkOutDate));

echo json_encode([
	'is_available' => $isAvailable,
]);
