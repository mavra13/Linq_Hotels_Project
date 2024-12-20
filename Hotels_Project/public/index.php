<?php
require __DIR__.'/../boot/boot.php';

use Hotel\User;
use Hotel\FormOptions;
$op = new FormOptions();
$options = $op->getFormOptions();
$cities = $options['cities'];
$roomTypes = $options['roomTypes'];
?>

<!DOCTYPE html>
<html>

<head>
    <meta name="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex,nofollow">
    <title>Home</title>
    <link href="assets/fonts/css/fontawesome.min.css" rel="stylesheet" />
    <link href="assets/fonts/css/solid.min.css" rel="stylesheet" />
    <link href="assets/styles/app.css" rel="stylesheet">
    <link href="assets/styles/form.css" rel="stylesheet" />
</head>

<body class="bodyContainer homePage">
    <header id="homeHeader" class="header display-flex align-items-center justify-content-between">
        <a href="./"><img src="assets/icons/Hotels.png" alt="Hotels Logo" class="hotelsLogo"></a>

        <menu class="homePageMenu fsc-14 display-flex">
            <li class="menuItemActive"><a href="./"><i class="fa fa-solid fa-house menu-icon"></i>Home</a></li>
			<?php if (!empty(User::getCurrentUserId())) { ?>
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
    <main class="container">
        <section class="mainSection display-flex align-items-center justify-content-center">
            <form class="searchForm" action="list.php">
                <div class="formRow gap-4 display-flex">
                    <div class="display-flex flex-wrap align-content-start half-width">
                        <select name="city" class="form-select fsc-14 text-center text-last-center city" required>
                            <option value="">City</option>
							<?php foreach($cities as $city){ ?>
							<option value="<?php echo $city; ?>"><?php echo $city; ?></option>
							<?php } ?>
                        </select>
                    </div>
                    <div class="display-flex flex-wrap align-content-start half-width">
                        <select name="roomType" class="form-select fsc-14 text-center text-last-center room">
                            <option value="">Room Type</option>
							<?php foreach($roomTypes as $key => $value){ ?>
							<option value="<?php echo $key; ?>"><?php echo $value; ?></option>
							<?php } ?>
                        </select>
                    </div>
                </div>
                <div class="formRow gap-4 display-flex">
                    <div class="display-flex flex-wrap align-content-start half-width">
                        <input id="checkInDate" name="checkInDate" class="form-input fsc-14 text-center date-field" type="date"
                            placeholder="Check-in Date" required>
                    </div>
                    <div class="display-flex flex-wrap align-content-start half-width">
                        <input id="checkOutDate" name="checkOutDate" class="form-input fsc-14 text-center date-field" type="date"
                            placeholder="Check-out Date" required>
                    </div>
                </div>
                <div class="formRow display-flex justify-content-center">
                    <button type="submit" class="buttonGeneric custom-btn fsc-14 submitBtn" disabled>Search</button>
                </div>
            </form>
        </section>
    </main>
    <footer class="footerContainer">
        <p>&copy;Copyright 2024</p>
    </footer>
    <script>
        $formType = 3;
    </script>
    <script src="assets/scripts/js/forms.js"></script>
</body>

</html>