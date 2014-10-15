<?php

require_once(HelperPath.DS.'db/DB_Factory.php');

	class DatabaseAccessModel {

		private static $dbUsername = "129463_ew31819";
		private static $dbPassword = "Qy.q55fc";
		private static $connectionString = 'mysql:host=photogallery-129463.mysql.binero.se;dbname=129463-photogallery';
		protected $dbFactory;
		private static $fields = "fields";
		private static $paramPlaceHolder = "paramPlaceHolder";

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
					
					throw new PhotoAlreadyExistException($object->getName() . " is already uploaded.");
				}

				throw new \Exception($e->getMessage(), (int)$e->getCode());
			}
		}
	}