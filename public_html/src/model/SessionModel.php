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
		private static $caption = 'caption';
		private static $uploadSuccessMessage = 'upLoadMessage';
		private static $deletePhotoSuccessMessage = 'deletePhotoMessage';
		private static $deleteCommentSuccessMessage = 'deleteCommentMessage';
		private static $urlComponent = 'urlcomponent';
		private static $uniquePhotoId = 'uniquePhotoId';
		private static $comment = 'comment';
		private static $author = 'author';
		private static $text = 'text';
		private static $setPhotoUploadMessageException = 'Param in setPhotoUploadSuccessMessage has to be of type string.';
		private static $setPhotoDeleteMessageException = 'Param in setPhotoDeleteSuccessMessage has to be of type string.';
		private static $setCommentDeleteMessageException = 'Param in setCommentDeleteSuccessMessage has to be of type string.';
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

			if (isset($_SESSION[self::$sessionUniqueId])) {
				
				unset($_SESSION[self::$sessionUniqueId]);
				$this->isLoggedIn = false;
			}
		}

		public function isStolen ($validId) {

			return isset($_SESSION[self::$securitySessionName]) && $validId != $_SESSION[self::$securitySessionName];
		}

		public function setPhotoUploadSuccessMessage ($message) {

			if (!is_string($message)) {
				
				throw new \Exception(self::$setPhotoUploadMessageException);
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
				
				throw new \Exception(self::$setPhotoDeleteMessageException);
			}

			$_SESSION[self::$photoSession][self::$deletePhotoSuccessMessage] = $message;
		}

		public function resetPhotoDeleteSuccessMessage () {

			if (isset($_SESSION[self::$photoSession][self::$deletePhotoSuccessMessage])) {

				$_SESSION[self::$photoSession][self::$deletePhotoSuccessMessage] = self::$emptyString;
			}
		}

		public function getPhotoDeleteSuccessMessage () {

			return isset($_SESSION[self::$photoSession][self::$deletePhotoSuccessMessage]) ?
						  $_SESSION[self::$photoSession][self::$deletePhotoSuccessMessage] :
						  self::$emptyString;
		}

		public function setCommentDeleteSuccessMessage ($message) {

			if (!is_string($message)) {
				
				throw new \Exception(self::$setCommentDeleteMessageException);
			}

			$_SESSION[self::$photoSession][self::$deleteCommentSuccessMessage] = $message;
		}

		public function resetCommentDeleteSuccessMessage () {

			if (isset($_SESSION[self::$photoSession][self::$deleteCommentSuccessMessage])) {

				$_SESSION[self::$photoSession][self::$deleteCommentSuccessMessage] = self::$emptyString;
			}
		}

		public function getCommentDeleteSuccessMessage () {

			return isset($_SESSION[self::$photoSession][self::$deleteCommentSuccessMessage]) ?
						  $_SESSION[self::$photoSession][self::$deleteCommentSuccessMessage] :
						  self::$emptyString;
		}

		public function setUniquePhotoId ($uniquePhotoId) {

			$_SESSION[self::$urlComponent][self::$uniquePhotoId] = $uniquePhotoId;
		}

		public function getUniquePhotoId () {

			if (isset($_SESSION[self::$urlComponent][self::$uniquePhotoId])) {
				
				$uniquePhotoId = $_SESSION[self::$urlComponent][self::$uniquePhotoId];
				$_SESSION[self::$urlComponent][self::$uniquePhotoId] = self::$emptyString;
			}

			return $uniquePhotoId;
		}

		public function setAuthorErrorMessage ($message) {

			$_SESSION[self::$comment][self::$author] = $message;
		}

		public function setCommentErrorMessage ($message) {

			$_SESSION[self::$comment][self::$text] = $message;
		}

		public function getAuthorErrorMessage () {

			$errorMsg = self::$emptyString;

			if (isset($_SESSION[self::$comment][self::$author])) {
				
				$errorMsg = $_SESSION[self::$comment][self::$author];
				$_SESSION[self::$comment][self::$author] = self::$emptyString;
			}
			
			return $errorMsg;
		}

		public function getCommentErrorMessage () {

			$errorMsg = self::$emptyString;

			if (isset($_SESSION[self::$comment][self::$text])) {
				
				$errorMsg = $_SESSION[self::$comment][self::$text];
				$_SESSION[self::$comment][self::$text] = self::$emptyString;
			}
			
			return $errorMsg;
		}

		public function setCaptionErrorMessage ($message) {

			$_SESSION[self::$photoSession][self::$caption] = $message;
		}

		public function getCaptionErrorMessage () {

			$errorMsg = self::$emptyString;
			
			if (isset($_SESSION[self::$photoSession][self::$caption])) {

				$errorMsg = $_SESSION[self::$photoSession][self::$caption];
				$_SESSION[self::$photoSession][self::$caption] = self::$emptyString;
			}

			return $errorMsg;
		}
	}