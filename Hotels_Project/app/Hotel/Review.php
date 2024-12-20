<?php

namespace Hotel;

use Hotel\DBConnect;

class Review extends DBConnect
{	
	public function insert($roomId, $userId, $rate, $comment)
	{
		// Start transaction
		$this->getPdo()->beginTransaction();
		
		// Insert review
		$query = 'INSERT INTO review (room_id, user_id, rate, comment) VALUES (:room_id, :user_id, :rate, :comment)';
		
		$parameters = [
			':room_id' => $roomId,
			':user_id' => $userId,
			':rate' => $rate,
			':comment' => $comment,
		];
		
		$this->execute($query, $parameters);
		
		// Update avg reviews of room
		$query = 'SELECT AVG(rate) as avg_reviews, COUNT(*) as count FROM review WHERE room_id = :room_id';
		
		$parameters = [
			':room_id' => $roomId
		];
		
		$roomAvg = $this->fetch($query, $parameters);
		
		$query = 'UPDATE room SET avg_reviews = :avg_reviews, count_reviews = :count_reviews WHERE room_id = :room_id';
		
		$parameters = [
			':room_id' => $roomId,
			':avg_reviews' => $roomAvg['avg_reviews'],
			':count_reviews' => $roomAvg['count'],
		];
		
		$this->execute($query, $parameters);
		
		// Commit transaction
		return $this->getPdo()->commit();	
	}
	
	public function getRoomReviews($roomId)
	{
		$query = 'SELECT review.*, user.name FROM review INNER JOIN user ON review.user_id = user.user_id WHERE room_id = :room_id ORDER BY review.created_time DESC';
		
		$parameters = [
			':room_id' => $roomId,
		];
		
		return $this->fetchAll($query, $parameters);
	}
	
	public function getListByUser($userId)
	{
		$parameters = [
			':user_id' => $userId
		];
		
		$query = 'SELECT review.*, room.name FROM review
		INNER JOIN room ON review.room_id = room.room_id
		WHERE user_id = :user_id';
		
		return $this->fetchAll($query, $parameters);
		
	}
}