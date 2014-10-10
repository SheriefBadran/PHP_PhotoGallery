<?php

require_once(HelperPath.DS.'db/DB_Factory.php');

	class DatabaseAccessModel {

		protected $dbFactory;

		public function __construct () {

			$dbAbstactFactory = new DB_Factory();
			$this->dbFactory = $dbAbstactFactory::getFactory('root', 'root', 'mysql:host=localhost;dbname=129463-loginmodule');
		}
	}