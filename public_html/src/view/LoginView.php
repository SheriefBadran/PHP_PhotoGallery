<?php

	class LoginView {

		private $mainView;
		private static $loginErrorMessage = "Incorrect username and/or password.";
		private static $emptyUsernameErrorMessage = "Username is required.";
		private static $emptyPasswordErrorMessage = "Password is required.";
		private static $logOutSuccessMessage = "Your are logged out.";

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
					
				// $responseMessages .= '<p>' . $message . '</p>';

				$responseMessages .=		'<div class="loginMsg">';
				$responseMessages .=			'<i class="fa fa-info-circle"></i>';
				$responseMessages .=			$message;
				$responseMessages .=		'</div>';
			}

			$loginHTML = 

			'<form id="login" enctype="multipart/form-data" method="post" action="?login">' .
				'<fieldset>' .
					'<legend>Login - Please enter username and password.</legend>' .
					$responseMessages .
					'<label class="loginlabel" for="username">Username : </label>' .
					'<input type="text" name="username" value="' . $_SESSION['LoginValues']['username'] . '" maxlength="30" id="username" /> </br></br> ' .

					'<label class="loginlabel" for="password">Password : </label>' .
					'<input type="password" name="password" maxlength="30" id="password" /> ' .

					'<input type="submit" name="submit" id="loginButton" value="Login" />
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