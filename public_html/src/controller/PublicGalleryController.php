<?php

	class PublicGalleryController {

		private $photoRepository;
		private $publicGalleryView;

		public function __construct (PhotoRepository $photoRepository, PublicGalleryView $publicGalleryView) {

			$this->photoRepository = $photoRepository;
			$this->publicGalleryView = $publicGalleryView;
		}

		public function run () {

			

			$totalItems = $this->photoRepository->countAll();

		}
	}