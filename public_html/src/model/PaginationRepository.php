<?php 
	
	class PaginationRepository {

		public function createPaginationModel ($totalItems, $currentPage = 1, $itemsEachPage = 2) {

			if (!is_numeric($totalItems) && !is_numeric($itemsEachPage) && !is_numeric($currentPage)) {
				
				throw new \Exception("All parrams in PaginationModel cunstructor has to be numeric.");
			}

			return new PaginationModel($totalItems, $currentPage, $itemsEachPage);
		}
	}