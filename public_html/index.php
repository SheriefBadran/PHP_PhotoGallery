<?php

	session_start();

	// Initialize security objects to identify hijacking.
	$remote_ip = $_SERVER['REMOTE_ADDR'];
	// $b_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	$user_agent = $_SERVER['HTTP_USER_AGENT'];

	if (!isset($_SESSION['LoginValues'])) {
		
		$_SESSION['LoginValues']['username'] = '';
	}

	require_once("../data/pathConfig.php");

	$sessionModel = new SessionModel();
	$loginController = new LoginController();
	$photoUploadController = new PhotoUploadController();
	$adminNavController = new AdminNavController($sessionModel, $photoUploadController);
	$adminNavView = new AdminNavView();

	// $adminNavView publishes to $adminNavController (admin nav choises).
	$adminNavView->attach($adminNavController);
	$adminNavView->updateNavChoices();

	// $adminnavView publishes to $loginController (if user clicks logout).
	$adminNavView->attach($loginController);
	$adminNavView->updateLogoutAction();


	// Run Application
	$loginController->run();
	// $loginViewHMTL = $loginView->renderLoginForm();




	// $htmlView = new HTMLview();
	// $adminNavView = new AdminNavView();
	// $adminNavHTML = $adminNavView->renderAdminNavHTML();
	// $htmlView->echoHTML($adminNavHTML);

	// $navigationController = new NavigationController();
	// $adminNavView->attach($navigationController);

	// $adminNavView->updateNavChoices();
	
