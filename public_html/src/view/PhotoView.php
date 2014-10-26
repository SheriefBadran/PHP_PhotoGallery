<?php

	class PhotoView extends Publisher {

		private $mainView;
		private $commentsView;

		private static $nameGetIndex = 'name';
		private static $page = 'page';

		public function __construct (HTMLview $mainView, CommentsView $commentsView) {

			$this->mainView = $mainView;
			$this->commentsView = $commentsView;
		}

		public function renderPhotoHTML (PhotoModel $photo) {

			// Make sure output is clean.
			$name = htmlspecialchars($photo->getName());
			$caption = htmlspecialchars($photo->getCaption());

			$page = isset($_GET[self::$page]) ? $_GET[self::$page] : '1';

			// Render html for photo and caption.
			$html = '<div id="imageWrapper">';
			$html .=	'<a class="back" href="index.php?page=' . $_GET[self::$page] .'"><span>&laquo;</span>Back</a>';
			$html .= 	'<div id="image">';
			$html .=		'<h3 id="photoName">' . $name . '</h3>';
			$html .= 		'<a title="' . $caption . '"><img width=1000 src=' . $photo->getSRC() . '></a>';
			$html .= 	'</div>';
			$html .= '</div>';

			$commentHTML = $this->renderCommentsHTML($photo->getComments()->toArray());
			$commentFormHTML = $this->renderCommentFormHTML();

			$html .= $commentHTML;
			$html .= $commentFormHTML;

			return $html;
		}

		public function renderCommentsHTML (Array $comments) {

			return $this->commentsView->renderCommentsHTML($comments);
		}

		public function renderCommentFormHTML () {

			return $this->commentsView->renderCommentFormHTML();
		}

		public function renderPhoto (PhotoModel $photo) {

			$photoHTML = $this->renderPhotoHTML($photo);
			$this->mainView->echoHTML($photoHTML);
		}

		public function userClickSubmitCommentButton() {

			return $this->commentsView->userClickSubmitCommentButton();
		}

		public function getAuthor() {

			return $this->commentsView->getAuthor();
		}

		public function getComment() {

			return $this->commentsView->getComment();
		}
	}