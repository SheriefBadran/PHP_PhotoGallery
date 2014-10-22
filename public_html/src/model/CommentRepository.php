<?php

	class CommentRepository extends DatabaseAccessModel {

		private $comments;

		public function __construct(CommentList $comments) {

			parent::__construct();
			$this->comments = $comments;
		}

		// Setup for parant class insert method.
		protected static $tblName = 'comment';
		protected static $tblFieldGetters = array(
			"author"	=> "getAuthor",
			"text"	    => "getText",
			"photoId"   => "getPhotoId"
		);

		protected static $repositoryType = 'CommentModel';
		protected static $commentId = 'commentId';
		protected static $photoId = 'photoId';

		public function deleteComment ($commentId) {

			// Make sure query is done with an integer.
			$commentId = (int)filter_var($commentId, FILTER_SANITIZE_NUMBER_INT);

			try {

				$db = $this->dbFactory->createInstance();

				$sql = "SELECT " . self::$commentId . " FROM " . self::$tblName;
				$sql .= " WHERE " . self::$commentId . " = (?)";
				$query = $db->prepare($sql);
				$query->execute(array($commentId));
				$trueId = $query->fetch();

				if ($trueId) {
					
					$sql = "DELETE FROM " . self::$tblName;
					$sql .= " WHERE " . self::$commentId . " = (?)";
					$query = $db->prepare($sql);
					$result = $query->execute(array($trueId["commentId"]));
				}
				else {

					$result = false;
				}

				return $result;
			} 
			catch (PDOException $e) {
				
				throw new \Exception($e->getMessage(), (int)$e->getCode());
			}
		}

		public function toList ($photoId) {

			$photoId = (int)filter_var($photoId, FILTER_SANITIZE_NUMBER_INT);

			$db = $this->dbFactory->createInstance();
			$sql = "SELECT * FROM " . self::$tblName . " WHERE " . self::$photoId . " = ?";
			$params = array($photoId);
			$query = $db->prepare($sql);
			$query->execute($params);
			$commentsRecord = $query->fetchAll();

			if ($commentsRecord) {
				
				foreach ($commentsRecord as $comment) {
					
					$this->comments->add(new CommentModel(

						$comment["author"],
						$comment["text"],
						$comment["photoId"],
						$comment["commentId"],
						$comment["created"]
					));
				}

				return $this->comments;
			}

			return $this->comments;
		}
	}