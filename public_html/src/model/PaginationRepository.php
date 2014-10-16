<?php 
	
	class PaginationRepository {

		public function createPaginationModel ($totalItems, $itemsEachPage = 2, $currentPage = 1) {

			if (!is_numeric($totalItems) && !is_numeric($itemsEachPage) && !is_numeric($currentPage)) {
				
				throw new \Exception("All parrams in PaginationModel cunstructor has to be numeric.");
			}

			return new PaginationModel($itemsEachPage, $totalItems, $currentPage);
		}
	}