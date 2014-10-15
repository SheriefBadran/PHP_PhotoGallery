<?php

	class ThumbnailList {

		private $thumbNails;

		public function __construct () {

			$this->thumbNails = array();
		}

		public function toArray() {

			return $this->thumbNails;
		}

		/**
		* Add a unique thumbnail to the list.
		*
		* @param ThumbnailModel $photo.
		*
		* @return Boolean
		*/
		public function add (ThumbnailModel $photo) {

			if (!empty($this->thumbNails) && $this->alreadyExist($photo)) {
				
				return false;
			}

			$this->thumbNails[] = $photo;
			return true;
		}

		public function alreadyExist (ThumbnailModel $photoToAdd) {

			foreach ($this->thumbNails as $photo) {
				
				if ($photo->getUniqueId() === $photoToAdd->getUniqueId()) {
					
					return true;
				}

				return false;
			}
		}
	}