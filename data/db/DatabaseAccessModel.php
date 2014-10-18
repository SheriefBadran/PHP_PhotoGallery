<?php

require_once(HelperPath.DS.'db/DB_Factory.php');

	class DatabaseAccessModel {

		// private static $dbUsername = "129463_ew31819";
		// private static $dbPassword = "Qy.q55fc";
		// private static $connectionString = 'mysql:host=photogallery-129463.mysql.binero.se;dbname=129463-photogallery';
		private static $dbUsername = "root";
		private static $dbPassword = "root";
		private static $connectionString = 'mysql:host=localhost;dbname=PhotoGallery';
		protected $dbFactory;

		private static $fields = "fields";
		private static $paramPlaceHolder = "paramPlaceHolder";

		private static $emptyRecordException = "There are zero results to fetch.";
		private static $databaseFetchAllErrorException = "fetchAll failed to fetch results";
		private static $databaseFetchErrorException = "fetch failed to fetch results";

		public function __construct () {

			$dbAbstactFactory = new DB_Factory();
			$this->dbFactory = $dbAbstactFactory::getFactory(self::$dbUsername, self::$dbPassword, self::$connectionString);
		}

		// Helper methods.
		protected function retrieveObjectGetters (Array $objectGetters, Array $objectMethods) {

			$getterMethods = array();
			foreach ($objectGetters as $objectGetter) {

				foreach ($objectMethods as $objectMethod) {
					
					if ($objectMethod === $objectGetter) {
						
						$getterMethods[] = $objectMethod;
					}
				}
			}

			return $getterMethods;
		}

		protected function getObjectAttributeValues (Array $getterMethods, $object) {

			$params = array();
			foreach ($getterMethods as $getter) {
				
				$params[] = call_user_func(array($object, $getter));
			}

			return $params;
		}

		protected function composePDOqueryComponents (Array $objectProperties) {

			$tblFields = " (";
			$paramString = "(";

			$methods = get_class_methods(static::$repositoryType);

			for ($i = 0; $i < count($objectProperties); $i++) {
				
				$tblFields .= ($i === count($objectProperties) - 1) ? $objectProperties[$i] 
																	 : $objectProperties[$i] . ", ";

				$paramString .= ($i === count($objectProperties) - 1) ? "?" : "?,";
			}

			$tblFields .= ") ";
			$paramString .= ")";

			return array(
				self::$fields 			=> $tblFields,
				self::$paramPlaceHolder => $paramString 
			);
		}

		public function insert ($object) {

			$objectMethods = get_class_methods($object);
			$objectGetters = array_values(static::$tblFieldGetters);

			$properties = array_keys(static::$tblFieldGetters);
			

			$getters = $this->retrieveObjectGetters($objectGetters, $objectMethods);
			$queryComponents = $this->composePDOqueryComponents($properties);
			$params = $this->getObjectAttributeValues($getters, $object);

			try {

				$db = $this->dbFactory->createInstance();
				$sql = "INSERT INTO " . static::$tblName;
				$sql .= $queryComponents[self::$fields] . "VALUES " . $queryComponents[self::$paramPlaceHolder];
				$query = $db->prepare($sql);
				$result = $query->execute($params);

				return $result;
			}
			catch (PDOException $e) {

				// Check SQL STATE 23000 (Photo name already exist in database).
				if ((int)$e->getCode() === 23000) {
					
					// TODO: BAD CODE, CHANG THIS, ALSO IN PhotoUploadController!
					throw new PhotoNameAlreadyExistException("A photo with the name " . $object->getName() . " is already uploaded.");
				}

				throw new \Exception($e->getMessage(), (int)$e->getCode());
			}
		}

		public function fetchAllAssoc () {

			try {

				$db = $this->dbFactory->createInstance();

				$sql = "SELECT * FROM " . static::$tblName;
				$query = $db->prepare($sql);
				$query->execute();
				$result = $query->fetchAll();

				if (empty($result)) {
					
					throw new EmptyRecordException(self::$emptyRecordException);
				}

				if (!$result) {
					
					throw new DatabaseErrorException(self::$databaseFetchAllErrorException);
				}

				return $result;
			} 
			catch (PDOException $e) {

				throw new \Exception($e->getMessage(), (int)$e->getCode());
			}
		}

		public function countAll () {

			try {
				
				$db = $this->dbFactory->createInstance();

				$sql = "SELECT COUNT(*) FROM " . static::$tblName;
				$query = $db->prepare($sql);
				$query->execute();
				$result = $query->fetch();

				if (empty($result)) {
					
					throw new EmptyRecordException(self::$emptyRecordException);
				}

				if (!$result) {
					
					throw new DatabaseErrorException(self::$databaseFetchErrorException);
				}

				$sum = (int)array_shift($result);
				return $sum;
			} 
			catch (PDOException $e) {
				
				throw new \Exception($e->getMessage(), (int)$e->getCode());
			}
		}
	}