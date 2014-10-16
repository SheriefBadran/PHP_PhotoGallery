<?php

	class PaginationModel {

		private $totalItems;
		private $currentPage;
		private $itemsEachPage;

		public function __construct ($totalItems, $currentPage, $itemsEachPage) {

			if (!is_numeric($totalItems) && !is_numeric($currentPage) && !is_numeric($itemsEachPage)) {
				
				throw new \Exception("All parrams in PaginationModel cunstructor has to be numeric.");
			}

			$this->totalItems = $totalItems;
			$this->currentPage = $currentPage;
			$this->itemsEachPage = $itemsEachPage;
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