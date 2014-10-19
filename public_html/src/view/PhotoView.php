<?php

	class PhotoView {

		private $mainView;
		private $commentsView;

		public function __construct (HTMLview $mainView, CommentsView $commentsView) {

			$this->mainView = $mainView;
			$this->commentsView = $commentsView;
		}

		public function renderPhotoHTML (PhotoModel $photo) {

			// Render html for photo and caption.
			$html = '<div id="image">';
			$html .= '<h3>' . $photo->getName() . '</h3>';
			$html .= '<a title="' . $photo->getCaption() . '"><img width=1000 src=' . $photo->getSRC() . '></a>';
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


	}