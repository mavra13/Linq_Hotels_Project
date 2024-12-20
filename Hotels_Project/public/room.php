<?php

require __DIR__.'/../boot/boot.php';

use Hotel\User;
use Hotel\Room;
use Hotel\Favourite;
use Hotel\Review;
use Hotel\Booking;

$room = new Room();
$favourite = new Favourite();
$review = new Review();
$booking = new Booking();
$searchTerms = [
	'roomId' => $_REQUEST['room_id'],
	'checkInDate' => $_REQUEST['checkInDate'],
	'checkOutDate' => $_REQUEST['checkOutDate']
];
if (empty($searchTerms['roomId'])) {
	header('Location: /');
	return;
}

$roomInfo = $room->getRoomInfo($searchTerms['roomId']);
if (empty($roomInfo )) {
	header('Location: /');
	return;
}
$roomReviews = $review->getRoomReviews($searchTerms['roomId']);

$userId = User::getCurrentUserId();
$userName = User::getCurrentUserName();

$isFavourite = $favourite->isFavourite($searchTerms['roomId'], $userId);

$isAvailable = $booking->roomAvailable($searchTerms['roomId'],new DateTime($searchTerms['checkInDate']),new DateTime($searchTerms['checkOutDate']));

?>

<!DOCTYPE html>
<html>

<head>
    <meta name="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex,nofollow">
    <title>Room Details</title>
    <link href="assets/fonts/css/fontawesome.min.css" rel="stylesheet" />
    <link href="assets/fonts/css/solid.min.css" rel="stylesheet" />
    <link href="assets/styles/app.css" rel="stylesheet">
    <link href="assets/styles/room.css" rel="stylesheet">
	<style> /*.test-star::after{content: "\f089";}*/</style>
</head>

<body class="bodyContainer">
    <header class="header display-flex align-items-center justify-content-between">
        <a href="./"><img src="assets/icons/Hotels.png" alt="Hotels Logo" class="hotelsLogo"></a>
        <menu class="homePageMenu fsc-14 display-flex">
            <li class=""><a href="./"><i class="fa fa-solid fa-house menu-icon"></i>Home</a></li>
			<?php if (!empty($userId)) { ?>
            <li class="menuBtn-user"><a href="./profile.php"><i class="fa fa-solid fa-user menu-icon"></i>Profile</a></li>
			
            <li class="menuBtn-logout">
				<form name="logoutForm" method="post" action="./actions/logout.php">
					<a onclick="document.forms['logoutForm'].requestSubmit();"><i class="fa fa-solid fa-arrow-right-from-bracket menu-icon logout"></i>Sign out</a>
				</form>
			</li>
			<?php } else { ?>
            <li class="menuBtn-login"><a href="./login.php"><i class="fa fa-solid fa-arrow-right-to-bracket menu-icon"></i>Sign in</a></li>
            <li class="menuBtn-register"><a href="./register.php"><i class="fa fa-solid fa-pen-to-square menu-icon"></i>Register</a></li>
			<?php } ?>
        </menu>
    </header>
    <main class="mainContainer display-flex align-items-stretch justify-content-center">
        <section class="full-width pt-10">
            <section class="mainHeading pageHeadingShell display-flex justify-content-between">
                <section class="display-flex">
                    <h1 class="pageHeadingContent">
						<?php echo $roomInfo['name'] . ' - ' . $roomInfo['city'] . ', ' . $roomInfo['area']; ?>
                    </h1>
                    <div class="pageHeadingContent ratingAvg">
						<span>| Reviews:</span>
					<?php $avgRev = $roomInfo['avg_reviews'];
							for($i=1; $i<=5; $i++) {
								if ($avgRev >= $i) { ?>
									<span class="fa fa-solid fa-star checked" ></span>
								<?php } else {?>
									<span class="fa fa-solid fa-star"></span>
								
						<?php }} ?>
                         |
                        
                    </div>
					<div class="pageHeadingContent">
						<form name="favouriteForm" class="favouriteForm">
							<input type=hidden name="room_id" value="<?php echo $searchTerms['roomId']; ?>">
							<input type=hidden name="is_favourite" value="<?php echo $isFavourite ? '1' : '0'; ?>">
							<input type=hidden name="csrf" value="<?php echo User::getCsrf(); ?>">
						<?php if (!empty(User::getCurrentUserId())) { ?>
							<span class="fa fa-solid fa-heart <?php echo $isFavourite ? 'loved' : ''; ?>" onclick="document.forms['favouriteForm'].requestSubmit();"></span>
						<?php } else { ?>
							<span class="fa fa-solid fa-heart"></span>
						<?php } ?>
						</form>
						
					</div>
                </section>
                <h1 class="pageHeadingContent">
                    Per Night: <?php echo $roomInfo['price']; ?> &#x20AC
                </h1>
            </section>
            <img src="assets/images/<?php echo $roomInfo['photo_url']; ?>" class="mt-10 mb-10">
            <section
                class="secondaryHeadingIcons pageHeadingShell display-flex align-items-center justify-content-around">
                <p class="pageHeadingIconsContent text-center fsc-12"><span class="fa fa-solid fa-user"></span><span
                        class="numOfPeople pl-2"><?php echo $roomInfo['count_of_guests']; ?></span><br> COUNT OF GUESTS</p>
                <p class="pageHeadingIconsContent text-center fsc-12"><span class="fa fa-solid fa-bed"></span><span
                        class="numOfBeds pl-2"><?php echo $roomInfo['count_of_guests']; ?></span><br> TYPE OF ROOM</p>
                <p class="pageHeadingIconsContent text-center fsc-12"><span
                        class="fa fa-solid fa-square-parking"></span><span class="numOfParking pl-2"><?php echo $roomInfo['parking'] === 1 ? 'Yes' : 'No'; ?></span><br>
                    PARKING</p>
                <p class="pageHeadingIconsContent text-center fsc-12"><span class="fa fa-solid fa-wifi"></span><span
                        class="wifiBool pl-2"><?php echo $roomInfo['wifi'] === 1 ? 'Yes' : 'No'; ?></span><br> WIFI</p>
                <p class="pageHeadingIconsContent text-center fsc-12"><span class="fa fa-solid fa-paw"></span><span
                        class="petsAllowedBool pl-2"><?php echo $roomInfo['pet_friendly'] === 1 ? 'Yes' : 'No'; ?></span><br> PET FRIENDLY</p>
            </section>
            <section class="decorativeLeftBorder mt-20 mb-40">
                <p class="secondaryTitles fsc-16">Room Description</p>
                <p class="roomFullDescription fsc-12"><?php echo $roomInfo['description_long']; ?></p>
            </section>
            <div class="display-flex justify-content-between align-items-center">
				<form class="display-flex justify-content-between align-items-center checkAvailabilityForm" name="checkAvailabilityForm">
					<input type=hidden name="room_id" value="<?php echo $searchTerms['roomId']; ?>">
					<div class="display-flex flex-wrap align-content-start mb-10 minln-10">
						<label class="text-center full-width fsc-12" for="checkInDate">Check-in Date</label>
                        <input id="checkInDate" name="checkInDate" class="form-input fsc-14 text-center date-field" type="date"
                            placeholder="Check-in Date" value ="<?php echo $searchTerms['checkInDate']; ?>" required>
                    </div>
                    <div class="display-flex flex-wrap align-content-start mb-10 minln-10">
						<label class="text-center full-width fsc-12" for="checkOutDate">Check-out Date</label>
                        <input id="checkOutDate" name="checkOutDate" class="form-input fsc-14 text-center date-field" type="date"
                            placeholder="Check-out Date" value ="<?php echo $searchTerms['checkOutDate']; ?>" required>
                    </div>
                    <button type="submit" class="buttonGeneric formSubmitBtn custom-btn fsc-12 checkAvailableBtn"><span class="fa fa-solid fa-arrows-rotate"></span></button>
				</form>
				<div class="display-flex justify-content-end">
					<form name="bookNowForm" class="bookNowForm <?php echo $isAvailable ? '' : 'hideElement'; ?>" method="post" action="actions/book.php">
						<input type=hidden name="room_id" value="<?php echo $searchTerms['roomId']; ?>">
						<input type=hidden name="checkInDate" value="<?php echo $searchTerms['checkInDate']; ?>">
						<input type=hidden name="checkOutDate" value="<?php echo $searchTerms['checkOutDate']; ?>">
						<input type=hidden name="csrf" value="<?php echo User::getCsrf(); ?>">
						<?php if (!empty(User::getCurrentUserId())) { ?>
                		<button type="submit" class="buttonGeneric custom-btn fsc-12 bookNowBtn">Book Now</button>
						<?php } else { ?>
						<button type="button" class="buttonGeneric custom-btn fsc-12 bookNowBtn">Book Now</button>
						<?php } ?>
					</form>
                	<button type="button" class="alreadyBookedBtn buttonGenericRed custom-btn fsc-12 <?php echo $isAvailable ? 'hideElement' : ''; ?>">Already Booked</button>
				</div>
            </div>
            <section class="mapSection">
                <iframe
                    src="https://maps.google.com/maps?q=<?php echo $roomInfo['location_lat'] . ', '. $roomInfo['location_long'];?>&z=15&output=embed"
                    width="1005" height="300" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
            </section>
            <section class="decorativeLeftBorder mt-20 mb-60">
                <p class="secondaryTitles fsc-16">Reviews</p>
                <ol class="reviewsList">
                </ol>
            </section>
            <section class="decorativeLeftBorder mb-60 newReview">
			<?php if (!empty(User::getCurrentUserId())) { ?>
				<form name="reviewForm" class="reviewForm">
                	<p class="secondaryTitles fsc-16">Add Review</p>
					<input type=hidden name="room_id" value="<?php echo $searchTerms['roomId']; ?>">
					<input type=hidden name="csrf" value="<?php echo User::getCsrf(); ?>">
					<input id="rateInput" type=hidden name="rate" value="">
                	<p class="fsc-12 lightGrayColor starsRateContainer">
                    	<span class="fa fa-solid fa-star" data-rate="1"></span>
                    	<span class="fa fa-solid fa-star" data-rate="2"></span>
                    	<span class="fa fa-solid fa-star" data-rate="3"></span>
                    	<span class="fa fa-solid fa-star" data-rate="4"></span>
                    	<span class="fa fa-solid fa-star" data-rate="5"></span>
                	</p>
                	<textarea name="comment" rows="4" placeholder="review" class="reviewTextArea"></textarea>
                	<div class="display-flex justify-content-center mt-10 mb-10">
                    	<button type="submit"
                        class="buttonGeneric customSubmitReview-btn fsc-12 submitReviewBtn">Submit</button>
                	</div>
				</form>
			<?php } ?>
            </section>
        </section>
    </main>
    <div id="overlay" class="hideElement"></div>
    <div id="popupDialog" class="hideElement">
        <button type="button" class="closeBtn"><span class="fa fa-solid fa-xmark"></span></button>
        <p id="popupMessage">
        </p>
            <button type="button" onclick="window.location.assign('./login.php')" class="buttonGeneric custom-btn fsc-14">
                Sign In
            </button>
            <button type="button" onclick="window.location.assign('./register.php')" class="buttonGeneric custom-btn fsc-14">
                Register
            </button>
    </div>
	<div class="loader hideElement"></div>
    <footer class="footerContainer">
        <p>&copy;Copyright 2024</p>
    </footer>
	<script>
		var userSignedIn;	
		<?php if (!empty($userId)) { ?> 
			userSignedIn = true;
			const $currentUserName = "<?php echo $userName; ?>";
		<?php } else { ?> 
			userSignedIn = false;
		<?php } ?>
	</script>
    <script src="assets/scripts/js/room.js"></script>
	<script src="assets/scripts/async/room.js"></script>
	<script>
		let review;
		<?php foreach($roomReviews as $review){
			$data = '"' . $review['name'] . '", "' . htmlentities($review['comment']) . '", ' . $review['rate'] . ', "' . $review['created_time'] . '"'; ?>
			review = new Rating(<?php echo $data; ?>);
			ratingsAll.addRating(review);
		<?php }
		
		?>
	</script>
</body>

</html>