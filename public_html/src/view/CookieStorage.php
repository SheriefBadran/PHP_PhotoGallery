<?php

	class CookieStorage {

		// private static $cookieName = "CookieStorage";
		public $username;
		public $password;
		private static $usernameCookie = "username";
		private static $passwordCookie = "password";

		public function saveUserCredentials ($username, $password, $cookieExpTime) {

			setcookie(self::$usernameCookie, $username, $cookieExpTime, "/");
			setcookie(self::$passwordCookie, $password, $cookieExpTime, "/");
		}

		public function deleteUserCredentials () {

			setcookie(self::$usernameCookie, "", 1, "/");
			setcookie(self::$passwordCookie, "", 1, "/");
		}

		public function rememberMe () {

			return isset($_COOKIE[self::$usernameCookie]);
		}

		public function getCookieUsername () {

			if (isset($_COOKIE[self::$usernameCookie])) {
				
				return $_COOKIE[self::$usernameCookie];
			}
		}

		public function getCookiePassword () {

			if (isset($_COOKIE[self::$passwordCookie])) {
				
				return $_COOKIE[self::$passwordCookie];
			}
		}		
	}