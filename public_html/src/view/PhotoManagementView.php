<?php
	class PhotoManagementView extends Publisher {

		private $mainView;
		private $sessionModel;

		private static $adminThumbnailWidth = 100;

		private static $action = 'action';
		private static $name = 'name';
		private static $actionUpload = 'upload';
		private static $actionManageGallery = 'manage';
		private static $actionDelete = 'delete';
		private static $actionErrorlog = 'errorlog';
		private static $actionLogout = 'logout';
		private static $actionEditComments = 'editcomments';
		private static $actionDeletePhoto = 'delete';
		private static $setDeleteSuccessMessage = 'The photo was successfully deleted.';
		private static $noPhotosInGalleryResponseMessage = 'Ooops! Not even one photo in the gallery. Go ahead and upload!';

		public function __construct (HTMLview $mainView, SessionModel $sessionModel) {

			$this->mainView = $mainView;
			$this->sessionModel = $sessionModel;
		}

		public function renderEmptyGalleryManagementHTML () {

			$html = '<nav>';
			$html .= 	'<ul>';
			$html .= 		'<li><a href=?' . self::$action . "=" . self::$actionUpload . '>Upload new photo</a></li>';
			$html .= 		'<li><a href=?' . self::$action . "=" . self::$actionManageGallery . '>Manage Photo\'s</a></li>';
			$html .= 		'<li><a href=?' . self::$action . "=" . self::$actionErrorlog . '>View error log</a></li>';
			$html .= 		'<li><a href=?' . self::$action . "=" . self::$actionLogout . '>Logout</a></li>';
			$html .= 	'</ul>';
			$html .= '</nav>';

			$html .= '<p>' . self::$noPhotosInGalleryResponseMessage . '</p>';

			return $html;
		}

		public function renderPhotoManagementHTML (Array $thumbnails) {

			$uploadConfirmMessage = $this->sessionModel->getPhotoUploadSuccessMessage();
			$deleteConfirmMessage = $this->sessionModel->getPhotoDeleteSuccessMessage();

			$this->sessionModel->resetPhotoUploadSuccessMessage();
			$this->sessionModel->resetPhotoDeleteSuccessmessage();

			$html = '<nav>';
			$html .= 	'<ul>';
			$html .= 		'<li><a href=?' . self::$action . "=" . self::$actionUpload . '>Upload new photo</a></li>';
			$html .= 		'<li><a href=?' . self::$action . "=" . self::$actionManageGallery . '>Manage Photo\'s</a></li>';
			$html .= 		'<li><a href=?' . self::$action . "=" . self::$actionErrorlog . '>View error log</a></li>';
			$html .= 		'<li><a href=?' . self::$action . "=" . self::$actionLogout . '>Logout</a></li>';
			$html .= 	'</ul>';
			$html .= '</nav>';

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
				$html .=		'<a href=?' . self::$action . "=" . self::$actionEditComments . '>';
				$html .= 			'Edit Comments';
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

			$managementHTML = $this->renderPhotoManagementHTML($thumbnailList->toArray());
			$this->mainView->echoHTML($managementHTML);
		}

		public function renderEmptyPhotoManagement () {

			$emptyManagementHTML = $this->renderEmptyGalleryManagementHTML();
			$this->mainView->echoHTML($emptyManagementHTML);
		}

		public function getAdminThumbnailWidth () {

			return self::$adminThumbnailWidth;
		}

		public function updateDeleteAction () {

			if (isset($_GET[self::$action]) && $_GET[self::$action] === self::$actionDelete) {
				
				$this->actions = $_GET[self::$action];
				$this->notify();
			}
		}

		public function publishDeleteAction () {

			return $this->actions;
		}

		public function getPhotoToDelete () {

			if (isset($_GET[self::$action]) && isset($_GET[self::$name])) {
				
				return $_GET[self::$name];
			}
		}

		public function setPhotoDeleteSuccessMessage () {

			$this->sessionModel->setPhotoDeleteSuccessMessage(self::$setDeleteSuccessMessage);
		}

		public function resetPhotoDeleteSuccessMessage () {

			$this->sessionModel->resetPhotoDeleteSuccessMessage();
		}

		public function redirect ($path) {

			header('Location: '.$path);
		}
	}