<?php

require_once(HelperPath.DS.'db/DB_Factory.php');

	class DatabaseAccessModel {

		protected $dbFactory;

		public function __construct () {

			$dbAbstactFactory = new DB_Factory();
			$this->dbFactory = $dbAbstactFactory::getFactory('root', 'root', 'mysql:host=localhost;dbname=PhotoGallery');
		}

		public function insert ($object) {

			$getters = array();
			$objectMethods = get_class_methods($object);

			$properties = array_keys(static::$tblFieldGetters);
			$objectGetters = array_values(static::$tblFieldGetters);

			foreach ($objectGetters as $objectGetter) {

				foreach ($objectMethods as $objectMethod) {
					
					if ($objectMethod === $objectGetter) {
						
						$getters[] = $objectMethod;
					}
				}
			}

			$params = array();
			$tblFields = " (";
			$paramString = "(";

			$methods = get_class_methods(static::$repositoryType);

			for ($i = 0; $i < count($properties); $i++) {
				
				$tblFields .= ($i === count($properties) - 1) ? $properties[$i] 
																	 : $properties[$i] . ", ";

				$paramString .= ($i === count($properties) - 1) ? "?" : "?,";
			}

			$tblFields .= ") ";
			$paramString .= ")";

			foreach ($getters as $getter) {
				
				$params[] = call_user_func(array($object, $getter));
			}

			// echo "<pre>";
			// print_r($params);
			// echo "</pre>";
			// die();
			$db = $this->dbFactory->createInstance();
			$sql = "INSERT INTO " . static::$tblName;
			$sql .= $tblFields . "VALUES " . $paramString;
			$query = $db->prepare($sql);
			$query -> execute($params);
		}
	}