<?php
	
	class AdminNavController implements iSubscriber {

		private $sessionModel;
		private $photoUploadController;
		private $photoManagementController;

		private static $actionUpload = 'upload';
		private static $actionManageGallery = 'manage';
		private static $actionErrorlog = 'errorlog';
		private static $actionLogout = 'logout';

		public function __construct(SessionModel $sessionModel, 
									PhotoUploadController $photoUploadController,
									PhotoManagementController $photoManagementController) {

			$this->sessionModel = $sessionModel;
			$this->photoUploadController = $photoUploadController;
			$this->photoManagementController = $photoManagementController;
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

				case self::$actionManageGallery:
				
					$this->photoManagementController->run();
					break;
				
				case self::$actionErrorlog:
					var_dump("view errorlog");
					break;
			}
		}
	}