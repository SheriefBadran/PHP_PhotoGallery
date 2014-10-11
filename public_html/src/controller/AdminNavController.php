<?php
	
	class AdminNavController implements iSubscriber {

		private $sessionModel;
		private $photoUploadController;

		private static $actionUpload = 'upload';
		private static $actionEditGallery = 'editgallery';
		private static $actionErrorlog = 'errorlog';
		private static $actionLogout = 'logout';

		public function __construct(SessionModel $sessionModel, PhotoUploadController $photoUploadController) {

			$this->sessionModel = $sessionModel;
			$this->photoUploadController = $photoUploadController;
		}

		public function subscribe (Publisher $publisher) {

			// AdminNavView publishes wich nav choises the user made in the menu.
			if ($this->sessionModel->isLoggedIn()) {
				
				$this->runNavigationController($publisher->publishChosenMenuItem());
			}

			return false;
		}

		public function runNavigationController ($navChoice) {

			switch ($navChoice) {

				case self::$actionUpload:

					$this->photoUploadController->run();
					break;

				case self::$actionEditGallery:
					var_dump("view photos");
					break;
				
				case self::$actionErrorlog:
					var_dump("view errorlog");
					break;
			}
		}
	}