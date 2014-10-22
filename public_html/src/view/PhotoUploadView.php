<?php

	class PhotoUploadView extends Publisher {

		private $cookieStorage;
		private $sessionModel;

		private static $action = 'action';
		private static $actionUpload = 'upload';
		private static $actionManageGallery = 'manage';
		private static $actionErrorlog = 'errorlog';
		private static $actionLogout = 'logout';
		private static $photoCaption = 'caption';
		private static $setUploadSuccessMessage = 'The photo was successfully uploaded.';
		private static $formActionUrl = 'src/view/PhotoUploadView.php';

		public function __construct (HTMLview $htmlView, CookieStorage $cookieStorage, SessionModel $sessionModel) {

			$this->mainView = $htmlView;
			$this->cookieStorage = $cookieStorage;
			$this->sessionModel = $sessionModel;
		}

		public function renderPhotoUploadHTML ($message, $autoLoginIsSet = false) {

			$username = '';


			if ($this->sessionModel->userSessionIsSet()) {

				$username = $this->sessionModel->getUsername();
			}
			
			if ($autoLoginIsSet) {

				$username = $this->cookieStorage->getCookieUsername();
			}

			$html = '<nav>';
			$html .= 	'<ul>';
			$html .= 		'<li><a href=?' . self::$action . "=" . self::$actionUpload . '>Upload new photo</a></li>';
			$html .= 		'<li><a href=?' . self::$action . "=" . self::$actionManageGallery . '>Manage Photo\'s</a></li>';
			$html .= 		'<li><a href=?' . self::$action . "=" . self::$actionErrorlog . '>View error log</a></li>';
			$html .= 		'<li><a href=?' . self::$action . "=" . self::$actionLogout . '>Logout</a></li>';
			$html .= 	'</ul>';
			$html .= '</nav>';

			$html .= '<h2>' . $username . ' - Upload Photo.</h2>';

			$html .= '<form action="" enctype="multipart/form-data" method="POST">';
			$html .= 	'<input type="hidden" name="MAX_FILE_SIZE" value="10000000">';
			$html .= 	'<input type="file" name="fileupload">';
			$html .=	'<label>Caption: </label>';
			$html .=	'<input type="text" name="caption"> ';
			$html .= 	'<input type="submit" name="upload" value="Upload">';
			$html .= '</form>';

			$html .= '<p>' . $message . '</p>';
			return $html;
		}

		public function renderPhotoUploadForm ($message, $autoLoginIsSet = false) {

			$adminHTML = $this->renderPhotoUploadHTML($message, $autoLoginIsSet);
			echo $this->mainView->echoHTML($adminHTML);
		}

		public function userPressUploadButton () {

			return isset($_POST[self::$actionUpload]);
		}

		public function getPhotoCaption () {

			if (isset($_POST[self::$photoCaption])) {
				
				return $_POST[self::$photoCaption];
			}
		}

		public function setPhotoUploadSuccessMessage () {

			$this->sessionModel->setPhotoUploadSuccessMessage(self::$setUploadSuccessMessage);
		}

	}