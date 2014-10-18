<?php 
	
	class PaginationRepository {

		public static $argumentException = 'All params in PaginationRepository ctor has to be numeric.';
		public static $pageOverflowException = 'The chosen page number exceeds the number of available pages!';

		public function createPaginationModel ($totalItems, $currentPage = 1, $itemsEachPage = 2) {

			if (!is_numeric($totalItems) || !is_numeric($itemsEachPage) || !is_numeric($currentPage)) {
				
				throw new ArgumentException(self::$argumentException);
			}

			try {

				return new PaginationModel($totalItems, $currentPage, $itemsEachPage);
			}
			catch (PageOverflowException $e) {

				throw new PageOverflowException(self::$pageOverflowException);
			}
		}
	}