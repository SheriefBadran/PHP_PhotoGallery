<?php
	
	class PhotoRepository extends DatabaseAccessModel {

		private $thumbnails;

		protected static $tblName = 'photo';
		protected static $tblFieldGetters = array(
			"uniqueId" => "getUniqueId",
			"name"	   => "getName",
			"size"	   => "getSize",
			"caption"  => "getCaption",
			"typeId"   => "getTypeId"
		);
		
		// Used by parent class.
		protected static $repositoryType = 'PhotoModel';
		private static $parentTblName = 'photoType';
		protected static $actualFileName = 'uploadedFileName';
		private static $photoId = 'photoId';
		private static $typeId = 'typeId'; 
		private static $name = 'name';
		private static $size = 'size';
		private static $caption = 'caption';
		private static $fileType = 'type';

		public function __construct (ThumbnailList $thumbnails) {

			// Call parent ctor to initialize database connection. If no ctor exist in this class, 
			// parent ctor is called automatically.
			parent::__construct();
			$this->thumbnails = $thumbnails;
		}

		public function createPhotoModel (Array $dataResult, $caption, $uniqueId, $photoId = null) {

			if (!is_numeric($photoId) && !is_null($photoId)) {
				
				throw new \Exception('Param $typeId must be an integer.');
			}

			if (!is_string($caption)) {
				
				throw new \Exception('Param $caption must be a string.');
			}

			if (!is_string($uniqueId)) {
				
				throw new \Exception('Param $uniqueId must be a string.');
			}

			try {

				$db = $this->dbFactory->createInstance();

				$sql = "SELECT " . self::$typeId . " FROM " . self::$parentTblName . " WHERE " . self::$fileType . " = ?";
				$params = array($dataResult[self::$fileType]);
				$query = $db->prepare($sql);
				$query->execute($params);
				$result = $query->fetch();
			}
			catch (PDOException $e) {

				throw new \Exception($e->getMessage(), (int)$e->getCode());
			}

			if (!$result) { return null; }

			$typeId = (int)($result["typeId"]);

			return new PhotoModel($dataResult, $caption, $typeId, $uniqueId, $photoId);
		}

		/**
		* Creates photo object and populates it, then creates a thumbnailobject and populate it with the
		* photo object and other data it needs. Save all the thumbnailobjects to a list and return it.
		*
		* @param ThumbnailModel $photo.
		*
		* @return Boolean
		*/
		public function toList ($thumbnailWidth) {

			// create both photoModel and thumbnailModel and populate them here.
			$db = $this->dbFactory->createInstance();
			$result = $this->fetchAllAssoc();

			// LOOP START: over record
				// 1. For each photo: create a thumbnail object and populate it with the data it needs.

				// 2. The thumbnail constructor fires of the thumbnail work.

				// 3. Save the new thumbnail object to the thumbnails list.
			// LOOP END

			// 4. RETURN THE LIST.

			foreach ($result as $photoRecord) {

				$this->thumbnails->add(new ThumbnailModel($photoRecord, $thumbnailWidth));
			}
		}
	}