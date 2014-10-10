<?php

	class AdminNavView extends Publisher {

		// private $favoritePatterns = NULL;
		private $mainView;
		private $cookieStorage;
		private $sessionModel;
		private static $action = 'action';
		private static $actionUpload = 'upload';
		private static $actionEditGallery = 'editgallery';
		private static $actionErrorlog = 'errorlog';
		private static $actionLogout = 'logout';
		private static $defaultLoginSuccessMessage = "You are successfully logged in.";
		private static $autoLoginSuccessMessage = "You are successfully logged in and we'll remember you next time.";
		private static $cookieLoginSuccessMessage = "You are successfully logged in with cookies.";

		function __construct () {

			$this->mainView = new HTMLview();
			$this->cookieStorage = new CookieStorage();
			$this->sessionModel = new SessionModel();
		}

		public function renderAdminNavHTML ($message = '', $autoLoginIsSet = false) {

			$successMessage = isset($_GET['login']) ? '<p>' . $message . '</p>' : "";
			$username = '';


			if ($this->sessionModel->userSessionIsSet()) {

				$username = $this->sessionModel->getUsername();
			}
			
			if ($autoLoginIsSet) {

				$username = $this->cookieStorage->getCookieUsername();
			}

			$html = '<h2>Welcome ' . $username . '</h2>'; 
			$html .= $successMessage;
			$html .= '<nav>';
			$html .= 	'<ul>';
			$html .= 		'<li><a href=?' . self::$action . "=" . self::$actionUpload . '>Upload new photo</a></li>';
			$html .= 		'<li><a href=?' . self::$action . "=" . self::$actionEditGallery . '>Edit gallery</a></li>';
			$html .= 		'<li><a href=?' . self::$action . "=" . self::$actionErrorlog . '>View error log</a></li>';
			$html .= 		'<li><a href=?' . self::$action . "=" . self::$actionLogout . '>Logout</a></li>';
			$html .= 	'</ul>';
			$html .= '</nav>';

			return $html;
		}

		public function renderAdminNav ($autoLoginIsSet, $onReload) {

			if ($onReload) {

				$adminHTML = $this->renderAdminNavHTML(self::$cookieLoginSuccessMessage, $autoLoginIsSet);

				if ($this->sessionModel->isLoggedIn()) {

					$adminHTML = $this->renderAdminNavHTML();
				}
			}
			else {

				$adminHTML = $this->renderAdminNavHTML(self::$defaultLoginSuccessMessage, $autoLoginIsSet);

				if ($autoLoginIsSet) {

					$adminHTML = $this->renderAdminNavHTML(self::$autoLoginSuccessMessage, $autoLoginIsSet);
				}
			}

			echo $this->mainView->echoHTML($adminHTML);
		}

		public function updateNavChoices () {

			if (isset($_GET[self::$action])) {
				
				$this->actions = $_GET[self::$action];
				$this->notify();
			}
		}

		public function publishNavChoices () {

			return $this->actions;
		}

		public function updateLogoutAction () {

			if (isset($_GET[self::$action]) && $_GET[self::$action] === self::$actionLogout) {
				
				$this->actions = self::$actionLogout;
				$this->notify();
			}
		}

		public function publishLogoutAction () {

			return $this->actions;
		}

		public function rememberMe () {

			return $this->cookieStorage->rememberMe();
		}

		public function userPressLogoutButton () {

			if (isset($_GET['logout'])) {

				return true;
			}
		}

		public function saveUserCredentials ($username, $password, $cookieExpTime) {

			$this->cookieStorage->SaveUserCredentials($username, $password, $cookieExpTime);
		}

		public function deleteUserCredentials () {

			$this->cookieStorage->deleteUserCredentials();
		}

		public function getCookieUsername () {

			return $this->cookieStorage->getCookieUsername();
		}

		public function getCookiePassword () {

			return $this->cookieStorage->getCookiePassword();
		}

		public function logoutUser () {

			// Remove cookies if remember me.
			if ($this->rememberMe()) {
				
				$this->deleteUserCredentials();
			}

			// Logout user and render loginView.
			$this->sessionModel->logoutUser();
		}
	}