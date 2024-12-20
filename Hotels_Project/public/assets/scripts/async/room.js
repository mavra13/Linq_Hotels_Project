document.addEventListener("DOMContentLoaded", () => {
	const $favouriteForm = document.querySelector(".favouriteForm");
	const $isFavourite = document.querySelector('input[name="is_favourite"]');
	const $heartIcon = document.querySelector(".fa-heart");
	const $reviewForm = document.querySelector(".reviewForm");
	const $reviewStars = document.querySelectorAll(".reviewForm .fa-star");
	const $avgReviewStars = document.querySelectorAll(".ratingAvg > .fa-star");
	const $roomAvailableForm = document.querySelector(".checkAvailabilityForm");
	const $roomAvailableFrom = $roomAvailableForm.querySelector('input[name="checkInDate"]');
	const $roomAvailableTo = $roomAvailableForm.querySelector('input[name="checkOutDate"]');
	const $roomBookForm = document.querySelector(".bookNowForm");
	const $roomBookCheckIn = $roomBookForm.querySelector('input[name="checkInDate"]');
	const $roomBookCheckOut = $roomBookForm.querySelector('input[name="checkOutDate"]');
	const $roomAlreadyBookedBtn = document.querySelector(".alreadyBookedBtn");
	const $overlay = document.querySelector("#overlay");
	const $loader = document.querySelector(".loader");
	
	const htmlEntities = (str) => {
    	return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
	};
	const $loaderToggle = (status) => {
		if(status) {
			$overlay.classList.remove("hideElement");
			$loader.classList.remove("hideElement");
		} else {
			$overlay.classList.add("hideElement");
			$loader.classList.add("hideElement");
		}
	};
	
	$favouriteForm.addEventListener("submit", (e) => {
		e.preventDefault();
		$loaderToggle(true);
		// Get form data
		const $params = new FormData($favouriteForm);
		const $queryString = new URLSearchParams($params).toString();
		const $myUrl = "/ajax/room_favourite.php?";

		// Ajax request
		var xhttp = new XMLHttpRequest();
  		
		xhttp.open("POST", $myUrl, true);
		xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				const $data = JSON.parse(this.responseText);
				if($data.status){
					$isFavourite.value = $data.is_favourite ? 1 : 0;
					$data.is_favourite ? $heartIcon.classList.add("loved") : $heartIcon.classList.remove("loved");
				}
				$loaderToggle(false);
    		}
		};
  		xhttp.send($queryString);
	})
	
	const $renderReviews = ($data) => {
		ratingsAll.clearList();
		let review;
		if($data.length > 0) {
			$data.forEach((rev)=>{
				review = new Rating(rev.name, htmlEntities(rev.comment), rev.rate, rev.created_time);
				ratingsAll.addRating(review);
			});
		}
	};
	if($reviewForm) {
		$reviewForm.addEventListener("submit", (e) => {
			e.preventDefault();
			$loaderToggle(true);
			// Get form data
			const $params = new FormData($reviewForm);
			const $queryString = new URLSearchParams($params).toString();
			const $myUrl = "/ajax/room_review.php?";

			// Ajax request
			var xhttp = new XMLHttpRequest();
  		
			xhttp.open("POST", $myUrl, true);
			xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhttp.onreadystatechange = function () {
				if (this.readyState == 4 && this.status == 200) {
					const $data = JSON.parse(this.responseText);
					if($data.status){
						// Render reviews
						$renderReviews($data.reviews);
						// Reset the add review form
						$reviewForm.reset();
						$reviewStars.forEach((el) => {
							el.classList.remove("checked");
						});
						// Update the avg review of room
						const $avgRev = $data.roomAvg;
						for(let i=1; i<=5; i++) {
							if ($avgRev >= i) {
								$avgReviewStars[i - 1].classList.add("checked");
							}else{
								$avgReviewStars[i - 1].classList.remove("checked");
							}
						}
					}
					$loaderToggle(false);
    			}
			};
  			xhttp.send($queryString);
		})
	};
	
	$roomAvailableForm.addEventListener("submit", (e) => {
		e.preventDefault();
		$loaderToggle(true);
		// Get form data
		const $params = new FormData($roomAvailableForm);
		const $queryString = new URLSearchParams($params).toString();
		const $myUrl = "/ajax/room_available.php?";

		// Ajax request
		var xhttp = new XMLHttpRequest();
  		
		xhttp.open("POST", $myUrl, true);
		xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				const $data = JSON.parse(this.responseText);
				if($data.is_available) {
					$roomBookForm.classList.remove("hideElement");
					$roomAlreadyBookedBtn.classList.add("hideElement");
				} else {
					$roomBookForm.classList.add("hideElement");
					$roomAlreadyBookedBtn.classList.remove("hideElement");
				}
				$roomBookCheckIn.value = $roomAvailableFrom.value;
				$roomBookCheckOut.value = $roomAvailableTo.value;
				history.pushState({}, "", "/room.php?" + $queryString);
				$loaderToggle(false);
    		}
		};
  		xhttp.send($queryString);
	})
})