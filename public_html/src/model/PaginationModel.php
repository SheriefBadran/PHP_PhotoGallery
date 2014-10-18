<?php

	class PaginationModel {

		private $totalItems;
		private $currentPage;
		private $itemsEachPage;

		public static $argumentException = 'All params in PaginationModel ctor has to be numeric.';
		public static $pageOverflowException = 'The chosen page number exceeds the number of available pages!';

		public function __construct ($totalItems, $currentPage, $itemsEachPage) {

			if (!is_numeric($totalItems) && !is_numeric($currentPage) && !is_numeric($itemsEachPage)) {
				
				throw new \Exception(self::$argumentException);
			}

			$this->totalItems = $totalItems;
			$this->currentPage = $currentPage;
			$this->itemsEachPage = $itemsEachPage;

			if ($currentPage > $this->getTotalPages()) {
				
				throw new PageOverflowException(self::$pageOverflowException);
			}
		}

		private function calculateSQLOffset () {

			return ($this->currentPage - 1) * $this->itemsEachPage;
		}

		public function getSQLOffset () {

			return $this->calculateSQLOffset();
		}

		public function getItemsForEachPage () {

			return $this->itemsEachPage;
		}

		public function getTotalPages () {

			return ceil($this->totalItems / $this->itemsEachPage);
		}

		public function getCurrentPage () {

			return $this->currentPage;
		}

		public function getNextPage () {

			return $this->currentPage + 1;
		}

		public function getPreviousPage () {

			return $this->currentPage - 1;
		}


		public function previousPageExist () {

			return ($this->getPreviousPage() >= 1);
		}

		public function nextPageExist () {

			return ($this->getNextPage() <= $this->getTotalPages());
		}

		public function setItemsForEachPage ($itemsEachPage) {

			$this->itemsEachPage = $itemsEachPage;
		}

		public function setTotalItems ($totalItems) {

			$this->totalItems = $totalItems;
		}

		public function setCurrentPage ($currentPage) {

			$this->$currentPage = $currentPage;
		}
	}