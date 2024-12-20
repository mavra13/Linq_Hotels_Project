<?php

require __DIR__.'/../boot/boot.php';

use Hotel\User;
use Hotel\Favourite;
use Hotel\Review;
use Hotel\Booking;

// Check for logged in user
$userId = User::getCurrentUserId();
if (empty($userId)) {
	header('Location: /');
	return;
}

// Get all Favourites
$favourite = new Favourite();
$userFavourites = $favourite->getListByUser($userId);

// Get all reviews
$review = new Review();
$userReviews = $review->getListByUser($userId);

// Get all bookings
$booking = new Booking();
$userBookings = $booking->getListByUser($userId);
?>

<!DOCTYPE html>
<html>

<head>
    <meta name="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex,nofollow">
    <title>Profile</title>
    <link href="assets/fonts/css/fontawesome.min.css" rel="stylesheet" />
    <link href="assets/fonts/css/solid.min.css" rel="stylesheet" />
    <link href="assets/styles/app.css" rel="stylesheet">
    <link href="assets/styles/profile.css" rel="stylesheet">
</head>

<body class="bodyContainer">
    <header class="header display-flex align-items-center justify-content-between">
        <a href="./"><img src="assets/icons/Hotels.png" alt="Hotels Logo" class="hotelsLogo"></a>
        <menu class="homePageMenu fsc-14 display-flex">
            <li class=""><a href="./"><i class="fa fa-solid fa-house menu-icon"></i>Home</a></li>
            <li class="menuItemActive"><a href="./profile.php"><i class="fa fa-solid fa-user menu-icon"></i>Profile</a></li>
            <li class="menuBtn-logout">
				<form name="logoutForm" method="post" action="./actions/logout.php">
					<a onclick="document.forms['logoutForm'].requestSubmit();"><i class="fa fa-solid fa-arrow-right-from-bracket menu-icon logout"></i>Sign out</a>
				</form>
			</li>
        </menu>
    </header>
    <main class="mainContainer display-flex align-items-stretch justify-content-between mb-35">
        <aside class="asideSection align-content-start">
            <section class="favouritesSection">
                <h2 class="asideHeading fsc-16 mb-10">Favourites</h2>
                <ol class="favouritesList mt-0 fsc-18">
                    <!-- <li>Megali Vretania Hotel</li> -->
                </ol>
            </section>
            <section class="reviewsSection">
                <h2 class="asideHeading fsc-16 mb-10">Reviews</h2>
                <ol class="reviewsList mt-0 fsc-18">
                    <!-- <li>Hilton Hotel
                        </br><span class="fa fa-star checked"></span>
                        <span class="fa fa-solid fa-star checked"></span>
                        <span class="fa fa-solid fa-star checked"></span>
                        <span class="fa fa-solid fa-star checked"></span>
                        <span class="fa fa-solid fa-star"></span>
                    </li>
                    <li>Megali Vretania Hotel
                        </br><span class="fa fa-star checked"></span>
                        <span class="fa fa-solid fa-star checked"></span>
                        <span class="fa fa-solid fa-star checked"></span>
                        <span class="fa fa-solid fa-star checked"></span>
                        <span class="fa fa-solid fa-star checked"></span>
                    </li> -->
                </ol>
            </section>
        </aside>
        <section class="mainSection align-content-start">
            <h1 class="pageHeading">My bookings</h1>
            <div class="roomsListBookings">

            </div>
        </section>
    </main>
    <footer class="footerContainer">
        <p>&copy;Copyright 2024</p>
    </footer>
<script src="assets/scripts/js/profile.js"></script>
	<script>
		let fav;
		<?php
		if(!empty($userFavourites)){
			foreach ($userFavourites as $fav) {
				$data = $fav['room_id'] . ', "' . $fav['name'] . '"'; ?>
				fav = new Favourite(<?php echo $data; ?>);
				favouriteRoomsList.addFavourite(fav);
			<?php }
		} else { ?>
		favouriteRoomsList.addNoFavouritesMsg();
		<?php } ?>
		let rev;
		<?php
		if(!empty($userReviews)){
			foreach ($userReviews as $rev) {
				$data = $rev['room_id'] . ', "' . $rev['name'] . '", ' . $rev['rate']; ?>
				rev = new Rating(<?php echo $data; ?>);
				ratingRoomsList.addRating(rev);
			<?php }
		} else { ?>
		ratingRoomsList.addNoRatingsMsg();
		<?php } ?>
		let booking;
		<?php 
		if(!empty($userBookings)){
			foreach ($userBookings as $booking) {
				$data = $booking['booking_id'] . ', ' . $booking['room_id']. ', ' . $booking['total_price'] . ', "' . $booking['check_in_date'] . '", "' . $booking['check_out_date'] . '", "assets/images/' . $booking['photo_url'] . '", "' . $booking['name'] . '", "' . $booking['city'] . ' ' . $booking['area']. '", "' . $booking['description_short']. '", "' . $booking['room_type'] . '"'; ?>
		booking = new Booking(<?php echo $data; ?>);
		bookingRoomsList.addBooking(booking);
		<?php	}
		} else { ?>
			bookingRoomsList.addNoBookingsMsg();
		
		<?php }
		?>
	</script>
</body>

</html>