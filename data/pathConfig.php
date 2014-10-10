<?php
	
	// DEFINE CORE PATHS (absolute).
	
	// Define a short for directory separator.
	defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);

	// Define a project root path.
	defined('ProjectRootPath') ? null : define('ProjectRootPath', DS.'Applications'.DS.'MAMP'.DS.'htdocs'.DS.'www'.DS.'git'.DS.'PHP_PhotoGallery');

	// Define helper path.
	defined('HelperPath') ? null : define('HelperPath', ProjectRootPath.DS.'data');

	// Define MVC path.
	defined('ModelPath') ? null : define('ModelPath', ProjectRootPath.DS.'public_html/src/model');
	defined('ViewPath') ? null : define('ViewPath', ProjectRootPath.DS.'public_html/src/view');
	defined('ControllerPath') ? null : define('ControllerPath', ProjectRootPath.DS.'public_html/src/controller');

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

	// ABSTRACT OBSERVER CLASSES
	require_once(HelperPath.DS.'observer'.DS.'PublisherBase.php');
	require_once(HelperPath.DS.'observer'.DS.'SubscriberBase.php');

	// OBSERVER CLASSES
	require_once(HelperPath.DS.'observer'.DS.'Publisher.php');

	// REQUIRE CONTROLERS
	require_once(ControllerPath.DS.'LoginController.php');
	require_once(ControllerPath.DS.'AdminNavController.php');

	// REQUIRE MODELS
	require_once(ModelPath.DS.'SessionModel.php');
	require_once(ModelPath.DS.'UserRepository.php');
	require_once(ModelPath.DS.'UserModel.php');

	// REQUIRE VIEWS
	require_once(ViewPath.DS.'CookieStorage.php');
	require_once(HelperPath.DS.'HTMLview.php');
	require_once(ViewPath.DS.'LoginView.php');
	require_once(ViewPath.DS.'AdminNavView.php');


