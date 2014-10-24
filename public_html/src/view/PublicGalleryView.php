<?php

	class PublicGalleryView extends Publisher {


		private $mainView;
		private $paginationView;

		private static $uniquePhotoGetIndex = 'name';
		private static $thumbnailWidth = 200;
		private static $noPhotosInGalleryResponseMessage = 'Ooops! The photo gallery seem to suffer a serious lack of photos!';

		public function __construct (HTMLview $mainView, PaginationView $paginationView) {

			$this->mainView = $mainView;
			$this->paginationView = $paginationView;
		}

		public function renderEmptyGalleryManagementHTML () {


			$html = '<div class="isa_info">';
			$html .= '<i class="fa fa-info-circle"></i>';
			$html .=  	self::$noPhotosInGalleryResponseMessage;
			$html .= '</div>';
			// $html = '<p>' . self::$noPhotosInGalleryResponseMessage . '</p>';

			return $html;
		}

		public function renderEmptyGalleryManagement () {

			$emptyGalleryHTML = $this->renderEmptyGalleryManagementHTML();
			$this->mainView->echoHTML($emptyGalleryHTML);
		}

		public function renderGallery (ThumbnailList $thumbnailList, PaginationModel $paginationModel) {

			// 1. First render html for all photos with caption and everything.
			// 2. Then render html for the pagination itself.

			$html = '<section id="images">';
			foreach ($thumbnailList->toArray() as $thumbnail) {

				$uniqueId = htmlspecialchars(urlencode($thumbnail->getUniqueId()));
				$caption = htmlspecialchars($thumbnail->getCaption());
				$html .= '<a title="' . $caption . '" href=index.php?page='.$paginationModel->getCurrentPage().'&name='.$uniqueId.'><img src=' . $thumbnail->getSRC() .'></a>';
			}
			$html .= '<div class="clear"></div>';
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

				return filter_var($_GET[self::$uniquePhotoGetIndex], FILTER_SANITIZE_STRING);
			}
		}
	}