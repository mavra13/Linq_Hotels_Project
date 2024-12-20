document.addEventListener("DOMContentLoaded", () => {

    const $password = document.querySelectorAll(".password");
    const $passwordInitial = document.querySelector(".passwordInitial");
    const $passwordRepeat = document.querySelector(".passwordRepeat");
    const $passwordVisibillity = document.querySelectorAll(".togglePass");
    const $email = document.querySelectorAll(".email");
	const $emailInitial = document.querySelector(".emailInitial");
    const $emailRepeat = document.querySelector(".emailRepeat");
    const $submitBtn = document.querySelector(".submitBtn");
    const $checkInDate = document.querySelector('#checkInDate');
    const $checkOutDate = document.querySelector('#checkOutDate');
    const $datePickers = document.querySelectorAll('.date-field');
    const $priceRangeNumbers = document.querySelectorAll(".priceRangeNumbers input");
    const $priceRangeSlider = document.querySelectorAll(".priceRangeSlider input");
    const $priceSlider = document.querySelector(".priceSlider");
    const $priceMinInput = document.querySelector("#priceMinNum");
    const $priceMaxInput = document.querySelector("#priceMaxNum");
    const $rangeMin = document.querySelector("#priceMinSl");
    const $rangeMax = document.querySelector("#priceMaxSl");
	const $priceRangeInput = document.querySelector(".priceRangeInput");

	let emailIsSame = false;
    let passwordIsSame = false;
    let durationIsValid = false;
    let gap = 5;
	let minPriceAllowed;
	let maxPriceAllowed;
	if ($priceRangeInput) {
    	minPriceAllowed = $priceRangeInput.min;
    	maxPriceAllowed = $priceRangeInput.max;
		if($priceSlider) {
			// Set the price range slider width based on current values
			$priceSlider.style.left = `${Math.abs((($priceMinInput.value - minPriceAllowed) / (maxPriceAllowed - minPriceAllowed))) * 100}%`;
			$priceSlider.style.right = `${100 - Math.abs((($priceMaxInput.value - minPriceAllowed) / (maxPriceAllowed - minPriceAllowed))) * 100}%`;
		}
	}
	
	const emailsMatchValidate = (mail, reMail) => {
        if (mail !== reMail) {
            emailIsSame = false;
        } else {
            emailIsSame = true;
        }
    }

    const passwordsMatchValidate = (pass, rePass) => {
        if (pass !== rePass) {
            passwordIsSame = false;
        } else {
            passwordIsSame = true;
        }
    }

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
		const $durationError = document.querySelectorAll(".errorDur");
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
        switch ($formType) {
            case 2:
                if (emailIsSame && passwordIsSame) {
                    $submitBtn.disabled = false;
                } else {
                    $submitBtn.disabled = true;
                }
                break
            case 3:
            case 4:
                if (durationIsValid) {
                    $submitBtn.disabled = false
                } else {
                    $submitBtn.disabled = true;
                }
                break
        }

    }
	
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
	
    if ($passwordVisibillity) {
        $passwordVisibillity.forEach(btn => {
            btn.addEventListener("click", () => {
                let passwordField = btn.previousElementSibling;
                if (passwordField.type === "password") {
                    passwordField.type = "text";
                    btn.value = "\uf06e";
                } else {
                    passwordField.type = "password";
                    btn.value = "\uf070";
                }
            })
        })
    }


    if ($email) {
		$email.forEach(m => {
            m.addEventListener("focusout", (e) => {
				let mail = e.target.value;
                
                if ($emailRepeat) {
                    let initialMail = $emailInitial.value;
                    let reMail = $emailRepeat.value;
					if (initialMail == "" || reMail == "") {
						emailIsSame = false;
						submitButtonState();
						return;
					}
                    const $emailMatchError = document.querySelectorAll(".errorEmail");
                    emailsMatchValidate(initialMail, reMail);
                    if (!emailIsSame) {
                        $emailInitial.classList.add("invalidInput");
                        $emailRepeat.classList.add("invalidInput");
                        $emailMatchError.forEach(er => {
                            er.classList.remove("hideElement");
                        })
                    } else {
                        $emailMatchError.forEach(er => {
                            er.classList.add("hideElement");
                        })

                        if ($emailInitial.nextElementSibling.classList.contains("hideElement")) {
                            $emailInitial.classList.remove("invalidInput");
                        }
                        if ($emailRepeat.nextElementSibling.classList.contains("hideElement")) {
                            $emailRepeat.classList.remove("invalidInput");
                        }
                    }
					submitButtonState();
                }
            })
        })
    }

    if ($password) {
        $password.forEach(psw => {
            psw.addEventListener("focusout", (e) => {
                let pass = e.target.value;

                if ($passwordRepeat) {
                    let initialPass = $passwordInitial.value;
                    let rePass = $passwordRepeat.value;
					if (initialPass == "" || rePass == "") {
						passwordIsSame = false;
						submitButtonState();
						return;
					}
                    const $matchError = document.querySelectorAll(".errorPass");
                    passwordsMatchValidate(initialPass, rePass);
                    if (!passwordIsSame) {
                        $passwordInitial.classList.add("invalidInput");
                        $passwordRepeat.classList.add("invalidInput");
                        $matchError.forEach(er => {
                            er.classList.remove("hideElement");
                        })
                    } else {
                        $matchError.forEach(er => {
                            er.classList.add("hideElement");
                        })

                        if ($passwordInitial.parentElement.nextElementSibling.classList.contains("hideElement")) {
                            $passwordInitial.classList.remove("invalidInput");
                        }
                        if ($passwordRepeat.parentElement.nextElementSibling.classList.contains("hideElement")) {
                            $passwordRepeat.classList.remove("invalidInput");
                        }
                    }
					submitButtonState();
                }
            })
        })
    }

    if ($datePickers && $datePickers.length !== 0) {
		checkDatePickers();
		
		$checkInDate.addEventListener("change", (e) => {
            checkDatePickers();
        })
    }

    

    if ($priceRangeNumbers) {
        $priceRangeNumbers.forEach((priceInput) => {
            priceInput.addEventListener("keydown", (e) => {
                let code = e.keyCode;
                switch (code) {
                    case 32:
                        e.preventDefault();
                        e.stopPropagation();
                        break
                    case 13:
                        e.preventDefault();
                        e.stopPropagation();
                    case 9:
                        let id = priceInput.id;
                        let val = parseInt(priceInput.value);

                        switch (id) {
                            case "priceMinNum":
                                switch (true) {
                                    case val < minPriceAllowed:
                                        $priceMinInput.value = minPriceAllowed
                                        break
                                    case val >= maxPriceAllowed && val >= $priceMaxInput.value:
                                    case val >= $priceMaxInput.value:
                                        $priceMinInput.value = parseInt($priceMaxInput.value) - gap
                                        break
                                    default:
                                        $priceMinInput.value = val;
                                }
                                $rangeMin.value = $priceMinInput.value;
                                $priceSlider.style.left = `${Math.abs((($priceMinInput.value - minPriceAllowed) / (maxPriceAllowed - minPriceAllowed))) * 100}%`;
                                break
                            case "priceMaxNum":
                                switch (true) {
                                    case val <= minPriceAllowed && val <= $priceMinInput.value:
                                    case val <= $priceMinInput.value:
                                        $priceMaxInput.value = parseInt($priceMinInput.value) + gap
                                        break
                                    case val > maxPriceAllowed:
                                        $priceMaxInput.value = maxPriceAllowed
                                        break
                                    default:
                                        $priceMaxInput.value = val;
                                }
                                $rangeMax.value = $priceMaxInput.value;
                                $priceSlider.style.right = `${100 - Math.abs((($priceMaxInput.value - minPriceAllowed) / (maxPriceAllowed - minPriceAllowed))) * 100}%`;
                        };
                }
            })
        });
		$priceRangeNumbers.forEach((priceInput) => {
            priceInput.addEventListener("focusout", () => {
				let id = priceInput.id;
                let val = parseInt(priceInput.value);
				switch (id) {
                	case "priceMinNum":
                    	switch (true) {
                        	case val < minPriceAllowed:
                            	$priceMinInput.value = minPriceAllowed
                                break
                           	case val >= maxPriceAllowed && val >= $priceMaxInput.value:
                            case val >= $priceMaxInput.value:
                            	$priceMinInput.value = parseInt($priceMaxInput.value) - gap
                                break
                            default:
                              	$priceMinInput.value = val;
                     	}
                        $rangeMin.value = $priceMinInput.value;
                        $priceSlider.style.left = `${Math.abs((($priceMinInput.value - minPriceAllowed) / (maxPriceAllowed - minPriceAllowed))) * 100}%`;
                        break
                 	case "priceMaxNum":
                    	switch (true) {
                        	case val <= minPriceAllowed && val <= $priceMinInput.value:
                            case val <= $priceMinInput.value:
                            	$priceMaxInput.value = parseInt($priceMinInput.value) + gap
                                break
                           	case val > maxPriceAllowed:
                            	$priceMaxInput.value = maxPriceAllowed
                            	break
                           	default:
                              	$priceMaxInput.value = val;
                          	}
                           	$rangeMax.value = $priceMaxInput.value;
                            $priceSlider.style.right = `${100 - Math.abs((($priceMaxInput.value - minPriceAllowed) / (maxPriceAllowed - minPriceAllowed))) * 100}%`;
       			};
			})
		});
    }

    if ($priceRangeSlider) {
        $priceRangeSlider.forEach((rangeHandle) => {
            rangeHandle.addEventListener("input", (e) => {
                let id = e.target.id;
                let diff = $rangeMax.value - $rangeMin.value;
                switch (id) {
                    case "priceMinSl":
                        switch (true) {
                            case diff < gap:
                                $rangeMin.value = parseInt($rangeMax.value) - gap;
                                break
                            default:
                        }
                        $priceMinInput.value = $rangeMin.value;
                        $priceSlider.style.left = `${Math.abs((($priceMinInput.value - minPriceAllowed) / (maxPriceAllowed - minPriceAllowed))) * 100}%`;
                        break
                    case "priceMaxSl":
                        switch (true) {
                            case diff < gap:
                                $rangeMax.value = parseInt($rangeMin.value) + gap;
                                break
                            default:
                        }
                        $priceMaxInput.value = parseInt($rangeMax.value);
                        $priceSlider.style.right = `${100 - Math.abs((($priceMaxInput.value - minPriceAllowed) / (maxPriceAllowed - minPriceAllowed))) * 100}%`;
                }
            })
        })
    }

});