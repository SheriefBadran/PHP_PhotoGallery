<?php

	class ThumbnailModel {

		private $photoRecord;
		private $thumbnailWidth;
		private $thumbnailHeight;

		private static $photoPath = PhotoUploadDestinationPath;
		private static $thumbnailPath = ThumbnailPath;

		public function __construct (Array $photoRecord, $thumbnailWidth) {

			$this->photoRecord = $photoRecord;
			$this->thumbnailWidth = $thumbnailWidth;

			$this->createThumbnail();
		}

		public function createThumbnail() {

			$thumbnailsExceedPhotos = false;

			if (file_exists(ThumbnailPath . DS . $this->photoRecord['uniqueId'])) {
				
				var_dump('Thumbnail already created');
				return false;
			}

			if (!file_exists(PhotoUploadDestinationPath . DS . $this->photoRecord['uniqueId'])) {
				
				return false;
			}

			if (preg_match('/[.]jpg$/', $this->photoRecord['uniqueId'])) {
				
				$image = imagecreatefromjpeg(PhotoUploadDestinationPath . DS . $this->photoRecord['uniqueId']);
			}
			else if (preg_match('/[.]gif$/', $this->photoRecord['uniqueId'])) {

				$image = imagecreatefromgif(PhotoUploadDestinationPath . DS . $this->photoRecord['uniqueId']);
			}
			else if (preg_match('/[.]png$/', $this->photoRecord['uniqueId'])) {

				$image = imagecreatefrompng(PhotoUploadDestinationPath . DS . $this->photoRecord['uniqueId']);
			}

			$imageWidth = imagesx($image);
			$imageHeight = imagesy($image);

			// Calculate the thumbnail height with respect to the desired width.
			$this->thumbnailHeight = $this->calculateThumbnailHeight($imageWidth, $imageHeight);

			$imageTrueColorResource = imagecreatetruecolor($this->thumbnailWidth, $this->thumbnailHeight);
			imagecopyresized($imageTrueColorResource, $image, 0, 0, 0, 0, $this->thumbnailWidth, $this->thumbnailHeight, $imageWidth, $imageHeight);

			if (!file_exists(ThumbnailPath)) {

				if (mkdir(ThumbnailPath)) {
					
					imagejpeg($imageTrueColorResource, ThumbnailPath .DS. $this->photoRecord['uniqueId']);
				}
			}

			imagejpeg($imageTrueColorResource, ThumbnailPath .DS. $this->photoRecord['uniqueId']);
		}

		public function getUniqueId () {

			return $this->photoRecord['uniqueId'];
		}

		protected function calculateThumbnailHeight ($photoWidth, $photoHeight) {

			return floor($photoWidth * ($this->thumbnailWidth / $photoWidth));
		}


	}