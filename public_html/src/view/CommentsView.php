<?php

	class CommentsView {

		private $sessionModel;

		private static $submitCommentPostIndex = 'submitcomment';
		private static $authorPostIndex = 'author';
		private static $commentPostIndex = 'comment';

		public function __construct (SessionModel $sessionModel) {

			$this->sessionModel = $sessionModel;
		}

		public function renderCommentsHTML (Array $comments, $forManagement = false) {

			$deleteConfirmMessage = $this->sessionModel->getCommentDeleteSuccessMessage();
			$this->sessionModel->resetCommentDeleteSuccessMessage();
			
			$html = '<p>' . $deleteConfirmMessage . '</p>';
			
			foreach ($comments as $comment) {

				$deleteLink = $forManagement ? '<a href="?action=deletecomment&id='.$comment->getCommentId().'" class="deleteComment">Delete</a>' : '';
				
				$html .= '<div class="comment">';
				$html .= 	'<div class="author">';
				$html .=		'<p>' . $comment->getAuthor() . '</p>';
				$html .=		$deleteLink;
				$html .= 	'</div>';
				$html .= 	'<div class="text">';
				$html .=		'<p>' . $comment->getText() . '</p>';
				$html .= 	'</div>';
				$html .= 	'<div class="crated">';
				$html .=		'<p>' . $comment->getCreated() . '</p>';
				$html .= 	'</div>';
				$html .= '</div>';
			}

			return $html;
		}

		public function renderCommentFormHTML () {

			$html = '<form id="comment" method="POST" action="">';
			$html .=	'<fieldset>';
			$html .=		'<legend>Go ahead and comment the photo!</legend>';
			$html .=		'<label for="name">Name : </label>';
			$html .=		'<input type="text" name="author"/> ';

			$html .=		'<label for="comment">Comment : </label>';
			$html .=		'<textarea maxlength="500" name="comment" class="comment"></textarea> ';
	
			$html .=		'<input type="submit" name="submitcomment" id="submit" value="Send Comment" />';
			$html .=	'</fieldset>';
			$html .='</form>';

			return $html;
		}

		public function userClickSubmitCommentButton () {

			return isset($_POST[self::$submitCommentPostIndex]);
		}

		public function getAuthor () {

			return $_POST[self::$authorPostIndex];
		}

		public function getComment () {

			return $_POST[self::$commentPostIndex];
		}
	}