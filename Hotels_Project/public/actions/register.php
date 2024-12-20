<?php

require_once __DIR__.'/../../boot/boot.php';

use Hotel\User;

if (strtolower($_SERVER['REQUEST_METHOD']) != 'post') {
	header('Location: /');
	return;
}
$user = new User();

try {
	$createUser = $user->insert($_REQUEST['name'], $_REQUEST['email'], $_REQUEST['password']);
} catch (PDOException $ex) {
		header('Location: /register.php?error=There is alredy a user with this email');
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