<?php
// Register autoload function
spl_autoload_register(function ($class) {
	$class = str_replace("\\","/",$class);
	require_once sprintf(__DIR__.'/../app/%s.php', $class);
});

use Hotel\User;

$user = new User();

// Check for token
if (!empty($_COOKIE)) {
	$userToken = $_COOKIE['user_token'];
	if ($userToken) {
		if($user->verifyToken($userToken)) {
			$userInfo = $user->getTokenPayload($userToken);
			User::setCurrentUserId($userInfo['user_id']);
			$userName = $user->getNameById($userInfo['user_id']);
			User::setCurrentUserName($userName['name']);
		}
	} 
}