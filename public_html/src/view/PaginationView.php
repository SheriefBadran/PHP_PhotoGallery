<?php

	class PaginationView extends Publisher {


		public static $paginationGetIndex = 'page';

		public function renderPaginationHTML (PaginationModel $paginationModel) {


			if ($paginationModel->getTotalPages() <= 1) {

				return false;
			}


			$html = '<p>';

			// Render previous link.
			if ($paginationModel->previousPageExist()) {

				$html .= '<a href=index.php?page=' . $paginationModel->getPreviousPage() . '><< Previous</a>';
			}

			// Pagination page-numbers
			for ($i=1; $i <= $paginationModel->getTotalPages(); $i++) {

				// Current page is not a link.
				if ($i == $_GET['page']) {

					$html .= "$i";
				}
				else {

					$html .= '<a href=index.php?page=' . $i . '>' . $i . '</a>';
				}
			}

			// Render next link.
			if($paginationModel->nextPageExist()) {

				$html .= '<a href=index.php?page=' . $paginationModel->getNextPage() . '>Next >> </a>';
			}

			$html .= '</p>';

			return $html;
		}

		public function updatePaginationPage () {

			// IMPORTANT: Check if current page nr is numeric! Also checked and handled in PublicGalleryController 
			// and business model.
			if (isset($_GET[self::$paginationGetIndex]) && is_numeric($_GET[self::$paginationGetIndex])) {
			// if (isset($_GET[self::$paginationGetIndex])) {
				
				$this->actions = $_GET[self::$paginationGetIndex];
				$this->notify();
			}
			else {

				$this->redirectToFirstPage();
			}
		}

		public function redirectToFirstPage () {

			$firstPage = 1;
			header('Location: '.$_SERVER['PHP_SELF'] . "?page=$firstPage");
		}

		public function publishPaginationAction () {

			return $this->actions;
		}
	}