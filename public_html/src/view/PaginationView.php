<?php

	class PaginationView extends Publisher {

		public static $paginationGetIndex = 'page';
		private $paginationModel;

		public function renderPaginationHTML (PaginationModel $paginationModel) {

			if ($paginationModel->getTotalPages() <= 1) {

				return false;
			}

			$this->paginationModel = $paginationModel;

			$html = '';

			// render previous link.
			if ($paginationModel->previousPageExist()) {

				$html .= '<a href=index.php?page=' . $paginationModel->getPreviousPage() . '/>&laquo; Previous</a>';
			}

			//this is the pagination page-numbers
			for ($i=1; $i <= $paginationModel->getTotalPages(); $i++) {
				//indicate which page is the active current page. Also the current page is not a link
				if ($i == $_GET['page']) {

					$html .= "<p>$i</p>";
				}
				else {

					$html .= '<a href=index.php?page=' . $i . '>' . $i . '</a>';
				}
			}

			// render next link.
			if($paginationModel->nextPageExist()) {

				$html .= '<a href=index.php?page=' . $paginationModel->getNextPage() . '/>Next &raquo; </a>';
			}

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