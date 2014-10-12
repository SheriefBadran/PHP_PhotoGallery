<?php
	
	class PhotoFileModel extends FileModel {

		public $errors = array();
		private $dataResult = array();
		private static $destinationPath = "../data/uploads";

		protected function validatePhotoMimeType ($filePath) {

			if (!is_string($filePath)) {
				
				throw new \Exception('Param must be of type string');
			}

			$finfo = new finfo(FILEINFO_MIME_TYPE);
			$finfo->file($filePath);
			$validMimeTypes = array(
				'jpg' => 'image/jpeg',
				'png' => 'image/png',
				'gif' => 'image/gif'
			);

			return array_search($finfo->file($filePath), $validMimeTypes);
		}


		public function upload ($filesKeyIndex) {

			$this->dataResult = $this->getFileData($filesKeyIndex);

			if (!is_array($this->dataResult)) {
				
				$this->errors[] = $this->dataResult;

				return false;
			}
				
			$validMimeType = $this->validatePhotoMimeType($this->dataResult[self::$tmpFile]);

			if ($validMimeType === false) {

				$this->errors[] = 'The file is not a valid photo.';
			}

			if (move_uploaded_file($this->dataResult[self::$tmpFile], self::$destinationPath . "/" . $this->dataResult[self::$actualFileName])) {

				return true;
			}
			else {

				$this->errors[] = $uploadErrors[$_FILES[$filesKeyIndex]['error']];
				return false;
			}
		}

		public function getDataResult () {

			return $this->dataResult;
		}

		public function printArray (Array $myArray) {

			echo "<pre>";
			print_r($myArray);
			echo "</pre>";
			die();
		}

		public function printVar ($var) {

			var_dump($var);
			die();
		}
	}