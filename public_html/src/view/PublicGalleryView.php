<?php

	class PublicGalleryView extends Publisher {


		private $mainView;
		private $paginationView;

		private static $uniquePhotoGetIndex = 'name';
		private static $thumbnailWidth = 100;
		private static $noPhotosInGalleryResponseMessage = 'Ooops! The photo gallery seem to suffer a serious lack of photos!';

		public function __construct (HTMLview $mainView, PaginationView $paginationView) {

			$this->mainView = $mainView;
			$this->paginationView = $paginationView;
		}

		public function renderEmptyGalleryManagementHTML () {

			$html .= '<p>' . self::$noPhotosInGalleryResponseMessage . '</p>';

			return $html;
		}

		public function renderGallery (ThumbnailList $thumbnailList, PaginationModel $paginationModel) {

			// 1. First render html for all photos with caption and everything.
			// 2. Then render html for the pagination itself.

			$html = '<section id="images">';
			foreach ($thumbnailList->toArray() as $thumbnail) {


				$html .= '<a title="' . $thumbnail->getCaption() . '" href=index.php?page='.$paginationModel->getCurrentPage().'&name='.$thumbnail->getUniqueId().'><img src=' . $thumbnail->getSRC() .'></a>';
			}
			$html .= '</section>';

			$paginationHTML = $this->paginationView->renderPaginationHTML($paginationModel);

			$paginationHTML === false ? $this->mainView->echoHTML($html) : $this->mainView->echoHTML($html . $paginationHTML);
		}

		public function getThumbnailWidth () {

			return self::$thumbnailWidth;
		}

		public function redirectToFirstPage () {

			$this->paginationView->redirectToFirstPage();
		}

		public function redirectToCurrentPage () {

			$this->paginationView->redirectToCurrentPage();
		}

		public function userClickPhoto () {

			return isset($_GET[self::$uniquePhotoGetIndex]);
		}

		public function getClickedPhotoId () {

			if (isset($_GET[self::$uniquePhotoGetIndex])) {
				
				return $_GET[self::$uniquePhotoGetIndex];
			}
		}
	}