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
		private static $childTblName = 'comment';
		protected static $actualFileName = 'uploadedFileName';
		private static $photoId = 'photoId';
		private static $uniqueId = 'uniqueId';
		private static $typeId = 'typeId';
		private static $name = 'name';
		private static $size = 'size';
		private static $caption = 'caption';
		private static $fileType = 'type';

		private static $argumentIntException = "Param must be an integer.";
		private static $argumentStringException = "Param must be an integer.";
		private static $emptyRecordException = "There are zero results to fetch.";
		private static $databaseFetchAllErrorException = "fetchAll failed to fetch results";
		private static $databaseFetchErrorException = "fetch failed to fetch results";

		public function __construct (ThumbnailList $thumbnails) {

			// Call parent ctor to initialize database connection. If no ctor exist in this class, 
			// parent ctor is called automatically.
			parent::__construct();
			$this->thumbnails = $thumbnails;
		}

		public function createPhotoModel (Array $photoProperties, $photoId = null) {

			if (!is_numeric($photoId) && !is_null($photoId)) {
				
				throw new \Exception(self::$argumentIntException);
			}

			if (!is_string($photoProperties[self::$caption])) {
				
				throw new \Exception(self::$argumentStringException);
			}

			if (!is_string($photoProperties[self::$uniqueId])) {
				
				throw new \Exception(self::$argumentStringException);
			}

			try {

				$db = $this->dbFactory->createInstance();

				$sql = "SELECT " . self::$typeId . " FROM " . self::$parentTblName . " WHERE " . self::$fileType . " = ?";
				$params = array($photoProperties[self::$fileType]);
				$query = $db->prepare($sql);
				$query->execute($params);
				$result = $query->fetch();
			}
			catch (PDOException $e) {

				throw new \Exception($e->getMessage(), (int)$e->getCode());
			}

			if (!$result) { return null; }

			$photoProperties[self::$typeId] = (int)($result[self::$typeId]);

			return new PhotoModel($photoProperties, $photoId);
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

					$query->execute(array($photoRecord[self::$typeId]));
					$typeResult = $query->fetch();
				}
				catch (Exception $e) {

					throw new DatabaseErrorException($e->getMessage(), (int)$e->getCode());
				}

				$this->thumbnails->add(new ThumbnailModel($photoRecord, $thumbnailWidth, $typeResult[self::$fileType]));
			}

			return $this->thumbnails;
		}

		public function toPaginationList (PaginationModel $paginationModel, $thumbnailWidth) {

			try {
				
				$db = $this->dbFactory->createInstance();

				$limit = $paginationModel->getItemsForEachPage();
				$offset = $paginationModel->getSQLOffset();

				$sql ='SELECT * FROM ' . self::$tblName . ' LIMIT :limit OFFSET :offset';

				/*** run the query ***/
				$query = $db->prepare($sql);
				$query->bindParam(':limit', $limit, PDO::PARAM_INT);
				$query->bindParam(':offset', $offset, PDO::PARAM_INT);
				$query->execute();
				$result = $query->fetchAll(PDO::FETCH_ASSOC);

				if (empty($result)) {
					
					throw new EmptyRecordException(self::$emptyRecordException);
				}

				if (!$result) {
					
					throw new DatabaseErrorException(self::$databaseFetchAllErrorException);
				}

			} 
			catch (PDOException $e) {

				throw new \Exception($e->getMessage(), (int)$e->getCode());
			}

			$sql = "SELECT " . self::$fileType .  " FROM " . self::$parentTblName;
			$sql .= " WHERE " . self::$typeId . " = (?)";
			$query = $db->prepare($sql);
			
			foreach ($result as $photoRecord) {

				try {

					$query->execute(array($photoRecord[self::$typeId]));
					$typeResult = $query->fetch();

					if (empty($typeResult)) {
						
						throw new EmptyRecordException(self::$emptyRecordException);
					}

					if (!$typeResult) {
						
						throw new DatabaseErrorException(self::$databaseFetchErrorException);
					}
				}
				catch (Exception $e) {

					throw new \Exception($e->getMessage(), (int)$e->getCode());
				}

				$this->thumbnails->add(new ThumbnailModel($photoRecord, $thumbnailWidth, $typeResult[self::$fileType]));
			}

			return $this->thumbnails;
		}

		public function getPhoto ($uniqueId) {

			try {
			
				$db = $this->dbFactory->createInstance();
				$sql = "SELECT * FROM " . self::$tblName . " WHERE " . self::$uniqueId . " = ?";
				$params = array($uniqueId);
				$query = $db->prepare($sql);
				$query->execute($params);
				$result = $query->fetch();

				if ($result) {

					// Get all the comments belonging to the photo.
					// Pupulate photo->comments with the comments.

					$photo = new PhotoModel($result, $result[self::$photoId]);

					$sql = "SELECT * FROM ". self::$childTblName. " WHERE " . self::$photoId . " = ?";
					$query = $db->prepare($sql);
					$query->execute(array($result[self::$photoId]));
					$comments = $query->fetchAll();

					foreach ($comments as $comment) {

						$photo->addComment(new CommentModel(
							$comment['created'],
							$comment['author'],
							$comment['text'],
							$comment['photoId'],
							$comment['commentId']
						));
					}

					return $photo;
				}

				return null;
			} 
			catch (PDOException $e) {
				
				throw new Exception($e->getMessage(), (int)$e->getCode());
			}
		}
	}