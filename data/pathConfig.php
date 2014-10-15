<?php
	
	// DEFINE CORE PATHS (absolute).
	
	// Define a short for directory separator.
	defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);

	// Define a project root path.
	defined('ProjectRootPath') ? null : define('ProjectRootPath', DS.'Applications'.DS.'MAMP'.DS.'htdocs'.DS.'www'.DS.'git'.DS.'PHP_PhotoGallery');

	// Define helper path.
	defined('HelperPath') ? null : define('HelperPath', ProjectRootPath.DS.'data');

	// Define MVC path.
	defined('ModelPath') ? null : define('ModelPath', ProjectRootPath.DS.'public_html'.DS.'src'.DS.'model');
	defined('ViewPath') ? null : define('ViewPath', ProjectRootPath.DS.'public_html'.DS.'src'.DS.'view');
	defined('ControllerPath') ? null : define('ControllerPath', ProjectRootPath.DS.'public_html'.DS.'src'.DS.'controller');

	defined('PhotoUploadDestinationPath') ? null : define('PhotoUploadDestinationPath', 
														   ProjectRootPath.DS.'data'.DS.'uploads');

	defined('ThumbnailPath') ? null : define('ThumbnailPath', ProjectRootPath.DS.'data'.DS.'thumbnails');

	// REQUIRE NEEDED FILES BELOW.

	// REQUIRE HELPERS

	// Database factory for db-type instances.
	require_once(HelperPath.DS.'interfaces'.DS.'iFactory.php');
	require_once(HelperPath.DS.'db'.DS.'DB_Base_Factory.php');
	require_once(HelperPath.DS.'db'.DS.'DB_Factory_PDO.php');

	require_once(HelperPath.DS.'interfaces'.DS.'iAbstractFactory.php');
	require_once(HelperPath.DS.'db'.DS.'DB_Factory.php');
	require_once(HelperPath.DS.'db'.DS.'DatabaseAccessModel.php');

	require_once(HelperPath.DS.'interfaces'.DS.'iPublisher.php');
	require_once(HelperPath.DS.'interfaces'.DS.'iSubscriber.php');

	// OWN SUBTYPE EXCEPTIONS
	require_once(HelperPath.DS.'exceptions'.DS.'PhotoNameAlreadyExistException.php');
	require_once(HelperPath.DS.'exceptions'.DS.'EmptyRecordException.php');

	// REQUIRE OBSERVER CLASSES

	// abstract
	require_once(HelperPath.DS.'observer'.DS.'PublisherBase.php');
	require_once(HelperPath.DS.'observer'.DS.'SubscriberBase.php');

	// non abstract
	require_once(HelperPath.DS.'observer'.DS.'Publisher.php');

	// REQUIRE CONTROLERS
	require_once(ControllerPath.DS.'LoginController.php');
	require_once(ControllerPath.DS.'PhotoUploadController.php');
	require_once(ControllerPath.DS.'AdminNavController.php');
	require_once(ControllerPath.DS.'PhotoManagementController.php');

	// REQUIRE MODELS
	require_once(ModelPath.DS.'SessionModel.php');
	require_once(ModelPath.DS.'UserRepository.php');
	require_once(ModelPath.DS.'UserModel.php');
	require_once(ModelPath.DS.'FileModel.php');
	require_once(ModelPath.DS.'PhotoFileModel.php');
	require_once(ModelPath.DS.'PhotoRepository.php');
	require_once(ModelPath.DS.'PhotoModel.php');
	require_once(ModelPath.DS.'ThumbnailModel.php');
	require_once(ModelPath.DS.'ThumbnailList.php');

	// REQUIRE VIEWS
	require_once(ViewPath.DS.'CookieStorage.php');
	require_once(HelperPath.DS.'HTMLview.php');
	require_once(ViewPath.DS.'LoginView.php');
	require_once(ViewPath.DS.'AdminNavView.php');
	require_once(ViewPath.DS.'PhotoUploadView.php');
	require_once(ViewPath.DS.'PhotoManagementView.php');


