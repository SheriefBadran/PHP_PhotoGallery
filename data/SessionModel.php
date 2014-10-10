<?php
	class SessionModel {

		private $isLoggedIn = false;
		private $userId;
		private $message;
		private static $sessionUserId = 'userId';
		private static $sessionUserHeadCategory = 'userdata';
		private static $sessionUsername = 'username';
		private static $sessionPassword = 'password';
		private static $securitySessionName = 'unique';
		private static $hashString = "sha256";

		function __construct () {

			if (!isset($_SESSION[self::$sessionUserHeadCategory])) {
				
				$_SESSION[self::$sessionUserHeadCategory][self::$sessionUsername] = '';
			}
		}

		public function IsLoggedIn () {

			// return $this->isLoggedIn;
			return isset($_SESSION[self::$sessionUserId]);
		}

		public function GetUserId () {

			return $this->userId;
		}

		public function GetUsername () {

			return $_SESSION[self::$sessionUserHeadCategory][self::$sessionUsername];
		}

		public function LoginUser (UserModel $user) {

			global $remote_ip;
			// global $b_ip;
			global $user_agent;

			// session_set_cookie_params(0);
			$this->userId = $_SESSION[self::$sessionUserId] = $user->GetUserId();
			$_SESSION[self::$sessionUserHeadCategory][self::$sessionUsername] = $user->GetUsername();
			$_SESSION[self::$securitySessionName] = hash(self::$hashString, $remote_ip . $user_agent);
			$this->isLoggedIn = true;
		}

		public function LogoutUser () {

			unset($_SESSION[self::$sessionUserId]);
			$this->isLoggedIn = false;
		}

		public function IsStolen ($validId) {

			return isset($_SESSION[self::$securitySessionName]) && $validId != $_SESSION[self::$securitySessionName];
		}
	}