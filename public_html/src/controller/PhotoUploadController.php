<?php
	class PhotoUploadController {

		private $photoFileModel;
		private $photoUploadView;
		private $photoRepository;
		private $photoManagementController;

		private $fileUploaded;
		private static $name = 'name';
		private static $fileType = 'type';
		private static $size = 'size';
		private static $caption = 'caption';
		private static $uniqueId = 'uniqueId';
		private static $emptyString = '';


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

			$this->photoUploadView->renderPhotoUploadForm(self::$emptyString, false);
			exit;
		}

		protected function uploadPhoto () {

			$this->fileUploaded = $this->photoFileModel->upload('fileupload');

			if ($this->fileUploaded) {

				$photoProperties = array();
				$dataResult = $this->photoFileModel->getDataResult();
				$photoProperties[self::$name] = $dataResult['uploadedFileName'];
				$photoProperties[self::$size] = $dataResult[self::$size];
				$photoProperties[self::$fileType] = $dataResult[self::$fileType];
				$photoProperties[self::$caption] = $this->photoUploadView->getPhotoCaption();
				$photoProperties[self::$uniqueId] = $this->photoFileModel->getUniquePhotoId();

				try {
					
					// TODO: We need to know if something goes wrong when creating a photoModel.
					$photoModel = $this->photoRepository->createPhotoModel($photoProperties);

					if ($this->photoRepository->insert($photoModel)) {
						
						$this->photoUploadView->setPhotoUploadSuccessMessage();
						$this->photoManagementController->run();
					}
					else {

						$this->photoFileModel->unlink($photoModel);
					}
				} 
				catch (PhotoNameAlreadyExistException $e) {

					$dataResult = $this->photoFileModel->unlink($photoModel);
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