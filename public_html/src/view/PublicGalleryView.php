<?php

	class PublicGalleryView {


		private $mainView;
		private $paginationView;
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
			$html = '';
			foreach ($thumbnailList->toArray() as $thumbnail) {
				
				$html .= '<a><img src=' . $thumbnail->getSRC() .'></a>';
			}

			$this->paginationView->renderPaginationHTML($paginationModel);

			$this->mainView->echoHTML($html);
		}

		public function getThumbnailWidth () {

			return self::$thumbnailWidth;
		}
	}