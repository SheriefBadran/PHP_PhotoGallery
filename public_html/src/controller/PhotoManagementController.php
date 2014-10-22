<?php
	class PhotoManagementController implements iSubscriber {

		private $photoRepository;
		private $commentRepository;
		private $photoManagementView;
		private $photoFileModel;

		private static $actionDelete = 'delete';
		private static $actionViewComments = 'viewcomments';
		private static $actionDeleteComment = 'deletecomment';
		private static $databaseErrorExceptionMessage = 'Error! Something went wrong when deleting from database.';

		public function __construct (PhotoRepository $photoRepository,
									 CommentRepository $commentRepository, 
									 PhotoManagementView $photoManagementView, 
									 PhotoFileModel $photoFileModel) {

			$this->photoRepository = $photoRepository;
			$this->commentRepository = $commentRepository;
			$this->photoManagementView = $photoManagementView;
			$this->photoFileModel = $photoFileModel;
		}

		protected function deleteComment () {

			$commentId = $this->photoManagementView->getCommentId();
			$commentDeleted = $this->commentRepository->deleteComment($commentId);

			if ($commentDeleted) {
				
				$this->photoManagementView->setCommentDeleteSuccessMessage();
				$this->photoManagementView->redirectToCommentManagement();
				exit;
			}
			else {

				$this->photoManagementView->redirectToManagementArea();
				exit;
			}
		}

		protected function showComments () {

			$uniqueId = $this->photoManagementView->getUniquePhotoId();
			$photoId = $this->photoRepository->getPhotoId($uniqueId);

			if ($photoId === false) {
				
				$this->photoManagementView->redirectToManagementArea();
				exit;
			}

			$commentList = $this->commentRepository->toList($photoId);

			if (count($commentList->toArray()) === 0) {
				
				$this->photoManagementView->renderEmptyCommentManagement();
				exit;
			}
			else {

				$this->photoManagementView->renderCommentManagement($commentList);
				exit;
			}
		}

		protected function deletePhoto () {

			$uniqueId = $this->photoManagementView->getUniquePhotoId();
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

				throw new \Exception(self::$databaseErrorExceptionMessage);
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

			$deletePhotoAction = $publisher->publishDeletePhotoAction();
			$viewCommentsAction = $publisher->publishViewCommentsAction();
			$deleteCommentAction = $publisher->publishDeleteCommentAction();

			if ($deletePhotoAction === self::$actionDelete) {

				$this->deletePhoto();
			}

			if ($viewCommentsAction === self::$actionViewComments) {
				
				$this->showComments();
			}

			if ($deleteCommentAction === self::$actionDeleteComment) {
				
				$this->deleteComment();
			}
		}
	}