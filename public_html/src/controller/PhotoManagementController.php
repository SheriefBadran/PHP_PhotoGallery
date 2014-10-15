<?php
	class PhotoManagementController {

		private $photoRepository;
		private $photoManagementView;

		public function __construct (PhotoRepository $photoRepository, PhotoManagementView $photoManagementView) {

			$this->photoRepository = $photoRepository;
			$this->photoManagementView = $photoManagementView;
		}

		public function run () {

			$thumbnails;

			// HARDCODED VALUES, GET FROM EITHER photoManagementView or thumbnailView.
			$thumbnailWidth = 100;

			$thumbnails = $this->photoRepository->toList($thumbnailWidth);
			// $this->photoManagementView->renderThumbNails();
		}
	}