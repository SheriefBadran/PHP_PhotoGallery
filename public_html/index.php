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
	$commentList		   =	new CommentList();

	$loginController 	   = 	new LoginController();
	$photoUploadView 	   = 	new PhotoUploadView($mainView, $cookieStorage, $sessionModel);
	$fileModel 			   = 	new PhotoFileModel();
	$photoRepository 	   = 	new PhotoRepository($thumbnailList); //using
	// $photoManagementView	   =    new PhotoManagementView($mainView, $sessionModel);
	// $photoManagementController = new PhotoManagementController($photoRepository, $photoManagementView, $fileModel);
	// $photoUploadController = 	new PhotoUploadController($fileModel, $photoUploadView, $photoRepository, $photoManagementController);
	// $adminNavController    = 	new AdminNavController($sessionModel, $photoUploadController, $photoManagementController);
	$adminNavView 		   = 	new AdminNavView();

	$commentsView		   =	new CommentsView($sessionModel);
	$photoView 			   =	new PhotoView($mainView, $commentsView);
	$paginationRepository  =   new PaginationRepository();
	$commentRepository	   =   new CommentRepository($commentList);	
	$paginationView		   =   new PaginationView ();
	$publicGalleryView	   =   new PublicGalleryView($mainView, $paginationView);
	$publicGalleryController = new PublicGalleryController($photoRepository, $paginationRepository, $commentRepository, $publicGalleryView, $photoView);	


	$paginationView->attach($publicGalleryController);
	$paginationView->updatePaginationPage();

	// Run Application
	$publicGalleryController->run();
	
