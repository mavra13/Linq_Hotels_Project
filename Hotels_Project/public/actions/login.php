<?php

require_once __DIR__.'/../../boot/boot.php';

use Hotel\User;

if (strtolower($_SERVER['REQUEST_METHOD']) != 'post') {
	header('Location: /');
	return;
}

if(!empty(User::getCurrentUserId())) {
	header('Location: /');
	return;
}

// Verify user
$user = new User();
try {
	if(!$user->verify($_REQUEST['email'], $_REQUEST['password'])){
		header('Location: /login.php?error=Could not verify user');
		return;
	}
} catch (InvalidArgumentException $ex) {
		header('Location: /login.php?error=There is no user with the given email');
		return;
}

// Retrieve user
$userInfo = $user->getByEmail($_REQUEST['email']);

// Generate token
$token = $user->generateToken($userInfo['user_id']);

// Add cookie
setcookie('user_token', $token, time() + (30 * 24 * 60 * 60) , '/');

// Return to home page
header('Location: /');
