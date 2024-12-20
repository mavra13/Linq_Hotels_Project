<?php

namespace Hotel;

use Exception;
use Hotel\DBConnect;

class FormOptions extends DBConnect
{	
	public function getFormOptions()
	{
		$data = [];
		try {
			$rows = $this->fetchAll('SELECT DISTINCT city FROM room;SELECT * FROM room_type;SELECT DISTINCT count_of_guests FROM room;SELECT MIN(price), MAX(price) FROM room;',[],true);
			$cities = [];
			$roomTypes = [];
			$countOfGuests = [];
			$priceRange = [];
			foreach ($rows as $row) {
				foreach ($row as $r) {
					switch($row)
					{
						case array_key_exists('city', $r);
							$cities[]= $r['city'];
        					break;
						case array_key_exists('type_id', $r);
							$roomTypes[$r['type_id']]= $r['title'];
        					break;
						case array_key_exists('count_of_guests', $r);
							$countOfGuests[]= $r['count_of_guests'];
        					break;
						case array_key_exists('MIN(price)', $r);
							$priceRange['min']= $r['MIN(price)'];
							$priceRange['max']= $r['MAX(price)'];
        					break;
					}
				}
			}
			$data = [
				'cities' => $cities, 
				'roomTypes' => $roomTypes, 
				'countOfGuests' => $countOfGuests, 
				'priceRange' => $priceRange
			];
		} catch (Exception $ex) {
			
		}
		
		return $data;
	}	
}