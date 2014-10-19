<?php

	class CommentsView {

		public function renderCommentsHTML (Array $comments) {

			$html = '';
			foreach ($comments as $comment) {
				
				$html .= '<div class="comment">';
				$html .= 	'<div class="author">';
				$html .=		'<p>' . $comment->getAuthor() . '</p>';
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

			$html = '<form id="comment" method="post" action="">';
			$html .=	'<fieldset>';
			$html .=		'<legend>Go ahead and comment the photo!</legend>';
			$html .=		'<label for="name">Name : </label>';
			$html .=		'<input type="text" name="name"/> ';

			$html .=		'<label for="comment">Comment : </label>';
			$html .=		'<textarea maxlength="500" class="messageView""></textarea> ';
	
			$html .=		'<input type="submit" name="submit" id="submit" value="Send Comment" />';
			$html .=	'</fieldset>';
			$html .='</form>';

			return $html;
		}
	}