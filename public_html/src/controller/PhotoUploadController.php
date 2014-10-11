<?php
	class PhotoUploadController {

		private $sessionModel;
		private $photoFileModel;
		private $photoUploadView;
		private $message;

		public function __construct (SessionModel $sessionModel, PhotoFileModel $photoFileModel, PhotoUploadView $photoUploadView) {

			$this->sessionModel = $sessionModel;
			$this->photoFileModel = $photoFileModel;
			$this->photoUploadView = $photoUploadView;
		}

		public function run () {


			if ($this->sessionModel->isLoggedIn()) {
				
				if ($this->photoUploadView->userPressUploadButton()) {
					
					$fileUploaded = $this->photoFileModel->upload('fileupload');

					if ($fileUploaded) {


						var_dump('Photo is successfully uploaded!');
					}
					else {

						$this->photoUploadView->renderPhotoUploadForm($this->photoFileModel->errors[0], false);
						exit;
					}
				}

				$this->photoUploadView->renderPhotoUploadForm('', false);
				exit;
			}
		}
	}