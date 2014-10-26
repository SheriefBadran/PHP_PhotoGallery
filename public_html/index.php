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

	$sessionModel 		   =   new SessionModel();
	$mainView 			   =   new HTMLview();

	$thumbnailList		   =   new ThumbnailList();
	$commentList		   =   new CommentList();

	$photoRepository 	   =   new PhotoRepository($thumbnailList);

	$commentsView		   =   new CommentsView($sessionModel);
	$photoView 			   =   new PhotoView($mainView, $commentsView);
	$paginationRepository  =   new PaginationRepository();
	$commentRepository	   =   new CommentRepository($commentList);
	$paginationView		   =   new PaginationView ();
	$publicGalleryView	   =   new PublicGalleryView($mainView, $paginationView);
	$publicGalleryController = new PublicGalleryController($photoRepository, $paginationRepository, $commentRepository, $publicGalleryView, $photoView);


	$paginationView->attach($publicGalleryController);
	$paginationView->updatePaginationPage();

	// Run Application
	$publicGalleryController->run();
	
