<?php
	class PhotoUploadController {

		private $photoFileModel;
		private $photoUploadView;
		private $photoRepository;
		private $photoManagementController;

		private $fileUploaded;
		protected static $fileType = 'type';


		public function __construct (PhotoFileModel $photoFileModel, 
									 PhotoUploadView $photoUploadView,
									 PhotoRepository $photoRepository,
									 PhotoManagementController $photoManagementController) {

			$this->photoFileModel = $photoFileModel;
			$this->photoUploadView = $photoUploadView;
			$this->photoRepository = $photoRepository;
			$this->photoManagementController = $photoManagementController;
		}

		public function run () {
				
			if ($this->photoUploadView->userPressUploadButton()) {
				
				$this->uploadPhoto();
			}

			$this->photoUploadView->renderPhotoUploadForm('', false);
			exit;
		}

		protected function uploadPhoto () {

			$this->fileUploaded = $this->photoFileModel->upload('fileupload');

			if ($this->fileUploaded) {

				$caption = $this->photoUploadView->getPhotoCaption();
				$dataResult = $this->photoFileModel->getDataResult();
				$uniqueId = $this->photoFileModel->getUniquePhotoId();

				try {
					
					// TODO: We need to know if something goes wrong when creating a photoModel.
					$photoModel = $this->photoRepository->createPhotoModel($dataResult, $caption, $uniqueId);

					if ($this->photoRepository->insert($photoModel)) {
						
						$this->photoUploadView->setPhotoUploadSuccessMessage();
						$this->photoManagementController->run();
					}
					else {

						$this->photoFileModel->unlink($photoModel);
					}
				} 
				catch (PhotoNameAlreadyExistException $e) {

					$dataResult = $this->photoFileModel->unlink($uniqueId);
					$this->photoFileModel->errors[] = $e->getMessage();
					$this->photoUploadView->renderPhotoUploadForm($this->photoFileModel->errors[0], false);
					exit;
				}
				catch (Exception $e) {

					throw new \Exception($e);
				}

			}
			else {

				$this->photoUploadView->renderPhotoUploadForm($this->photoFileModel->errors[0], false);
				exit;
			}
		}
	}