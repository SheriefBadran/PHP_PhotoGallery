<?php

	class LoginView {

		private $mainView;
		private static $loginErrorMessage = "Felaktigt användarnamn och/eller lösenord.";
		private static $emptyUsernameErrorMessage = "Användarnamn saknas.";
		private static $emptyPasswordErrorMessage = "Lösenord saknas.";
		private static $logOutSuccessMessage = "Du är nu utloggad.";
		private static $corruptCookieLogoutMessage = "Fel information i cookie.";

		public static $username = "username";
	    public static $password = "password";
	    public static $hashType = 'sha256';
	    public static $emptyString = '';


		function __construct () {

			$this->mainView = new HTMLView();
		}

		public function getLoginFormHTML ($message = '') {

			// IF cookie with errors is set render a sertain view.
			$responseMessages = '';

			if ($message != '') {
					
				$responseMessages .= '<p>' . $message . '</p>';
			}

			$loginHTML = 
			'<h2>Ej Inloggad</h2>' .

			'<form id="login" enctype="multipart/form-data" method="post" action="?login">' .
				'<fieldset>' .
					'<legend>Login - Skriv in användarnamn och lösenord</legend>' .
					$responseMessages .
					'<label for="username">Användarnamn : </label>' .
					'<input type="text" name="username" value="' . $_SESSION['LoginValues']['username'] . '" maxlength="30" id="username" /> ' .

					'<label for="password">Lösenord : </label>' .
					'<input type="password" name="password" maxlength="30" id="password" /> ' .

					'<label for="rememberMe">Håll mig inloggad :</label>
					<input id="rememberMe" type="checkbox" name="rememberMe">
					<input type="submit" name="submit" id="submit" value="Logga in" />
				</fieldset>
			</form>';

			$_SESSION['LoginValues']['username'] = "";

			return $loginHTML;			
		}

		public function renderLoginForm ($errorMessage = '') {

			$loginHTML = $this->GetLoginFormHTML($errorMessage);
			echo $this->mainView->echoHTML($loginHTML);
		}

		public function renderLogoutView ($isDefaultLogout = true) {

			$isDefaultLogout ? $this->RenderLoginForm(self::$logOutSuccessMessage)
							 : $this->RenderLoginForm(self::$corruptCookieLogoutMessage);
		}

		public function getUsername () {

			// Is called from LoginController
			if (isset($_POST['username'])) {
				
				return $this->cleanString($_POST['username']);
			}
		}

		public function getPassword () {

	        if (isset($_POST[self::$password])) {
	            
	            return $_POST[self::$password] === self::$emptyString ? self::$emptyString : 
	                                            hash(self::$hashType ,$_POST[self::$password]);
	        }
		}

		public function userPressLoginButton () {

			return isset($_POST['submit']);
		}

		public function validate () {

			if ($this->GetUsername() == null) {

				return self::$emptyUsernameErrorMessage;
			}

			else if ($this->GetPassword() == null) {

				$_SESSION['LoginValues']['username'] = $this->GetUsername();

				return self::$emptyPasswordErrorMessage;
			}

			return true;
		}

		public function autoLoginIsChecked () {

			$isChecked = false;

			if (isset($_POST['rememberMe'])) {
				
				$isChecked = $_POST['rememberMe'];
			}

			return ($isChecked == 'true' || $isChecked == 'on') ? true : false;
		}

		public function getLoginErrorMessage () {

			$errorMessage;
			$_SESSION['LoginValues']['username'] = $this->GetUsername();

			return self::$loginErrorMessage;
		}

		public function cleanString ($string) {

			$string = trim($string);
			$string = stripslashes($string);

			return (filter_var($string, FILTER_SANITIZE_STRING));
		}
	}