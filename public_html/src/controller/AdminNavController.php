<?php
	
	class AdminNavController extends Publisher implements iSubscriber {

		private $sessionModel;
		private static $actionUpload = 'upload';
		private static $actionErrorlog = 'errorlog';
		private static $actionLogout = 'logout';

		public function __construct(SessionModel $sessionModel) {

			$this->sessionModel = $sessionModel;
		}

		public function update (Publisher $publisher) {

			// AdminNavView publishes wich nav choises the user made in the menu.
			if ($this->sessionModel->isLoggedIn()) {
				
				$this->runNavigationController($publisher->getNavChoices());
			}

			return false;
		}

		public function runNavigationController ($navChoice) {

			switch ($navChoice) {
				case self::$actionUpload:
					var_dump("upload Image");
					break;
				
				case self::$actionErrorlog:
					var_dump("view errorlog");
					break;

				case self::$actionLogout:
					
					var_dump("logout");
					$this->updateLogout();
					break;

				default:
					# code...
					break;
			}
		}

		public function updateLogout () {
			
			$this->logout = true;
			$this->notify();
		}

		public function getLogout () {

			return $this->logout;
		}


	}