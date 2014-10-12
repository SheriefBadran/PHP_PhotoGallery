<?php
	
	class PhotoRepository extends DatabaseAccessModel {

		protected static $tblName = 'photo';
		protected static $tblFieldGetters = array(
			"name"	  => "getName",
			"size"	  => "getSize",
			"caption" => "getCaption",
			"typeId"  => "getTypeId"
		);

		// Used by parent class.
		protected static $repositoryType = 'PhotoModel';
		private static $parentTblName = 'photoType';
		private static $photoId = 'photoId';
		private static $typeId = 'typeId'; 
		private static $name = 'name';
		private static $size = 'size';
		private static $caption = 'caption';
		private static $fileType = 'type';

		public function createPhotoModel (Array $dataResult, $caption, $photoId = null) {

			if (!is_numeric($photoId) && !is_null($photoId)) {
				
				throw new \Exception('Param $typeId must be an integer.');
			}

			if (!is_string($caption)) {
				
				throw new \Exception('Param $caption must be a string.');
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

			$typeId = (int)($result["typeId"]);

			return new PhotoModel($dataResult, $caption, $typeId, $photoId);
		}
	}