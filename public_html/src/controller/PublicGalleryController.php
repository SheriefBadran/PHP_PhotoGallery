<?php

	class PublicGalleryController implements iSubscriber {

		private $photoRepository;
		private $paginationRepository;
		private $commentRepository;
		private $publicGalleryView;
		private $photoView;

		public function __construct (PhotoRepository $photoRepository,
									 PaginationRepository $paginationRepository,
									 CommentRepository $commentRepository,
									 PublicGalleryView $publicGalleryView,
									 PhotoView $photoView) {

			$this->photoRepository = $photoRepository;
			$this->paginationRepository = $paginationRepository;
			$this->commentRepository = $commentRepository;
			$this->publicGalleryView = $publicGalleryView;
			$this->photoView = $photoView;
		}

		public function run ($currentPage = 1) {

			$thumbnailWidth = $this->publicGalleryView->getThumbnailWidth();
			$totalItems = $this->photoRepository->countAll();

			try {

				$paginationModel = $this->paginationRepository->createPaginationModel($totalItems, $currentPage);
				$thumbnailList = $this->photoRepository->toPaginationList($paginationModel, $thumbnailWidth);
			} 
			catch (Exception $e) {

				$this->publicGalleryView->redirectToFirstPage();
			}

			if ($this->publicGalleryView->userClickPhoto()) {

				$uniquePhotoId = $this->publicGalleryView->getClickedPhotoId();
				$this->showPhoto($uniquePhotoId);
				exit;
			}

			$this->publicGalleryView->renderGallery($thumbnailList, $paginationModel);
			exit;
		}

		// Subscribes on user input from the pagination.
		public function subscribe (Publisher $publisher) {

			$currentPage = $publisher->publishPaginationAction();
			$this->run($currentPage);
		}

		public function showPhoto ($uniquePhotoId) {
			
			// Using PRG pattern - if POST,- REDIRECT and then GET.
			if ($this->photoView->userClickSubmitCommentButton()) {
				
				$photoId = $this->photoRepository->getPhotoId($uniquePhotoId);
				$commentAuthor = $this->photoView->getAuthor();
				$commentText = $this->photoView->getComment();

				if (!$commentAuthor || !$commentText) {
					
					$photo = $this->photoRepository->getPhoto($uniquePhotoId);
					$this->photoView->renderPhoto($photo);
					exit;
				}

				$this->commentRepository->insert(new CommentModel($commentAuthor, $commentText, $photoId));

				// TODO: Deligate the redirect to a view.
				header('Location: '.$_SERVER['REQUEST_URI']);
				$photo = $this->photoRepository->getPhoto($uniquePhotoId);
				$this->photoView->renderPhoto($photo);
				exit;
			}

			$photo = $this->photoRepository->getPhoto($uniquePhotoId);

			// If there of some reason is no photo returned - redirect!
			if (is_null($photo)) {

				$this->publicGalleryView->redirectToFirstPage();
				exit;
			}

			$this->photoView->renderPhoto($photo);
		}
	}