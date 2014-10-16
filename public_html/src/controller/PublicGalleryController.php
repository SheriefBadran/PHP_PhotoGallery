<?php

	class PublicGalleryController {

		private $photoRepository;
		private $PaginationRepository;
		private $publicGalleryView;

		public function __construct (PhotoRepository $photoRepository,
									 PaginationRepository $paginationRepository,
									 PublicGalleryView $publicGalleryView) {

			$this->photoRepository = $photoRepository;
			$this->paginationRepository = $paginationRepository;
			$this->publicGalleryView = $publicGalleryView;
		}

		public function run () {

			$thumbnailWidth = $this->publicGalleryView->getThumbnailWidth();
			$totalItems = $this->photoRepository->countAll();
			$paginationModel = $this->paginationRepository->createPaginationModel($totalItems);
			$thumbnailList = $this->photoRepository->toPaginationList($paginationModel, $thumbnailWidth);

			$this->publicGalleryView->renderGallery($thumbnailList, $paginationModel);
		}
	}