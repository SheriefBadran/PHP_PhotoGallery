<?php

	class PhotoModel {

		private $photoId;
		private $typeId;
		private $name;
		private $size;
		private $caption;
		private $comments;

		protected static $actualFileName = 'uploadedFileName';
		protected static $fileSize = 'size';

		public function __construct (Array $photoData, $caption, $typeId, $photoId = null) {

			if (!is_numeric($photoId) && !is_null($photoId)) {
				
				throw new \Exception('Param $photoId must be an integer.');
			}

			if (!is_numeric($typeId)) {
				
				throw new \Exception('Param $typeId must be an integer.');
			}

			if (!is_string($caption)) {
				
				throw new \Exception('Param $caption must be a string.');
			}

			$this->photoId = $photoId;
			$this->typeId = $typeId;
			$this->name = $photoData[self::$actualFileName];
			$this->size = $photoData[self::$fileSize];
			$this->caption = $caption;
		}

		public function setPhotoId ($photoId) {

			$this->photoId = $photoId;
		}

		public function getPhotoId () {

			return $this->photoId;
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
	}