<?php

	class CommentList {

		private $comments;

		public function __construct () {

			$this->comments = array();
		}

		public function toArray () {

			return $this->comments;
		}

		/**
		* Add a unique comment to the list.
		*
		* @param CommentModel $comment.
		*
		* @return Boolean
		*/
		public function add (CommentModel $comment) {

			if (!empty($this->comments) && $this->alreadyExist($comment)) {
				
				return false;
			}

			$this->comments[] = $comment;
			return true;
		}

		public function alreadyExist (CommentModel $commentToAdd) {

			foreach ($this->comments as $comment) {
				
				if ($comment->getCommentId() === $commentToAdd->getCommentId()) {
					
					return true;
				}

				return false;
			}
		}
	}