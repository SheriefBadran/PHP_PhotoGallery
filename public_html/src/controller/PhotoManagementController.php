<?php
	class PhotoManagementController implements iSubscriber {

		private $photoRepository;
		private $photoManagementView;
		private $photoFileModel;

		private static $actionDelete = 'delete';
		private static $databaseErrorExceptionMessage = 'Error! Something went wrong when deleting from database.';

		public function __construct (PhotoRepository $photoRepository, 
									 PhotoManagementView $photoManagementView, 
									 PhotoFileModel $photoFileModel) {

			$this->photoRepository = $photoRepository;
			$this->photoManagementView = $photoManagementView;
			$this->photoFileModel = $photoFileModel;
		}

		protected function deletePhoto () {

			$uniqueId = $this->photoManagementView->getPhotoToDelete();

			$filesDeleted = $this->photoFileModel->removePhoto($uniqueId);

			if ($filesDeleted) {
				
				$photoDeleted = $this->photoRepository->deletePhoto($uniqueId);
			}
			else {

				$this->photoManagementView->redirect($_SERVER['PHP_SELF'] . '?action=manage');
				exit;
			}

			if ($photoDeleted) {
				
				$this->photoManagementView->setPhotoDeleteSuccessMessage();
				$this->photoManagementView->redirect($_SERVER['PHP_SELF'] . '?action=manage');
			}
			else {

				throw new \Exception(self::$$databaseErrorExceptionMessage);
			}

		}

		public function run () {

			$thumbnailWidth = $this->photoManagementView->getAdminThumbnailWidth();

			try {

				$thumbnails = $this->photoRepository->toList($thumbnailWidth);

			}
			catch (EmptyRecordException $e) {

				$this->photoManagementView->resetPhotoDeleteSuccessMessage();
				$this->photoManagementView->renderEmptyPhotoManagement();
				exit;
			}
			
			$this->photoManagementView->renderPhotoManagement($thumbnails);
			exit;
		}

		public function subscribe (Publisher $publisher) {

			$action = $publisher->publishDeleteAction();

			if ($action === self::$actionDelete) {

				$this->deletePhoto();
			}
		}
	}