<?php
	class PhotoUploadController {

		private $fileUploaded;
		private $sessionModel;
		private $photoFileModel;
		private $photoUploadView;
		private $message;

		protected static $fileType = 'type';


		public function __construct (SessionModel $sessionModel, 
									 PhotoFileModel $photoFileModel, 
									 PhotoUploadView $photoUploadView,
									 PhotoRepository $photoRepository) {

			$this->sessionModel = $sessionModel;
			$this->photoFileModel = $photoFileModel;
			$this->photoUploadView = $photoUploadView;
			$this->photoRepository = $photoRepository;
		}

		public function run () {
				
			if ($this->photoUploadView->userPressUploadButton()) {
				
				$this->fileUploaded = $this->photoFileModel->upload('fileupload');

				if ($this->fileUploaded) {

					$caption = $this->photoUploadView->getPhotoCaption();
					$dataResult = $this->photoFileModel->getDataResult();

					try {
						
						$photoModel = $this->photoRepository->createPhotoModel($dataResult, $caption);
						$this->photoRepository->insert($photoModel);
						// $this->photoRepository->save($photoModel);
					} 
					catch (Exception $e) {
						
						throw new \Exception($e);
					}

					// $this->photoRepository->save($photoModel);
					var_dump($photoModel);

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