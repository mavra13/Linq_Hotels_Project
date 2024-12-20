<?php

namespace Hotel;
use PDO;
use Hotel\DBConnect;
class User extends DBConnect
{
	const TOKEN_KEY = 'mtiaklgchp!agpmokuldimesfxr';
	
	private static $currentUserId;
	private static $currentUserName;
	
	public function getByEmail($email)
	{
		$parameters = [
			':email' => $email
		];
		return $this->fetch('SELECT * FROM user WHERE email = :email', $parameters);
	}
	
	public function getNameById($userId)
	{
		$parameters = [
			':user_id' => $userId
		];
		return $this->fetch('SELECT name FROM user WHERE user_id = :user_id', $parameters);
	}
	
	public function getList()
	{
		return $this->fetchAll('SELECT * FROM user');
	}
	
	public function insert($name, $email, $password)
	{
		$query = 'INSERT INTO user (name, email, password) VALUES (:name, :email, :password)';
		//Hash password
		$passwordHash = password_hash($password, PASSWORD_BCRYPT);
		
		//Prepare parameters
		$parameters = [
			':name' => $name,
			':email' => $email,
			':password' => $passwordHash
		];
		
		$rows = $this->execute($query, $parameters);

		return $rows==1;	
	}
	
	public function verify($email, $password)
	{
		$user = $this->getByEmail($email);
		print_r($user);
		return password_verify($password, $user['password']);
	}
	
	public function generateToken($userId, $csrf = '')
	{
		// Create token payload
		$payload = [
			'user_id' => $userId,
			'csrf' => $csrf ?: md5(time())
		];
		$payloadEncoded = base64_encode(json_encode($payload));
		$signature = hash_hmac('sha256', $payloadEncoded, self::TOKEN_KEY);
	
		return sprintf('%s.%s', $payloadEncoded, $signature);
	}
	
	public static function getTokenPayload($token)
 	{
 		// Get payload and signature
		[$payloadEncoded] = explode('.', $token);
		
		//Get payload
		return json_decode(base64_decode($payloadEncoded), true);
 	}
	
	public function verifyToken($token)
	{
		//Get payload
		$payload = self::getTokenPayload($token);
		$userId = $payload['user_id'];
		$csrf = $payload['csrf'];
	
		// Generate signature and verify
		return $this->generateToken($userId, $csrf) == $token;
	}
	
	public static function verifyCsrf($csrf)
	{
		return self::getCsrf() == $csrf;
	}
	
	public static function getCsrf()
	{
		if(!empty($_COOKIE)) {
			//Get token payload
			$token = $_COOKIE['user_token'];
			//var_dump($token);
			$payload = self::getTokenPayload($token);
		
			return $payload['csrf'];
		} else {
			return;
		}
	}
	
	public static function getCurrentUserId()
	{
		return self::$currentUserId;
	}
	
	public static function setCurrentUserId($userId)
	{
		self::$currentUserId = $userId;
	}
	
	public static function getCurrentUserName()
	{
		return self::$currentUserName;
	}
	
	public static function setCurrentUserName($userName)
	{
		self::$currentUserName = $userName;
	}
		
}