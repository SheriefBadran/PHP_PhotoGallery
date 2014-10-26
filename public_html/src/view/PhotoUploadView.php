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
		private static $emptyString = '';
		private static $longCaptionErrorMessage = 'The caption can have maximum 25 characters.';
		private static $maximumCaptionLength = 25;

		public function __construct (HTMLview $htmlView, CookieStorage $cookieStorage, SessionModel $sessionModel) {

			$this->mainView = $htmlView;
			$this->cookieStorage = $cookieStorage;
			$this->sessionModel = $sessionModel;
		}

		public function renderPhotoUploadHTML ($message, $autoLoginIsSet = false) {

			$username = self::$emptyString;
			$messageHTML = self::$emptyString;
			$captionResponseHTML = self::$emptyString;
			$longCaptionMessage = $this->sessionModel->getCaptionErrorMessage();

			if ($this->sessionModel->userSessionIsSet()) {

				$username = $this->sessionModel->getUsername();
			}
			
			if ($autoLoginIsSet) {

				$username = $this->cookieStorage->getCookieUsername();
			}

			if ($message !== self::$emptyString) {
				
				$messageHTML .=	'<div class="isa_error">';
				$messageHTML .=		'<i class="fa fa-times-circle"></i>';
				$messageHTML .=			$message;
				$messageHTML .=	'</div>';
			}

			if ($longCaptionMessage !== self::$emptyString) {
				
				$captionResponseHTML .=	'<div class="isa_error">';
				$captionResponseHTML .=		'<i class="fa fa-times-circle"></i>';
				$captionResponseHTML .=			$longCaptionMessage;
				$captionResponseHTML .=	'</div>';
			}

			$html = '<nav class="menu">';
			$html .= 	'<ul>';
			$html .= 		'<li><a class="active" href=?' . self::$action . "=" . self::$actionUpload . '>Upload new photo</a></li>';
			$html .= 		'<li><a href=?' . self::$action . "=" . self::$actionManageGallery . '>Manage Photo\'s</a></li>';
			$html .= 		'<li><a href=?' . self::$action . "=" . self::$actionLogout . '>Logout</a></li>';
			$html .= 	'</ul>';
			$html .= '</nav>';

			$html .= '<h2>' . $username . ' - Upload New Photo.</h2>';

			$html .= '<form id="upload" action="" enctype="multipart/form-data" method="POST">';
			$html .= 	'<input type="hidden" name="MAX_FILE_SIZE" value="10000000">';
			$html .= 	'<input type="file" name="fileupload">';
			$html .=	'<label class="label">Caption: </label>';
			$html .=	'<input type="text" name="caption" maxlength="25"> ';
			$html .= 	'<input type="submit" name="upload" value="Upload">';
			$html .= '</form>';

			$html .= '<div id="uploadResponse">';
			$html .= 	$messageHTML;
			$html .= 	$captionResponseHTML;
			$html .= '</div>';

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

				$caption = $this->cleanString($_POST[self::$photoCaption]);

				if (strlen($caption) > self::$maximumCaptionLength) {
					
					$this->sessionModel->setCaptionErrorMessage(self::$longCaptionErrorMessage);
					return false;
				}
				
				return $caption;
			}
		}

		public function setPhotoUploadSuccessMessage () {

			$this->sessionModel->setPhotoUploadSuccessMessage(self::$setUploadSuccessMessage);
		}

		public function cleanString ($string) {

			$string = trim($string);
			$string = stripslashes($string);

			return (filter_var($string, FILTER_SANITIZE_STRING));
		}

	}