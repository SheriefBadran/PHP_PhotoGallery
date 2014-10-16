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
		private static $photoSession = 'photos';
		private static $uploadSuccessMessage = 'upLoadMessage';
		private static $deleteSuccessMessage = 'deleteMessage';
		private static $setPhotoMessageException = 'Param 1 in setPhotoUploadSuccessMessage has to be of type string.';
		private static $emptyString = '';

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

		public function setPhotoUploadSuccessMessage ($message) {

			if (!is_string($message)) {
				
				throw new \Exception(self::$setPhotoMessageException);
			}

			$_SESSION[self::$photoSession][self::$uploadSuccessMessage] = $message;
		}

		public function resetPhotoUploadSuccessMessage () {

			if (isset($_SESSION[self::$photoSession][self::$uploadSuccessMessage])) {
				
				$_SESSION[self::$photoSession][self::$uploadSuccessMessage] = self::$emptyString;	
			}
		}

		public function getPhotoUploadSuccessMessage () {
				
			return isset($_SESSION[self::$photoSession][self::$uploadSuccessMessage]) ?
						  $_SESSION[self::$photoSession][self::$uploadSuccessMessage] :
						  self::$emptyString;
		}

		public function setPhotoDeleteSuccessMessage ($message) {

			if (!is_string($message)) {
				
				throw new \Exception(self::$setPhotoMessageException);
			}

			$_SESSION[self::$photoSession][self::$deleteSuccessMessage] = $message;
		}

		public function resetPhotoDeleteSuccessMessage () {

			if (isset($_SESSION[self::$photoSession][self::$deleteSuccessMessage])) {

				$_SESSION[self::$photoSession][self::$deleteSuccessMessage] = self::$emptyString;
			}
		}

		public function getPhotoDeleteSuccessMessage () {

			return isset($_SESSION[self::$photoSession][self::$deleteSuccessMessage]) ?
						  $_SESSION[self::$photoSession][self::$deleteSuccessMessage] :
						  self::$emptyString;
		}
	}