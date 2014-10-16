<?php

	class ThumbnailModel {

		//TODO: Maybe add original height and witdth.
		private $photoRecord;
		private $uniqueId;
		private $name;
		private $size;
		private $formattedSize;
		private $caption;
		private $type;
		private $thumbnailWidth;
		private $thumbnailHeight;

		private static $photoTypeRegex = '/[.]jpg$/';
		private static $photoPath = PhotoUploadDestinationPath;
		private static $thumbnailPath = ThumbnailPath;

		// For local environment
		private static $localhostURL = LocalURL;

		// For server environment
		private static $serverURL = ServerURL;

		private $src;

		public function __construct (Array $photoRecord, $thumbnailWidth, $mimeType) {

			$this->photoRecord = $photoRecord;
			$this->uniqueId = $photoRecord['uniqueId'];
			$this->name = $photoRecord['name'];
			$this->size = $photoRecord['size'];
			$this->caption = $photoRecord['caption'];
			$this->type = $mimeType;
			$this->thumbnailWidth = $thumbnailWidth;

			if ($_SERVER['HTTP_HOST'] === 'localhost:8888') {
				

			}

			$this->src = ($_SERVER['HTTP_HOST'] === 'localhost:8888') ? self::$localhostURL . DS . $this->uniqueId :
																		self::$serverURL . DS . $this->uniqueId;

			$this->formattedSize = $this->setFormattedSize();
			$this->createThumbnail();
		}

		// Protected helper functions.
		protected function calculateThumbnailHeight ($photoWidth, $photoHeight) {

			return floor($photoWidth * ($this->thumbnailWidth / $photoWidth));
		}

		protected function createImageIdentifier () {

			if (preg_match(self::$photoTypeRegex, $this->uniqueId)) {
				
				$image = imagecreatefromjpeg(PhotoUploadDestinationPath . DS . $this->uniqueId);
			}
			else if (preg_match($photoTypeRegex, $this->uniqueId)) {

				$image = imagecreatefromgif(PhotoUploadDestinationPath . DS . $this->uniqueId);
			}
			else if (preg_match($photoTypeRegex, $this->uniqueId)) {

				$image = imagecreatefrompng(PhotoUploadDestinationPath . DS . $this->uniqueId);
			}

			return $image;
		}

		public function setFormattedSize () {


			if ($this->size < 1024) {

				$formattedSize = "$this->size bytes";
			}
			else if ($this->size < 1048576) {

				$sizeInKb = round($this->size/1024);
				$formattedSize = "$sizeInKb KB";
			}
			else {

				$sizeInMb = round($this->size/1048576, 1);
				$formattedSize = "$sizeInMb MB";
			}

			return $formattedSize;
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

		public function getFormattedSize () {

			return $this->formattedSize;
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