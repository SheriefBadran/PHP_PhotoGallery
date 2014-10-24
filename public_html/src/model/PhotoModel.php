<?php

	class PhotoModel {

		private $photoId;
		private $uniqueId;
		private $name;
		private $size;
		private $caption;
		private $typeId;
		private $comments;
		private $src;

		private static $id = 'uniqueId';
		private static $photoName = 'name';
		private static $photoTypeId = 'typeId';
		private static $photoSize = 'size';
		private static $photoCaption = 'caption';

		private static $numericArgumentException = 'Param has to be an integer.';
		private static $stringArgumentException = 'Param has to be a string.';

		private static $dirPath = PhotoUploadDestinationPath;

		// For local environment
		private static $localhostURL = LocalPhotosURL;

		// For server environment
		private static $serverURL = ServerPhotosURL;

		protected static $actualFileName = 'uploadedFileName';
		protected static $fileSize = 'size';

		public function __construct (Array $photoProperties, $photoId = null) {

			if (!is_numeric($photoId) && !is_null($photoId)) {
				
				throw new \Exception(self::$numericArgumentException);
			}

			if (!is_string($photoProperties[self::$id])) {
				
				throw new \Exception(self::$stringArgumentException);
			}

			if (!is_numeric($photoProperties[self::$photoTypeId])) {
				
				throw new \Exception(self::$numericArgumentException);
			}

			if (!is_string($photoProperties[self::$photoCaption])) {
				
				throw new \Exception(self::$stringArgumentException);
			}

			// BAD string dependency!!
			$this->photoId = $photoId;
			$this->uniqueId = $photoProperties[self::$id];
			$this->typeId = $photoProperties[self::$photoTypeId];
			$this->name = $photoProperties[self::$photoName];
			$this->size = $photoProperties[self::$photoSize];
			$this->caption = $photoProperties[self::$photoCaption];
			$this->comments = new CommentList();

			$this->src = ($_SERVER['HTTP_HOST'] === 'localhost:8888') ? self::$localhostURL . DS . $this->uniqueId :
																		self::$serverURL . DS . $this->uniqueId;
		}

		public function setPhotoId ($photoId) {

			if (!is_numeric($photoId)) {
				
				throw new \Exception(self::$numericArgumentException);
			}

			$this->photoId = $photoId;
		}

		public function getPhotoId () {

			return $this->photoId;
		}

		public function getUniqueId () {

			return $this->uniqueId;
		}

		public function getTypeId () {

			return $this->typeId;
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

		public function getComments () {

			return $this->comments;
		}

		public function getSRC () {

			return $this->src;
		}

		public function addComment (CommentModel $comment) {

			$this->comments->add($comment);
		}
	}