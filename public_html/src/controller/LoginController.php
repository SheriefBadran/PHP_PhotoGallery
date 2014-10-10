<?php
	require_once(ViewPath.DS.'LoginView.php');

	class LoginController implements iSubscriber {
		// REMINDER: WHEN CREATING MODELS, THE MODEL MIGHT HAVE TO INHERIT FROM THE DATABASE OBJECTS IN THE HELPERS FOLDER?

		private $sessionModel;
		private $loginView;
		private $memberView;
		private $userModel;
		private static $hashString = "sha256";
		private static $actionLogout = 'logout';

		function __construct () {

			$this->sessionModel = new SessionModel();
			$this->loginView = new LoginView();
			$this->adminNavView = new AdminNavView();
			$this->userRepository = new UserRepository();
		}

		public function run () {

			global $remote_ip;
			global $b_ip;
			global $user_agent;

			// Set page reload flag
			$onReload = false;

			// Assign needed instances in local variables (Experiment).
			$loginView = clone $this->loginView;		
			// $memberView = clone $this->memberView;
			$adminNavView = clone $this->adminNavView;
			$sessionModel = clone $this->sessionModel;

			// RENDER START PAGE, Render loginView if user is not already logged in and did not press Login Button
			if(!$sessionModel->isLoggedIn() && !$loginView->userPressLoginButton() && !$adminNavView->rememberMe()) {

				// Generate output data
				$loginView->renderLoginForm();
				return;
			}

			// USER LOGS OUT
			if ($adminNavView->userPressLogoutButton()) {
				
				var_dump('logout'); die();
				$this->logoutUser();
				return true;
			}

			// USER MAKES A LOGIN REQUEST
			if ($loginView->userPressLoginButton()) {
				
				$result = $this->authenticateUser();

				// If comparison to database succeeded login user and render memberarea.
				if ($result === true) {

					$autoLoginIsSet = $loginView->AutoLoginIsChecked();
					$adminNavView->renderAdminNav($autoLoginIsSet, $onReload);

					return true;
				}
				else {

					// render loginform with errormessage.
					$loginView->renderLoginForm($result);
				}
			}

			// USER IS ALREADY LOGGED IN AND RELOADS PAGE or USER LOGGED IN WITH REMEMBER ME AND RELOADS
			if ($sessionModel->isLoggedIn() || $adminNavView->rememberMe()) {

				$onReload = true;

				$validId = hash(self::$hashString, $remote_ip . $user_agent);
				if ($sessionModel->isStolen($validId)) {
					
					$this->adminNavView->logoutUser();
					$this->loginView->renderLoginForm();
					return false;
				}

				// Check if somebody manipulated cookies.
				// This if statement only checks the or block if user klicked remember me because of the && - operator.
				if ( $adminNavView->rememberMe() && ($this->userCredentialManipulated() || $this->cookieDateManipulated()) ) {

					$this->logoutUser(false);
					return false;
				}

				$adminNavView->renderAdminNav(false, $onReload);

				return true;
			}
		}

		// HELPER FUNCTIONS FOR THIS CONTROLLER

		// Authentication logic. 
		protected function authenticateUser () {

			$username = $this->loginView->getUsername();
			$password = $this->loginView->getPassword();

			// 1. CLIENT-WORK: VALIDATE IN-DATA 
			$message = $this->loginView->validate();


			if ($message !== true) {
				
				return $message;
			}

			// 2. SERVER-AUTHENTICATION: CHECK WITH DATABASE IF USERNAME AND PASSWORD EXIST
			$userRecord = $this->userRepository->authenticateUser($username, $password);
			
			if ($userRecord) {

				$user = $this->userRepository->makeUser($userRecord);

				// TODO: Check that this is not done more than once.
				$this->sessionModel->loginUser($user);

				if ($this->loginView->AutoLoginIsChecked()) {

					// TODO: Change 30 to a constant/variable.
					$cookieExpTime = time() + 30;
					$this->adminNavView->saveUserCredentials($username, $password, $cookieExpTime);
					$this->userRepository->saveCookieExpTime($user->getUniqueId(), $cookieExpTime);
				}

				return true;
			}
			else {

				return $this->loginView->GetLoginErrorMessage();
			}
		}

		protected function userCredentialManipulated () {

			$username = $this->adminNavView->getCookieUsername();
			$password = $this->adminNavView->getCookiePassword();

			return !$this->userRepository->authenticateUser($username, $password);
		}

		protected function CookieDateManipulated () {

			// TODO: Move this logic to view.
			// $currentTime = time();
			// $cookieExpTime = (int)($this->userModel->GetCookieDateById());

			// return ($currentTime > $cookieExpTime) ? true : false;
			return false;
		}

		public function subscribe (Publisher $publisher) {

			
			if ($this->sessionModel->isLoggedIn() && $publisher->publishLogoutAction() === self::$actionLogout) {

				$this->logoutUser(true);
			}
		}

		protected function logoutUser ($isDefaultLogout = true) {

			$this->adminNavView->logoutUser();
			$this->loginView->RenderLogoutView($isDefaultLogout);
			exit;
		}
	}