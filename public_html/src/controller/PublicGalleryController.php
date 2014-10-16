<?php

	class PublicGalleryController implements iSubscriber {

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
			// exit;
		}

		public function subscribe (Publisher $publisher) {

			$thumbnailWidth = $this->publicGalleryView->getThumbnailWidth();
			$currentPage = $publisher->publishPaginationAction();
			$totalItems = $this->photoRepository->countAll();
			$paginationModel = $this->paginationRepository->createPaginationModel($totalItems, $currentPage);
			$thumbnailList = $this->photoRepository->toPaginationList($paginationModel, $thumbnailWidth);
			$this->publicGalleryView->renderGallery($thumbnailList, $paginationModel);
			exit;
		}
	}