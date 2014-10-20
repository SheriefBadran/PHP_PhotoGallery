<?php
	
	class CommentModel {

		private $author;
		private $text;
		private $photoId;
		private $commentId;
		private $created;

		private static $pkArgumentException = 'Param has to be an integer or null.';
		private static $stringArgumentException = 'Param has to be a string.';
		private static $intArgumentException = 'Param has to be numeric.';

		public function __construct ($author, $text, $photoId, $commentId = null, $created = null) {

			if (!is_numeric($commentId) && !is_null($commentId)) {
				
				throw new ArgumentException(self::$pkArgumentException);
			}

			if (!is_string($author) || !is_string($text)) {
				
				throw new ArgumentException(self::$stringArgumentException);
			}

			if (!is_numeric($photoId)) {
				
				throw new ArgumentException(self::$intArgumentException);
			}

			$this->author = $author;
			$this->text = $text;
			$this->photoId = $photoId;
			$this->commentId = $commentId;
			$this->created = $created;
		}

		public function getAuthor () {

			return $this->author;
		}

		public function getText () {

			return $this->text;
		}

		public function getPhotoId () {

			return $this->photoId;
		}

		public function getCommentId () {

			return $this->commentId;
		}

		public function getCreated () {

			return $this->created;
		}
	}