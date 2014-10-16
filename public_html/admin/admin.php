<?php

	session_start();

	// Initialize security objects to identify hijacking.
	$remote_ip = $_SERVER['REMOTE_ADDR'];
	// $b_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	$user_agent = $_SERVER['HTTP_USER_AGENT'];

	if (!isset($_SESSION['LoginValues'])) {
		
		$_SESSION['LoginValues']['username'] = '';
	}

	require_once("../../data/pathConfig.php");

	$cookieStorage 		   =    new CookieStorage();
	$sessionModel 		   =    new SessionModel();
	$mainView 			   =    new HTMLview();

	$thumbnailList		   =    new ThumbnailList();

	$loginController 	   = 	new LoginController();
	$photoUploadView 	   = 	new PhotoUploadView($mainView, $cookieStorage, $sessionModel);
	$fileModel 			   = 	new PhotoFileModel();
	$photoRepository 	   = 	new PhotoRepository($thumbnailList);
	$photoManagementView   =    new PhotoManagementView($sessionModel, $mainView);
	$photoManagementController = new PhotoManagementController($photoRepository, $photoManagementView, $fileModel);
	$photoUploadController = 	new PhotoUploadController($fileModel, $photoUploadView, $photoRepository, $photoManagementController);
	$adminNavController    = 	new AdminNavController($sessionModel, $photoUploadController, $photoManagementController);
	$adminNavView 		   = 	new AdminNavView();

	// $adminNavView publishes to $adminNavController (admin nav choises).
	$adminNavView->attach($adminNavController);
	$adminNavView->updateChosenMenuItem();

	// $adminnavView publishes to $loginController (if user clicks logout).
	$adminNavView->attach($loginController);
	$adminNavView->updateLogoutAction();

	$photoManagementView->attach($photoManagementController);
	$photoManagementView->updateDeleteAction();

	// Run Application
	$loginController->run();
	
