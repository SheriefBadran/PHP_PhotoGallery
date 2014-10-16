<?php

	class ThumbnailModel {

		//TODO: Maybe add original height and witdth.
		private $photoRecord;
		private $uniqueId;
		private $name;
		private $size;
		private $caption;
		private $type;
		private $thumbnailWidth;
		private $thumbnailHeight;

		private static $photoPath = PhotoUploadDestinationPath;
		private static $thumbnailPath = ThumbnailPath;

		private static $absoluteSrcPrefix = "http://localhost:8888/www/git/PHP_PhotoGallery/data/thumbnails";
		private static $src;

		public function __construct (Array $photoRecord, $thumbnailWidth, $mimeType) {

			$this->photoRecord = $photoRecord;
			$this->uniqueId = $photoRecord['uniqueId'];
			$this->name = $photoRecord['name'];
			$this->size = $photoRecord['size'];
			$this->caption = $photoRecord['caption'];
			$this->type = $mimeType;
			$this->thumbnailWidth = $thumbnailWidth;
			$this->src = self::$absoluteSrcPrefix . DS . $this->uniqueId;
			// http://localhost:8888/www/git/PHP_PhotoGallery/data/thumbnails/dee01b789e017ad2663fb170ee9f102628ad4d78.jpg

			$this->createThumbnail();
		}

		// Protected helper functions.
		protected function calculateThumbnailHeight ($photoWidth, $photoHeight) {

			return floor($photoWidth * ($this->thumbnailWidth / $photoWidth));
		}

		protected function createImageIdentifier () {

			if (preg_match('/[.]jpg$/', $this->photoRecord['uniqueId'])) {
				
				$image = imagecreatefromjpeg(PhotoUploadDestinationPath . DS . $this->uniqueId);
			}
			else if (preg_match('/[.]gif$/', $this->uniqueId)) {

				$image = imagecreatefromgif(PhotoUploadDestinationPath . DS . $this->uniqueId);
			}
			else if (preg_match('/[.]png$/', $this->uniqueId)) {

				$image = imagecreatefrompng(PhotoUploadDestinationPath . DS . $this->uniqueId);
			}

			return $image;
		}

		public function createThumbnail() {


			if (file_exists(ThumbnailPath . DS . $this->uniqueId)) {
				
				// var_dump('Thumbnail already created');
				return false;
			}

			if (!file_exists(PhotoUploadDestinationPath . DS . $this->uniqueId)) {
				
				return false;
			}

			$image = $this->createImageIdentifier();

			$imageWidth = imagesx($image);
			$imageHeight = imagesy($image);

			// Calculate the thumbnail height with respect to the desired width.
			$this->thumbnailHeight = $this->calculateThumbnailHeight($imageWidth, $imageHeight);

			$imageTrueColorResource = imagecreatetruecolor($this->thumbnailWidth, $this->thumbnailHeight);

			imagecopyresampled($imageTrueColorResource, $image, 0, 0, 0, 0, $this->thumbnailWidth, $this->thumbnailHeight, $imageWidth, $imageHeight);

			if (!file_exists(ThumbnailPath)) {

				if (mkdir(ThumbnailPath)) {
					
					imagejpeg($imageTrueColorResource, ThumbnailPath .DS. $this->uniqueId);
				}
			}

			imagejpeg($imageTrueColorResource, ThumbnailPath .DS. $this->uniqueId);
		}

		public function getUniqueId () {

			return $this->uniqueId;
		}

		public function getName () {

			return $this->name;
		}

		public function getSize () {

			return $this->size;
		}

		public function getCaption () {

			return $this->caption;
		}

		public function getType () {

			return $this->type;
		}

		public function getThumbnailWidth () {

			return $this->thumbnailWidth;
		}

		public function getSRC () {

			return $this->src;
		}
	}