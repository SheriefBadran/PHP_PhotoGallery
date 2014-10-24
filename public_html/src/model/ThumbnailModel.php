<?php

	class ThumbnailModel {

		//TODO: Maybe add original height and witdth.
		private $uniqueId;
		private $name;
		private $size;
		private $formattedSize; // Readable format
		private $caption;
		private $type;
		private $thumbnailWidth;
		private $thumbnailHeight;

		private static $jpgTypeRegex = '/[.]jpg$/';
		private static $gifTypeRegex = '/[.]gif$/';
		private static $pngTypeRegex = '/[.]png$/';
		private static $photoPath = PhotoUploadDestinationPath;
		private static $thumbnailPath = ThumbnailPath;

		private static $id = 'uniqueId';
		private static $photoName = 'name';
		private static $fileSize = 'size';
		private static $photoCaption = 'caption';

		// For local environment
		private static $localhostURL = LocalThumbnailsURL;

		// For server environment
		private static $serverURL = ServerThumbnailsURL;

		private $src;

		public function __construct (Array $photoRecord, $thumbnailWidth, $mimeType) {

			// TODO: Bad string dependency from the array. Make a better solution.
			$this->uniqueId = $photoRecord[self::$id];
			$this->name = $photoRecord[self::$photoName];
			$this->size = $photoRecord[self::$fileSize];
			$this->caption = $photoRecord[self::$photoCaption];
			$this->type = $mimeType;
			$this->thumbnailWidth = $thumbnailWidth;

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

			if (preg_match(self::$jpgTypeRegex, $this->uniqueId)) {
				
				$image = imagecreatefromjpeg(PhotoUploadDestinationPath . DS . $this->uniqueId);
			}
			else if (preg_match(self::$gifTypeRegex, $this->uniqueId)) {

				$image = imagecreatefromgif(PhotoUploadDestinationPath . DS . $this->uniqueId);
			}
			else if (preg_match(self::$pngTypeRegex, $this->uniqueId)) {

				$image = imagecreatefrompng(PhotoUploadDestinationPath . DS . $this->uniqueId);
			}
			else {

				$image = null;
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

			if (is_null($image)) {
				
				throw new \Exception('ThumbnailModel could not create image identifier.');
			}

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