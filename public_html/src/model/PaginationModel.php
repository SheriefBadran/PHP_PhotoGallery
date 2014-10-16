<?php

	class PaginationModel {

		private $itemsEachPage;
		private $totalItems;
		private $currentPage;

		public function __construct ($itemsEachPage, $totalItems, $currentPage) {

			if (!is_numeric($itemsEachPage) && !is_numeric($totalItems) && !is_numeric($currentPage)) {
				
				throw new \Exception("All parrams in PaginationModel cunstructor has to be numeric.");
			}

			$this->itemsEachPage = $itemsEachPage;
			$this->totalItems = $totalItems;
			$this->currentItems = $currentItems;
		}

		private function calculateSQLOffset () {

			return ($this->currentPage - 1) * $this->itemsEachPage;
		}

		public function getSQLOffset () {

			return $this->calculateSQLOffset();
		}




	}