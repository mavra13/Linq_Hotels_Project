class Rating {
    constructor(userName, comment, rate, timestamp) {
        this.userName = userName;
        this.comment = comment;
        this.rate = rate;
        this.timestamp = timestamp;
    }
}
class RatingsList {
    constructor(listSelector) {
        this.list = [];
        this.$list = document.querySelector(listSelector);
    }
	clearList() {
		this.$list.innerHTML = "";
		this.list = [];
	}
    addRating(rate) {
        this.list.push(rate);
        this.renderItems();
    }
    renderItems() {
        const ratingTemplate = this.list.map((rating, i) => {
            const { userName, comment, rate, timestamp } = rating;
            let star = '<span class="fa fa-solid fa-star lightGrayColor"></span>';
            let starChecked = '<span class="fa fa-solid fa-star lightGrayColor checked"></span>';
            let ratingStars = '';
            for (let r = 1; r <= 5; r++) {
                if (r > rate) {
                    ratingStars += star;
                } else {
                    ratingStars += starChecked;
                }
            }
            return `<li><span class="username">${userName} </span>
                        ${ratingStars}</br>
                        <span class="dateTimeAdded">${timestamp}</span></br></br>
                        <span class="comment">${comment}</span>
                    </li>`;
        });

        this.$list.innerHTML = ratingTemplate.join("");
    }
}

const $newReviewSection = document.querySelector(".newReview");
$newReviewSection.addEventListener("click", (e) => {
    const className = e.target.className;
    if (className.includes("fa-star")) {
        setRating(e);
    } 
})
const setRating = (e) => {
    let rateInput = document.querySelector('#rateInput');
    let starElement = e.target;
    let prevStarElement = starElement.previousElementSibling;
    let nextStarElement = starElement.nextElementSibling;
    if (starElement.classList.contains("checked") && !prevStarElement && !nextStarElement.classList.contains("checked")) {
        starElement.classList.remove("checked");
    } else {
        starElement.classList.add("checked");
        while (prevStarElement) {
            prevStarElement.classList.add("checked");
            prevStarElement = prevStarElement.previousElementSibling;
        }
        while (nextStarElement) {
            nextStarElement.classList.remove("checked");
            nextStarElement = nextStarElement.nextElementSibling;
        }
    }
	let lastStar = Array.from($newReviewSection.querySelectorAll('.checked')).pop();
	let rate = 0;
	if (lastStar) {
    	rate = parseInt(lastStar.dataset.rate);
	}
	rateInput.value = rate;
}

const ratingsAll = new RatingsList(".reviewsList");

document.addEventListener("DOMContentLoaded", () => {
    const $lovedBtn = document.querySelector(".fa-heart");
    const $bookNowBtn = document.querySelector(".bookNowBtn");
    const $overlay = document.querySelector("#overlay");
    const $popup = document.querySelector("#popupDialog");
    const $popupMessage = document.querySelector("#popupMessage");
    const $closeOverlayBtn = document.querySelector(".closeBtn");
	const $checkInDate = document.querySelector('#checkInDate');
    const $checkOutDate = document.querySelector('#checkOutDate');
    const $datePickers = document.querySelectorAll('.date-field');
	const $submitBtn = document.querySelector(".checkAvailableBtn"); //test

    let durationIsValid = false;
	
	
	if($bookNowBtn){
    	$bookNowBtn.addEventListener("click", () => {
        	if (!userSignedIn) {
            	$popupMessage.innerHTML = "You have to Sign in or Register in order to book a room!";
            	$overlay.classList.remove("hideElement");
            	$popup.classList.remove("hideElement");
            	//$popup.scrollIntoView();
				document.body.scrollTop = 0;
  				document.documentElement.scrollTop = 0;
        	}
    	})
	}
	$overlay.addEventListener("click", () => {
		if (!$overlay.classList.contains("hideElement") && !$popup.classList.contains("hideElement")) {
            $overlay.classList.add("hideElement");
            $popup.classList.add("hideElement")
        }
	})
    $closeOverlayBtn.addEventListener("click", () => {
        if (!$overlay.classList.contains("hideElement") && !$popup.classList.contains("hideElement")) {
            $overlay.classList.add("hideElement");
            $popup.classList.add("hideElement")
        }
    })

    $lovedBtn.addEventListener("click", () => {
        if (!userSignedIn) {
            $popupMessage.innerHTML = "You have to Sign in or Register in order to add a room to your favourites!";
            $overlay.classList.remove("hideElement");
            $popup.classList.remove("hideElement");
        }
    })
	const getNextDay = (date) => {
		let givenDate = new Date(date);
		let nextDay = new Date(date);
		nextDay.setDate(givenDate.getDate() + 1);
		let year = nextDay.toLocaleString("default", { year: "numeric" });
		let month = nextDay.toLocaleString("default", { month: "2-digit" });
		let day = nextDay.toLocaleString("default", { day: "2-digit" });
		let nextDayFormatted = `${year}-${month}-${day}`;
		return nextDayFormatted;
	};
	const setDuration = () => {
		let checkIn = $checkInDate.value;
		let checkOut = getNextDay(checkIn);
		
		$checkOutDate.value = checkOut;
		$checkOutDate.min = checkOut;
	};

    const durationValidate = () => {
        let checkIn = $checkInDate.value;
        let checkOut = $checkOutDate.value;
        if (checkIn === "" || checkOut === "") {
            durationIsValid = false;
        } else {
            if (checkOut <= checkIn) {
                durationIsValid = false;
            } else {
                durationIsValid = true;
            }
        }
    }
	const submitButtonState = () => {
		if (durationIsValid) {
        	$submitBtn.disabled = false
        } else {
        	$submitBtn.disabled = true;
        }
	};
	const checkDatePickers = () => {
		//Get today and tomorrow dates in a way that works the same for all locals
		let today = new Date();
    	let todayYear = today.toLocaleString("default", { year: "numeric" });
    	let todayMonth = today.toLocaleString("default", { month: "2-digit" });
    	let todayDay = today.toLocaleString("default", { day: "2-digit" });
		
    	let todayFormatted = `${todayYear}-${todayMonth}-${todayDay}`;
		let tomorrowFormatted = getNextDay(todayFormatted);
		
		//Set today's date as value to checkIn datepicker if it's empty
		if($checkInDate.value === "" || $checkInDate.value < todayFormatted || isNaN(Date.parse($checkInDate.value))){
			$checkInDate.value = todayFormatted;
		}
		//Set today's date as min date to checkIn datepicker
		$checkInDate.min = todayFormatted;
		//Set tomorrow's date as value to checkOut datepicker if it's empty
		if($checkOutDate.value === "" || $checkOutDate.value < tomorrowFormatted || isNaN(Date.parse($checkOutDate.value))){
			$checkOutDate.value = tomorrowFormatted;
		}
		durationValidate();
		if(!durationIsValid) {
			setDuration();
		} else {
			$checkOutDate.min = getNextDay($checkInDate.value);
		}
		durationValidate();
        submitButtonState();
		
	};
	if ($datePickers && $datePickers.length !== 0) {
		checkDatePickers();
		
		$checkInDate.addEventListener("change", (e) => {
            checkDatePickers();
        })
    }

})