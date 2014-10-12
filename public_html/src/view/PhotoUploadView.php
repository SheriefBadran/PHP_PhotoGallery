<?php

	class PhotoUploadView extends Publisher {

		private $cookieStorage;
		private $sessionModel;

		private static $action = 'action';
		private static $actionUpload = 'upload';
		private static $actionEditGallery = 'editgallery';
		private static $actionErrorlog = 'errorlog';
		private static $actionLogout = 'logout';
		private static $photoCaption = 'caption';
		private static $formActionUrl = 'src/view/PhotoUploadView.php';

		public function __construct (HTMLView $htmlView, CookieStorage $cookieStorage, SessionModel $sessionModel) {

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

			$html = '<h1>Edit Photo Gallery</h1>';
			$html .= '<h2>' . $username . '</h2>';

			$html .= '<nav>';
			$html .= 	'<ul>';
			$html .= 		'<li><a href=?' . self::$action . "=" . self::$actionEditGallery . '>Edit gallery</a></li>';
			$html .= 		'<li><a href=?' . self::$action . "=" . self::$actionErrorlog . '>View error log</a></li>';
			$html .= 		'<li><a href=?' . self::$action . "=" . self::$actionLogout . '>Logout</a></li>';
			$html .= 	'</ul>';
			$html .= '</nav>';

			$html .= '<form action="" enctype="multipart/form-data" method="POST">';
			$html .= 	'<input type="hidden" name="MAX_FILE_SIZE" value="1000000">';
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

	}