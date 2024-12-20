<?php

require_once __DIR__.'/../../boot/boot.php';

use Hotel\User;

if (strtolower($_SERVER['REQUEST_METHOD']) != 'post') {
	header('Location: /');
	return;
}
var_dump(User::getCurrentUserId());
if(empty(User::getCurrentUserId())) {
	header('Location: /');
	return;
}

// Add cookie
setcookie('user_token', '', time() -3600 , '/');

// Return to home page
header('Location: /');
