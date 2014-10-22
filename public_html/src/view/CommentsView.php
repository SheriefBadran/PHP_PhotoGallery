<?php

	class CommentsView {

		private $sessionModel;

		private static $submitCommentPostIndex = 'submitcomment';
		private static $authorPostIndex = 'author';
		private static $commentPostIndex = 'comment';

		private static $emptyAuthorErrorMessage = "The name cannot be empty.";
		private static $shortAuthorErrorMessage = "The name must be minimum 3 characters.";
		private static $longAuthorErrorMessage = "The name can have maximum 45 characters.";

		private static $emptyCommentErrorMessage = "The comment cannot be empty.";
		private static $longCommentErrorMessage = "The comment can have maximum 1000 characters.";

		public function __construct (SessionModel $sessionModel) {

			$this->sessionModel = $sessionModel;
		}

		public function renderCommentsHTML (Array $comments, $forManagement = false) {

			$deleteConfirmMessage = $this->sessionModel->getCommentDeleteSuccessMessage();
			$this->sessionModel->resetCommentDeleteSuccessMessage();
			
			$html = '';
			if ($forManagement) {
				
				$html = '<p>' . $deleteConfirmMessage . '</p>';
			}
			
			
			foreach ($comments as $comment) {

				$author = htmlspecialchars($comment->getAuthor());
				$text = htmlspecialchars($comment->getText());

				$deleteLink = $forManagement ? '<a href="?action=deletecomment&id='.$comment->getCommentId().'" class="fa fa-times">Delete</a>' : '';
				
				$html .= '<section class="message">';
				$html .= 	'<div class="topbar">';
				$html .=		'<p class="sender">' . $comment->getAuthor() . ' says:</p>';
				$html .=			$deleteLink;
				$html .=		'<p class="topbarContainer">';
				$html .=			'<span class="deleteIcon">';
				$html .=				$deleteLink;
				$html .=			'</span>';
				$html .=		'</p>';
				$html .= 	'</div>';
				$html .= 	'<div class="text">';
				$html .=		'<p>' . $comment->getText() . '</p>';
				$html .= 	'</div>';
				$html .= 	'<div class="bottomBar">';
				$html .=		'<p><span class="bottomBar">' . $comment->getCreated() . '</span></p>';
				$html .= 	'</div>';
				$html .= '</section>';
			}

			return $html;
		}

		public function renderCommentFormHTML () {

			$authorErrorMessage = $this->sessionModel->getAuthorErrorMessage();
			$commentErrorMessage = $this->sessionModel->getCommentErrorMessage();

			$responseHTML = '';

			if ($authorErrorMessage !== '' || $commentErrorMessage !== '') {
				
				$responseHTML .=		'<div class="isa_error">';
				$responseHTML .=			'<i class="fa fa-warning"></i>';
				$responseHTML .=			'<p class="resp">'.$authorErrorMessage.'</p>';
				$responseHTML .=			'<p class="resp">'.$commentErrorMessage.'</p>';
				$responseHTML .=		'</div>';
			}

			$html = '<form id="commentForm" method="POST" action="">';
			$html .=	'<fieldset>';
			$html .=		$responseHTML;
			$html .=		'<legend>Go ahead and comment the photo!</legend>';
			$html .=		'<div class="nameWrapper">';
			$html .=			'<input class="nameInput" maxlength="45" type="text" placeholder="Name" name="author"/> ';
			$html .=		'</div>';
			$html .=		'<div class="commentWrapper">';
			$html .=			'<textarea maxlength="1000" name="comment" class="commentInput" placeholder="Comment the photo..."></textarea> ';
			$html .=		'</div>';
			$html .=		'<div id="subminCommentButton">';
			$html .=			'<input type="submit" name="submitcomment" id="submitComment" value="Send Comment" />';
			$html .=		'</div>';
			$html .=	'</fieldset>';
			$html .='</form>';

			return $html;
		}

		public function userClickSubmitCommentButton () {

			return isset($_POST[self::$submitCommentPostIndex]);
		}

		public function getAuthor () {

			$author = $this->cleanString($_POST[self::$authorPostIndex]);

			if (empty($author) || $author == '') {
				
				$this->sessionModel->setAuthorErrorMessage(self::$emptyAuthorErrorMessage);
				return false;
			}

			if (strlen($author) < 3) {
				
				$this->sessionModel->setAuthorErrorMessage(self::$shortAuthorErrorMessage);
				return false;
			}

			if (strlen($author) > 45) {
				
				$this->sessionModel->setAuthorErrorMessage(self::$longAuthorErrorMessage);
				return false;
			}

			return $author;
		}

		public function getComment () {

			$comment = $this->cleanString($_POST[self::$commentPostIndex]);

			if (empty($comment) || $comment == '') {
				
				$this->sessionModel->setCommentErrorMessage(self::$emptyCommentErrorMessage);
				return false;
			}

			if (strlen($comment) > 1000) {
				
				$this->sessionModel->setCommentErrorMessage(self::$longCommentErrorMessage);
				return false;
			}

			return $comment;
		}

		public function cleanString ($string) {

			$string = trim($string);
			$string = stripslashes($string);

			return (filter_var($string, FILTER_SANITIZE_STRING));
		}
	}