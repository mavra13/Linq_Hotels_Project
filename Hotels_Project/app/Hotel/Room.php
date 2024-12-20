<?php

namespace Hotel;

use DateTime;
use Hotel\DBConnect;

class Room extends DBConnect
{	
	
	public function searchRoom($city, $checkInDate, $checkOutDate, $typeId = "",$guests = "", $minPrice = "", $maxPrice = "")
	{
		$parameters = [
			':city' => $city,
			':check_in_date' => $checkInDate->format(DateTime::ATOM),
			':check_out_date' => $checkOutDate->format(DateTime::ATOM),
		];
		if($typeId !== "") {
			$parameters[':type_id'] = $typeId;
		}
		if($guests !== "") {
			$parameters[':count_of_guests'] = $guests;
		}
		if($minPrice !== "" && $maxPrice !== "") {
			$parameters[':min_price'] = $minPrice;
			$parameters[':max_price'] = $maxPrice;
		}
		
		$query = 'SELECT room.*, room_type.title as room_type FROM room 
		INNER JOIN room_type ON room.type_id = room_type.type_id
		WHERE city = :city AND ';
		if($typeId !== "") {
			$query .= 'room.type_id = :type_id AND ';
		}
		if($guests !== "") {
			$query .= 'count_of_guests >= :count_of_guests AND ';
		}
		if($minPrice !== "" && $maxPrice !== "") {
			$query .= 'price BETWEEN :min_price AND :max_price AND ';
		}
		
		$query .= 'room_id NOT IN(
		SELECT room_id FROM booking 
		WHERE check_in_date < :check_out_date 
		AND check_out_date > :check_in_date)';
		
		return $this->fetchAll($query, $parameters);
	}
	
	public function getRoomInfo($roomId)
	{
		$parameters = [
			':room_id' => $roomId,
		];
		
		$query = 'SELECT * FROM room WHERE room_id = :room_id';
		return $this->fetch($query, $parameters);
	}
	public function getRoomAvgReviews($roomId)
	{
		$parameters = [
			':room_id' => $roomId,
		];
		
		$query = 'SELECT avg_reviews FROM room WHERE room_id = :room_id';
		return $this->fetch($query, $parameters);
	}
	
}