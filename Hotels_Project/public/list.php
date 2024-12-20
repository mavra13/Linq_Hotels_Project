<?php

require __DIR__.'/../boot/boot.php';

use Hotel\User;
use Hotel\Room;
use Hotel\FormOptions;

$op = new FormOptions();
$room = new Room();

$options = $op->getFormOptions();
$cities = $options['cities'];
$roomTypes = $options['roomTypes'];
$countOfGuests = $options['countOfGuests'];
$priceRange = $options['priceRange'];

$searchTerms = [
	'city' => $_REQUEST['city'],
	'roomType' => $_REQUEST['roomType'],
	'checkInDate' => $_REQUEST['checkInDate'],
	'checkOutDate' => $_REQUEST['checkOutDate'],
	'guests' => '',
	'minPrice' => '',
	'maxPrice' => '',
];
if($_REQUEST['guests']) {
	$searchTerms['guests'] = $_REQUEST['guests'];
}
if($_REQUEST['minPrice']) {
 $searchTerms['minPrice'] = $_REQUEST['minPrice'];
}
if($_REQUEST['maxPrice']) {
 $searchTerms['maxPrice'] = $_REQUEST['maxPrice'];
}

$searchResults = $room->searchRoom($searchTerms['city'], new DateTime($searchTerms['checkInDate']), new DateTime($searchTerms['checkOutDate']), $searchTerms['roomType'], $searchTerms['guests'], $searchTerms['minPrice'], $searchTerms['maxPrice']);

?>

<!DOCTYPE html>
<html>

<head>
    <meta name="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex,nofollow">
    <title>Search Results</title>
    <link href="assets/fonts/css/fontawesome.min.css" rel="stylesheet" />
    <link href="assets/fonts/css/solid.min.css" rel="stylesheet" />
    <link href="assets/styles/app.css" rel="stylesheet">
    <link href="assets/styles/list.css" rel="stylesheet">
</head>

<body class="bodyContainer">
    <header class="header display-flex align-items-center justify-content-between">
        <a href="./"><img src="assets/icons/Hotels.png" alt="Hotels Logo" class="hotelsLogo"></a>
        <menu class="homePageMenu fsc-14 display-flex">
            <li class=""><a href="./"><i class="fa fa-solid fa-house menu-icon"></i>Home</a></li>
			<?php if (!empty(User::getCurrentUserId())) { ?>
            <li class="menuBtn-user"><a href="./profile.php"><i
                        class="fa fa-solid fa-user menu-icon"></i>Profile</a></li>
            <li class="menuBtn-logout">
				<form name="logoutForm" method="post" action="./actions/logout.php">
					<a onclick="document.forms['logoutForm'].requestSubmit();"><i class="fa fa-solid fa-arrow-right-from-bracket menu-icon logout"></i>Sign out</a>
				</form>
			</li>
			<?php } else { ?>
            <li class="menuBtn-login"><a href="./login.php"><i
                        class="fa fa-solid fa-arrow-right-to-bracket menu-icon"></i>Sign in</a></li>
            <li class="menuBtn-register"><a href="./register.php"><i
                        class="fa fa-solid fa-pen-to-square menu-icon"></i>Register</a></li>
			<?php } ?>
        </menu>
    </header>
    <main class="mainContainer display-flex align-items-stretch justify-content-between mb-35">
        <aside class="asideSection filtersSection align-content-start">
            <section>
                <h2 class="asideHeading text-center fsc-16 mb-10">Find the perfect <br> hotel</h2>
                <form action="" class="searchForm">
                    <select name="guests" id="guests" class="mb-10 form-select fsc-14 text-center text-last-center">
                        <option value="" selected>Count of Guests</option>
						<?php foreach($countOfGuests as $count){ ?>
						<option value="<?php echo $count; ?>"><?php echo $count; ?></option>
						<?php } ?>
                    </select>
                    <div class="display-flex flex-wrap align-content-start mb-10">
                        <select name="roomType" class="form-select fsc-14 text-center text-last-center room">
                            <option value="" <?php echo $searchTerms['roomType'] == '' ? 'selected' : ''; ?> >Room Type</option>
                            <?php foreach($roomTypes as $key => $value){ ?>
							<option <?php echo $searchTerms['roomType'] == $key ? 'selected' : ''; ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
							<?php } ?>
                        </select>
                    </div>
                    <div class="display-flex flex-wrap align-content-start mb-10">
                        <select name="city" class="form-select fsc-14 text-center text-last-center city" required>
                            <option value="" <?php echo $searchTerms['city'] == '' ? 'selected' : ''; ?>>City</option>
                            <?php foreach($cities as $city){ ?>
							
							<option <?php echo $searchTerms['city'] == $city ? 'selected' : ''; ?> value="<?php echo $city; ?>"><?php echo $city; ?></option>
							<?php } ?>
                        </select>
                    </div>
                    <div class="priceRangeNumbers display-flex justify-content-between mb-10">
                        <input id="priceMinNum" name="minPrice" type="number" min="<?php echo $priceRange['min']; ?>" max="<?php echo $priceRange['max']; ?>" value="<?php echo $priceRange['min']; ?>"
                            class="form-input minPrice priceNumberInput text-center" />
                        <input id="priceMaxNum" name="maxPrice" type="number" min="<?php echo $priceRange['min']; ?>" max="<?php echo $priceRange['max']; ?>" value="<?php echo $priceRange['max']; ?>"
                            class="form-input maxPrice priceNumberInput text-center" />
                    </div>
                    <div class="sliderContainer mb-10">
                        <div class="priceSlider">
                        </div>
                    </div>
                    <div class="priceRangeSlider">
                        <input id="priceMinSl" type="range" min="<?php echo $priceRange['min']; ?>" max="<?php echo $priceRange['max']; ?>" value="<?php echo $priceRange['min']; ?>" step="5"
                            class="priceRangeInput" />
                        <input id="priceMaxSl" type="range" min="<?php echo $priceRange['min']; ?>" max="<?php echo $priceRange['max']; ?>" value="<?php echo $priceRange['max']; ?>" step="5"
                            class="priceRangeInput" />
                    </div>
                    <div class="display-flex justify-content-around mb-10">
                        <p class="PriceRangeLabel fsc-12">PRICE MIN.</p>
                        <p class="PriceRangeLabel fsc-12">PRICE MAX.</p>
                    </div>
                    <div class="display-flex flex-wrap align-content-start mb-10">
                        <input id="checkInDate" name="checkInDate" class="form-input fsc-14 text-center date-field" type="date"
                            placeholder="Check-in Date" value ="<?php echo $searchTerms['checkInDate']; ?>" required>
                    </div>
                    <div class="display-flex flex-wrap align-content-start mb-10">
                        <input id="checkOutDate" name="checkOutDate" class="form-input fsc-14 text-center date-field" type="date"
                            placeholder="Check-out Date" value ="<?php echo $searchTerms['checkOutDate']; ?>" required>
                    </div>
                    <button type="submit" class="buttonGeneric formSubmitBtn mb-10 custom-btn fsc-12 submitBtn">FIND
                        HOTEL</button>
                </form>
            </section>
        </aside>
        <section class="mainSection align-content-start">
            <h1 class="pageHeading">Search Results</h1>
            <div class="roomsListSearch">
            </div>
        </section>
    </main>
	<div id="overlay" class="hideElement"></div>
	<div class="loader hideElement"></div>
    <footer class="footerContainer">
        <p>&copy;Copyright 2024</p>
    </footer>
    <script>
        $formType = 4;
    </script>
    <script src="assets/scripts/js/forms.js"></script>
    <script src="assets/scripts/js/list.js"></script>
	<script src="assets/scripts/async/search.js"></script>
	<script>
		let room;
		<?php 
		if(!empty($searchResults)){
			foreach ($searchResults as $room) {
				$data = $room['room_id']. ', "assets/images/' . $room['photo_url']. '", "' . $room['name'] . '", "' . $room['city'] . ' ' . $room['area']. '", "' . $room['description_short']. '", ' . $room['price'] . ', ' . $room['count_of_guests'] . ', "' . $room['room_type'] . '"'; ?>
		room = new Room(<?php echo $data; ?>);
		searchRoomList.addRoom(room);
		<?php	}
		} else { ?>
			searchRoomList.addNoResultsMsg();
		
		<?php }
		?>
	</script>
</body>

</html>