<?php
	class SessionModel {

		private $isLoggedIn = false;
		private $uniqueId;
		private $message;
		private static $sessionUniqueId = 'uniqueId';
		private static $sessionUser = 'userdata';
		private static $username = 'username';
		private static $securitySessionName = 'unique';
		private static $hashString = "sha256";

		function __construct () {

			if (!isset($_SESSION[self::$sessionUser])) {
				
				$_SESSION[self::$sessionUser][self::$username] = '';
			}
		}

		public function isLoggedIn () {

			// return $this->isLoggedIn;
			return isset($_SESSION[self::$sessionUniqueId]);
		}

		public function getUniqueId () {

			return $this->uniqueId;
		}

		public function getUsername () {

			return $_SESSION[self::$sessionUser][self::$username];
		}

		public function userSessionIsSet () {

			return isset($_SESSION[self::$sessionUser][self::$username]);
		}

		public function loginUser (UserModel $user) {

			global $remote_ip;
			global $user_agent;

			// session_set_cookie_params(0);
			$this->uniqueId = $_SESSION[self::$sessionUniqueId] = $user->getUniqueId();
			$_SESSION[self::$sessionUser][self::$username] = $user->getUsername();
			$_SESSION[self::$securitySessionName] = hash(self::$hashString, $remote_ip . $user_agent);
			$this->isLoggedIn = true;
		}

		public function logoutUser () {

			unset($_SESSION[self::$sessionUniqueId]);
			$this->isLoggedIn = false;
		}

		public function isStolen ($validId) {

			return isset($_SESSION[self::$securitySessionName]) && $validId != $_SESSION[self::$securitySessionName];
		}
	}