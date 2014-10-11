<?php
	
	class PhotoFileModel extends FileModel {

		public $errors = array();
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

			$dataResult = $this->getFileData($filesKeyIndex);

			if (!is_array($dataResult)) {
				
				$this->errors[] = $dataResult;

				return false;
			}
				
			$validMimeType = $this->validatePhotoMimeType($dataResult[self::$tmpFile]);

			if ($validMimeType === false) {

				$this->errors[] = 'The file is not a valid photo.';
			}

			if (move_uploaded_file($dataResult[self::$tmpFile], self::$destinationPath . "/" . $dataResult[self::$actualFileName])) {

				return true;
			}
			else {

				$this->errors[] = $uploadErrors[$_FILES[$filesKeyIndex]['error']];
				return false;
			}
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