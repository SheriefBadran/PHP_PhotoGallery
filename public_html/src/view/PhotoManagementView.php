<?php
	class PhotoManagementView extends Publisher {

		private $mainView;
		private $commentsView;
		private $sessionModel;

		private static $adminThumbnailWidth = 100;

		private static $action = 'action';
		private static $name = 'name';
		private static $actionUpload = 'upload';
		private static $actionManageGallery = 'manage';
		private static $actionDelete = 'delete';
		private static $actionErrorlog = 'errorlog';
		private static $actionLogout = 'logout';
		private static $actionViewComments = 'viewcomments';
		private static $actionDeleteComment = 'deletecomment';
		private static $id = 'id';
		private static $actionDeletePhoto = 'delete';
		private static $photoDeleteSuccessMessage = 'The photo was successfully deleted.';
		private static $commentDeleteSuccessMessage = 'The comment was successfully deleted.';
		private static $noPhotosInGalleryResponseMessage = 'Ooops! Not even one photo in the gallery. Go ahead and upload!';

		public function __construct (HTMLview $mainView, CommentsView $commentsView, SessionModel $sessionModel) {

			$this->mainView = $mainView;
			$this->commentsView = $commentsView;
			$this->sessionModel = $sessionModel;
		}

		public function renderMenuHTML () {

			$html = '<nav>';
			$html .= 	'<ul>';
			$html .= 		'<li><a href=?' . self::$action . "=" . self::$actionUpload . '>Upload new photo</a></li>';
			$html .= 		'<li><a href=?' . self::$action . "=" . self::$actionManageGallery . '>Manage Photo\'s</a></li>';
			$html .= 		'<li><a href=?' . self::$action . "=" . self::$actionErrorlog . '>View error log</a></li>';
			$html .= 		'<li><a href=?' . self::$action . "=" . self::$actionLogout . '>Logout</a></li>';
			$html .= 	'</ul>';
			$html .= '</nav>';

			return $html;
		}

		public function renderEmptyGalleryManagementHTML () {

			$html = $this->renderMenuHTML();
			$html .= '<p>' . self::$noPhotosInGalleryResponseMessage . '</p>';

			return $html;
		}

		public function renderPhotoManagementHTML (Array $thumbnails) {

			$uploadConfirmMessage = $this->sessionModel->getPhotoUploadSuccessMessage();
			$deleteConfirmMessage = $this->sessionModel->getPhotoDeleteSuccessMessage();

			$this->sessionModel->resetPhotoUploadSuccessMessage();
			$this->sessionModel->resetPhotoDeleteSuccessmessage();

			$html = $this->renderMenuHTML();

			$html .= '<p>' . $uploadConfirmMessage . '</p>';
			$html .= '<p>' . $deleteConfirmMessage . '</p>';


			$html .=  "<h3>Manage photo's</h3>";
			$html .= '<table>';
			$html .=	'<tr>';
			$html .=		'<th>Image</th>';
			$html .=		'<th>Name</th>';
			$html .=		'<th>Caption</th>';
			$html .=		'<th>Size</th>';
			$html .=		'<th>Type</th>';
			$html .=		'<th>Comments</th>';
			// $html .=		'<th>&nbsp;</th>';
			$html .=	'<tr>';

			foreach ($thumbnails as $thumbnail) {

				$html .= '<tr>';
				$html .= 	'<td><img src=' . $thumbnail->getSRC() . '></td>';
				$html .= 	'<td>' . $thumbnail->getName() . '</td>';
				$html .= 	'<td>' . $thumbnail->getCaption() . '</td>';
				$html .= 	'<td>' . $thumbnail->getFormattedSize() . '</td>';
				$html .= 	'<td>' . $thumbnail->getType() . '</td>';
				$html .= 	'<td>';
				$html .=		'<a href=?' . self::$action . "=" . self::$actionViewComments . "&" . self::$name . "=" . $thumbnail->getUniqueId() . '>';
				$html .= 			'View Comments';
				$html .= 		'</a>';
				$html .= 	'</td>';
				$html .= 	'<td>';
				$html .=		'<a href=?' . self::$action . "=" . self::$actionDeletePhoto . "&" . self::$name . "=" . $thumbnail->getUniqueId() . '>';
				$html .= 			'Delete';
				$html .= 		'</a>';
				$html .= 	'</td>';
				$html .= '</tr>';
			}

			$html .= '</table>';

			return $html;
		}

		public function renderPhotoManagement (ThumbnailList $thumbnailList) {

			$html = $this->renderPhotoManagementHTML($thumbnailList->toArray());
			$this->mainView->echoHTML($html);
		}

		public function renderEmptyPhotoManagement () {

			$html = $this->renderMenuHTML();

			$html .= $this->renderEmptyGalleryManagementHTML();
			$this->mainView->echoHTML($html);
		}

		public function renderCommentManagement (CommentList $comments) {

			$html = $this->renderMenuHTML();

			$html .= $this->renderCommentsHTML($comments->toArray());
			$this->mainView->echoHTML($html);
		}

		public function renderEmptyCommentManagement () {

			$html = $this->renderMenuHTML();

			// TODO: Remove this string dep.
			$html .= '<p>There are no comments on this photo.</p>';
			$this->mainView->echoHTML($html);
		}

		public function renderCommentsHTML (Array $comments) {

			return $this->commentsView->renderCommentsHTML($comments, true);
		}

		public function getAdminThumbnailWidth () {

			return self::$adminThumbnailWidth;
		}

		public function updateDeletePhotoAction () {

			if (isset($_GET[self::$action]) && $_GET[self::$action] === self::$actionDelete) {
				
				$this->actions = $_GET[self::$action];
				$this->notify();
			}
		}

		public function publishDeletePhotoAction () {

			return $this->actions;
		}

		public function updateViewCommentsAction () {

			if (isset($_GET[self::$action]) && $_GET[self::$action] === self::$actionViewComments) {

				$this->sessionModel->setUniquePhotoId($this->getUniquePhotoId());
				$this->actions = $_GET[self::$action];
				$this->notify();
			}
		}

		public function publishViewCommentsAction () {

			return $this->actions;
		}

		public function getUniquePhotoId () {

			return (isset($_GET[self::$action]) && isset($_GET[self::$name])) ? $_GET[self::$name] : 
																				$this->sessionModel->getUniquePhotoId();
		}

		public function updateDeleteCommentAction () {

			if (isset($_GET[self::$action]) && $_GET[self::$action] === self::$actionDeleteComment) {
				
				$this->actions = $_GET[self::$action];
				$this->notify();
			}
		}

		public function publishDeleteCommentAction () {

			return $this->actions;
		}

		public function getCommentId () {

			if (isset($_GET[self::$action]) && isset($_GET[self::$id])) {
				
				return $_GET[self::$id];
			}
		}

		public function setPhotoDeleteSuccessMessage () {

			$this->sessionModel->setPhotoDeleteSuccessMessage(self::$photoDeleteSuccessMessage);
		}

		public function resetPhotoDeleteSuccessMessage () {

			$this->sessionModel->resetPhotoDeleteSuccessMessage();
		}

		public function setCommentDeleteSuccessmessage () {

			$this->sessionModel->setCommentDeleteSuccessmessage(self::$commentDeleteSuccessMessage);
		}

		public function resetCommentDeleteSuccessMessage () {

			$this->sessionModel->resetCommentDeleteSuccessMessage();
		}

		public function redirect ($path) {

			header('Location: '.$path);
		}

		public function redirectToCommentManagement () {

			$uniquePhotoId = $this->getUniquePhotoId();
			header('Location: '.$_SERVER['PHP_SELF']."?".self::$action.'='.self::$actionViewComments.'&'.self::$name.'='.$uniquePhotoId);
		}

		public function redirectToManagementArea () {

			header('Location: '.$_SERVER['PHP_SELF']."?".self::$action.'='.self::$actionManageGallery);
		}
	}