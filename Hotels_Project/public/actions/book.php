<?php 

require_once __DIR__.'/../../boot/boot.php';

use Hotel\User;
use Hotel\Booking;

if (strtolower($_SERVER['REQUEST_METHOD']) != 'post') {
	header('Location: /');
	return;
}

if(empty(User::getCurrentUserId())) {
	header('Location: /');
	return;
}

$roomId = $_REQUEST['room_id'];
if(empty($roomId)) {
	header('Location: /');
	return;
}

// Verify csrf
$csrf = $_REQUEST['csrf'];
if(empty($csrf) || !User::verifyCsrf($csrf)) {
	header('Location: /');
	return;
}

$checkInDate = $_REQUEST['checkInDate'];
$checkOutDate = $_REQUEST['checkOutDate'];

$booking = new Booking();
$booking->insert($roomId, User::getCurrentUserId(), $checkInDate, $checkOutDate);

header(sprintf('Location: /room.php?room_id=%s&checkInDate=%s&checkOutDate=%s', $roomId, $checkInDate, $checkOutDate));