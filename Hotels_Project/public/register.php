<?php

require __DIR__.'/../boot/boot.php';

use Hotel\User;

if (!empty(User::getCurrentUserId())) {
	header('Location: /public/index.php'); die;
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta name="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex,nofollow">
    <title>Sign up</title>
    <link href="assets/fonts/css/fontawesome.min.css" rel="stylesheet" />
    <link href="assets/fonts/css/solid.min.css" rel="stylesheet" />
    <link href="assets/styles/app.css" rel="stylesheet">
    <link href="assets/styles/form.css" rel="stylesheet" />
</head>

<body class="bodyContainer">
    <header id="homeHeader" class="header display-flex align-items-center justify-content-between">
        <a href="./"><img src="assets/icons/Hotels.png" alt="Hotels Logo" class="hotelsLogo"></a>
        <menu class="homePageMenu fsc-14 display-flex">
            <li class=""><a href="./"><i class="fa fa-solid fa-house menu-icon"></i>Home</a></li>
            <li class=""><a href="./login.php"><i class="fa fa-solid fa-arrow-right-to-bracket menu-icon"></i>Sign in</a></li>
            <li class="menuItemActive"><a href="./register.php"><i class="fa fa-solid fa-pen-to-square menu-icon"></i>Register</a></li>
        </menu>
    </header>
    <main class="container">
        <section class="mainSection  align-items-center justify-content-start">
            <form class="registerForm" method="post" action="actions/register.php">
				
                <div class="formRow display-flex justify-content-center">
                    <h2 class="formHeading asideHeading text-center fsc-16">Sign up</h2>
                </div>
				<?php if (!empty($_GET['error'])) { ?>
				<div class="formRow display-flex flex-wrap errorMsg"><?php echo $_GET['error']; ?></div>
				<?php } ?>
                <div class="formRow display-flex flex-wrap">
                    <input id="name" name="name" class="form-input fsc-14" type="text" placeholder="Name" minlength=4 maxlength=16 required>
                </div>
                <div class="formRow display-flex flex-wrap">
                    <input name="email" class="form-input fsc-14 email emailInitial" type="email" placeholder="Email" pattern="[a-zA-Z0-9._%\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,4}$" required>
					<span class="errorEmail hideElement">
                        Emails do not match!
                    </span>
                </div>
				<div class="formRow display-flex flex-wrap">
                    <input name="email" class="form-input fsc-14 email emailRepeat" type="email" placeholder="Email" pattern="[a-zA-Z0-9._%\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,4}$" required>
					<span class="errorEmail hideElement">
                        Emails do not match!
                    </span>
                </div>
                <div class="formRow display-flex flex-wrap">
                    <div class="display-flex full-width">
                        <input name="password" class="form-input input-with-btn fsc-14 password passwordInitial" type="password" placeholder="Password" minlength=4 maxlength=16 required>
                        <input type="button" class="form-input btn-in-input fa-solid togglePass" value="&#xf070;">
                    </div>
                    <span class="errorPass hideElement">
                        Passwords do not match!
                    </span>
                   
                </div>
                <div class="formRow display-flex flex-wrap">
                    <div class="display-flex full-width">
                        <input class="form-input input-with-btn fsc-14 password passwordRepeat" type="password"
                            placeholder="Confirm Password" minlength=4 maxlength=16 required>
                        <input type="button" class="form-input btn-in-input fa-solid togglePass" value="&#xf070;">
                    </div>
                    <span class="errorPass hideElement">
                        Passwords do not match!
                    </span>
                </div>
                <div class="formRow display-flex justify-content-center">
                    <button type="submit" class="buttonGeneric custom-btn fsc-14 submitBtn" disabled>Register</button>
                </div>
            </form>
        </section>
    </main>
    <footer class="footerContainer">
        <p>&copy;Copyright 2024</p>
    </footer>
    <script>
        $formType = 2;
    </script>
    <script src="assets/scripts/js/forms.js"></script>
</body>

</html>