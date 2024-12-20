document.addEventListener("DOMContentLoaded", () => {
	const $searchForm = document.querySelector(".searchForm");
	const $overlay = document.querySelector("#overlay");
	const $loader = document.querySelector(".loader");
	
	const $loaderToggle = (status) => {
		if(status) {
			$overlay.classList.remove("hideElement");
			$loader.classList.remove("hideElement");
		} else {
			$overlay.classList.add("hideElement");
			$loader.classList.add("hideElement");
		}
	};
	
	const $newResults = (jsonData) => {
		const $data = JSON.parse(jsonData);
		
		searchRoomList.clearList();
		let room;
		if($data.length > 0) {
			$data.forEach((room)=>{
				room = new Room(room.room_id, "assets/images/" + room.photo_url, room.name, room.city + " " + room.area, room.description_short, room.price, room.count_of_guests, room.room_type);
				searchRoomList.addRoom(room);
			});
		} else {
			searchRoomList.addNoResultsMsg();
		}
	};
	
	$searchForm.addEventListener("submit", (e) => {
		e.preventDefault();
		$loaderToggle(true);
		// Get form data
		const $params = new FormData($searchForm);
		const $queryString = new URLSearchParams($params).toString();
		const $myUrl = "/ajax/search_results.php?" + $queryString;

		// Ajax request
		var xhttp = new XMLHttpRequest();
  		
		xhttp.open("GET", $myUrl, true);
		xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				// Render the rooms
				$newResults(this.responseText);
				// Update the url
				history.pushState({}, "", "/list.php?" + $queryString);
				$loaderToggle(false);
    		}
		};
  		xhttp.send();
	})
})