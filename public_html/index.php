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

	$cookieStorage 		   =    new CookieStorage(); //using
	$sessionModel 		   =    new SessionModel(); //using 
	$mainView 			   =    new HTMLview(); //using

	$thumbnailList		   =    new ThumbnailList(); //using

	$loginController 	   = 	new LoginController();
	$photoUploadView 	   = 	new PhotoUploadView($mainView, $cookieStorage, $sessionModel);
	$fileModel 			   = 	new PhotoFileModel();
	$photoRepository 	   = 	new PhotoRepository($thumbnailList); //using
	$photoManagementView	   =    new PhotoManagementView($mainView, $sessionModel);
	$photoManagementController = new PhotoManagementController($photoRepository, $photoManagementView, $fileModel);
	$photoUploadController = 	new PhotoUploadController($fileModel, $photoUploadView, $photoRepository, $photoManagementController);
	$adminNavController    = 	new AdminNavController($sessionModel, $photoUploadController, $photoManagementController);
	$adminNavView 		   = 	new AdminNavView();

	$paginationRepository  =   new PaginationRepository();
	$paginationView		   =   new PaginationView ();
	$publicGalleryView	   =   new PublicGalleryView($mainView, $paginationView);
	$publicGalleryController = new PublicGalleryController($photoRepository, $paginationRepository, $publicGalleryView);

	// $adminNavView publishes to $adminNavController (admin nav choises).
	$adminNavView->attach($adminNavController);
	$adminNavView->updateChosenMenuItem();

	// $adminnavView publishes to $loginController (if user clicks logout).
	$adminNavView->attach($loginController);
	$adminNavView->updateLogoutAction();

	$photoManagementView->attach($photoManagementController);
	$photoManagementView->updateDeleteAction();

	// Run Application
	$publicGalleryController->run();
	
