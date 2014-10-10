<?php

// require_once(HelperPath.DS.'DatabaseAccessModel.php');
require_once(ModelPath.DS.'UserModel.php');

	class UserRepository extends DatabaseAccessModel {

		private static $tblName = 'user';
		private static $childTblName = 'cookie';
		private static $userId = 'userId';
		private static $uniqueId = 'uniqueId';
		private static $uniqueIdLength = 20;
		private static $username = 'username';
		private static $password = 'password';
		private static $expDate = 'rememberme';


		function authenticateUser ($username, $password) {

			try {

				$db = $this->dbFactory->createInstance();

				$sql = "SELECT * FROM " . self::$tblName . " WHERE " . self::$username . " = ? AND " . self::$password . " = ?";
				$params = array($username, $password);
				$query = $db->prepare($sql);
				$query->execute($params);
				$result = $query->fetch();

				return $result;
			}
			catch (PDOException $e) {

				throw ('DB Error!');
			}
		}

		function makeUser (array $userProperties) {

			$userId = $userProperties['userId'];
			$uniqueId = $userProperties['uniqueId'];
			$username = $userProperties['username'];
			$password = $userProperties['password'];

			return new UserModel($userId, $uniqueId, $username, $password);
		}

		function saveCookieExpTime ($uniqueId, $time) {

			try {

				$db = $this->dbFactory->createInstance();

				$sql = "UPDATE " . self::$childTblName . " SET ";
				$sql .= self::$expDate . " = ?";
				$sql .= "WHERE " . self::$uniqueId . "= ?";
				$params = array($time, $uniqueId);
				$query = $db->prepare($sql);
				$query->execute($params);
			}
			catch (PDOException $e) {

				throw ('DB Error!');
			}	
		}
	}