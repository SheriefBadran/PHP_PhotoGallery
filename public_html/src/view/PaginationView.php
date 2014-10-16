<?php

	class PaginationView extends Publisher {

		public static $paginationGetIndex = 'page';
		private $paginationModel;

		public function renderPaginationHTML (PaginationModel $paginationModel) {

			if ($paginationModel->getTotalPages() <= 1) {

				return false;
			}

			$this->paginationModel = $paginationModel;


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

			if (isset($_GET[self::$paginationGetIndex])) {
				
				$this->actions = $_GET[self::$paginationGetIndex];
				$this->notify();
			}
		}

		public function publishPaginationAction () {

			return $this->actions;
		}
	}