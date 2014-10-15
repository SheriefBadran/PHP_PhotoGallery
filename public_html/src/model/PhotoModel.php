<?php

	class PhotoModel {

		private $photoId;
		private $uniqueId;
		private $name;
		private $size;
		private $caption;
		private $typeId;
		private $comments;

		private static $dirPath = PhotoUploadDestinationPath;

		protected static $actualFileName = 'uploadedFileName';
		protected static $fileSize = 'size';

		public function __construct (Array $photoData, $caption, $typeId, $uniqueId, $photoId = null) {

			if (!is_numeric($photoId) && !is_null($photoId)) {
				
				throw new \Exception('Param $photoId must be an integer.');
			}

			if (!is_string($uniqueId)) {
				
				throw new \Exception('Param $uniqueId must be a string.');
			}

			if (!is_numeric($typeId)) {
				
				throw new \Exception('Param $typeId must be an integer.');
			}

			if (!is_string($caption)) {
				
				throw new \Exception('Param $caption must be a string.');
			}

			$this->photoId = $photoId;
			$this->uniqueId = $uniqueId;
			$this->typeId = $typeId;
			$this->name = $photoData[self::$actualFileName];
			$this->size = $photoData[self::$fileSize];
			$this->caption = $caption;
		}

		public function setPhotoId ($photoId) {

			if (!is_numeric($photoId)) {
				
				throw new \Exception('Param $photoId in setter must be an integer.');
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

		public function getPath () {

			return self::$dirPath;
		}
	}