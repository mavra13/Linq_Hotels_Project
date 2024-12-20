<?php

namespace Hotel;

use DateTime;
use Hotel\DBConnect;

class Booking extends DBConnect
{	
	public function insert($roomId, $userId, $checkInDate, $checkOutDate)
	{
		// Start transaction
		$this->getPdo()->beginTransaction();
		
		// Get room info
		$query = 'SELECT * FROM room WHERE room_id = :room_id';
		$parameters = [
			':room_id' => $roomId,
		];
	
		$roomInfo =  $this->fetch($query, $parameters);
		$price = $roomInfo['price'];
		
		// Calculate total price
		$checkInDateTime = new DateTime($checkInDate);
		$checkOutDateTime = new DateTime($checkOutDate);
		$daysDiff = $checkOutDateTime->diff($checkInDateTime)->days;
		$totalPrice = $price * $daysDiff;
	
		// Create new booking
		$query = 'INSERT INTO booking (room_id, user_id, check_in_date, check_out_date, total_price) VALUES (:room_id, :user_id, :check_in_date, :check_out_date, :total_price)';
		$parameters = [
			':room_id' => $roomId,
			':user_id' => $userId,
			':check_in_date' => $checkInDate,
			':check_out_date' => $checkOutDate,
			':total_price' => $totalPrice,
		];
		
		$this->execute($query,$parameters);
		
		// Commit transaction
		return $this->getPdo()->commit();	
		
	}
	
	public function roomAvailable($roomId, $checkInDate, $checkOutDate)
	{
		$query = 'SELECT room_id FROM booking 
		WHERE room_id = :room_id
		AND check_in_date < :check_out_date 
		AND check_out_date > :check_in_date';
		
		$parameters = [
			':room_id' => $roomId,
			':check_in_date' => $checkInDate->format(DateTime::ATOM),
			':check_out_date' => $checkOutDate->format(DateTime::ATOM),
		];
		
		$rows = $this->fetchAll($query, $parameters);
		
		return count($rows) == 0;
	}
	
	public function getListByUser($userId)
	{
		$parameters = [
			':user_id' => $userId
		];
		
		$query = 'SELECT booking.*, room.*, room_type.title as room_type 
		FROM booking
		INNER JOIN room ON booking.room_id = room.room_id
		INNER JOIN room_type ON room.type_id = room_type.type_id
		WHERE user_id = :user_id';
		
		return $this->fetchAll($query, $parameters);
		
	}
	
}