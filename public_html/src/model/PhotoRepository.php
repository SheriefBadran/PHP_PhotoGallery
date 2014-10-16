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

		protected static $id;
		
		// Used by parent class.
		protected static $repositoryType = 'PhotoModel';
		private static $parentTblName = 'photoType';
		protected static $actualFileName = 'uploadedFileName';
		private static $photoId = 'photoId';
		private static $uniqueId = 'uniqueId';
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

		public function deletePhoto ($uniqueId) {

			try {

				$db = $this->dbFactory->createInstance();

				$sql = "DELETE FROM " . self::$tblName;
				$sql .= " WHERE " . self::$uniqueId . " = (?)";
				$query = $db->prepare($sql);
				$result = $query->execute(array($uniqueId));

				return $result;	
			} 
			catch (PDOException $e) {
				
				throw new \Exception($e->getMessage(), (int)$e->getCode());
			}
		}

		/**
		* Pupulates a list with thumbnail objects
		* photo object and other data it needs. Save all the thumbnailobjects to a list and return it.
		*
		* @param Numeric thumbnailWidth.
		*
		* @return ThumbnailModel List $thumbnails
		*/
		public function toList ($thumbnailWidth) {

			// create both photoModel and thumbnailModel and populate them here.
			try {

				$result = $this->fetchAllAssoc();
			}
			catch (EmptyRecordException $e) {

				throw new EmptyRecordException($e);
			}
			catch (DatabaseErrorException $e) {

				throw $e;
			}
			catch (Exception $e) {

				throw $e->getMessage();
			}

			$db = $this->dbFactory->createInstance();
			$sql = "SELECT " . self::$fileType .  " FROM " . self::$parentTblName;
			$sql .= " WHERE " . self::$typeId . " = (?)";
			$query = $db->prepare($sql);
			
			foreach ($result as $photoRecord) {

				try {

					$query->execute(array($photoRecord["typeId"]));
					$typeResult = $query->fetch();
				}
				catch (Exception $e) {

					throw new \Exception($e->getMessage(), (int)$e->getCode());
				}

				$this->thumbnails->add(new ThumbnailModel($photoRecord, $thumbnailWidth, $typeResult['type']));
			}

			return $this->thumbnails;
		}
	}