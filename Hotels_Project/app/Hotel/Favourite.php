<?php

namespace Hotel;

use Hotel\DBConnect;

class Favourite extends DBConnect
{
	public function isFavourite($roomId, $userId) 
	{
		$parameters = [
			':room_id' => $roomId,
			':user_id' => $userId
		];
		
		$query = 'SELECT * FROM favorite WHERE room_id = :room_id AND user_id = :user_id';
		
		$favourite = $this->fetch($query, $parameters);
		
		return !empty($favourite);

	}
	
	public function addToFavourites($roomId, $userId)
	{
		$query = 'INSERT IGNORE INTO favorite (room_id, user_id) VALUES (:room_id, :user_id)';
		
		//Prepare parameters
		$parameters = [
			':room_id' => $roomId,
			':user_id' => $userId
		];
		
		$rows = $this->execute($query, $parameters);

		return $rows==1;	
	}
	
	public function removeFromFavourites($roomId, $userId)
	{
		$query = 'DELETE FROM favorite WHERE room_id = :room_id AND user_id = :user_id';
		
		//Prepare parameters
		$parameters = [
			':room_id' => $roomId,
			':user_id' => $userId
		];
		
		$rows = $this->execute($query, $parameters);

		return $rows==1;	
	}
	
	public function getListByUser($userId)
	{
		$parameters = [
			':user_id' => $userId
		];
		
		$query = 'SELECT favorite.*, room.name FROM favorite
		INNER JOIN room ON favorite.room_id = room.room_id
		WHERE user_id = :user_id';
		
		return $this->fetchAll($query, $parameters);
		
	}
}