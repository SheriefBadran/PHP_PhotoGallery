<?php
require_once(HelperPath.DS.'interfaces/iFactory.php');

	abstract class DB_Base_Factory {

		protected $dbUsername;
		protected $dbPassword;
		protected $dbConnectionString;
		protected $dbConnection;
		protected $dbTable;

	    protected function __construct ($dbUsername = '', $dbPassword = '', $dbConnectionString = '') {

	        $this->dbUsername = $dbUsername;
	        $this->dbPassword = $dbPassword;
	        $this->dbConnectionString = $dbConnectionString;
	    }
	}