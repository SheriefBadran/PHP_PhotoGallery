<?php
	
	class PhotoFileModel extends FileModel {

		public $errors = array();
		private $dataResult = array();
		private $uniquePhotoId;
		private static $destinationPath = PhotoUploadDestinationPath;
		private static $thumbnailPath = ThumbnailPath;

		public function getUniquePhotoId () {

			return $this->uniquePhotoId;
		}

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

			if ($dirHandle = opendir(self::$destinationPath)) {
				
				if(file_exists(sprintf(self::$destinationPath . '/%s.%s', sha1_file($_FILES['fileupload']['tmp_name']), $validMimeType))) {

					$this->errors[] = 'The photo is already uploaded!';
					return false;
				}

				closedir($dirHandle);
			}



			// if (move_uploaded_file($this->dataResult[self::$tmpFile], self::$destinationPath . "/" . $this->dataResult[self::$actualFileName])) {

			// 	return true;
			// }
			// else {

			// 	$this->errors[] = $uploadErrors[$_FILES[$filesKeyIndex]['error']];
			// 	return false;
			// }
			// Some operations to retrieve the unique file name.
			$uniqueFilePath = sprintf(self::$destinationPath . '/%s.%s', sha1_file($_FILES['fileupload']['tmp_name']), $validMimeType);
			$splitFilePath = explode(DIRECTORY_SEPARATOR, $uniqueFilePath);
			$this->uniquePhotoId = $splitFilePath[count($splitFilePath) - 1];


			// Create a name from the photo's binary data.
			if ($dirHandle = opendir(self::$destinationPath)) {

				if (move_uploaded_file($_FILES['fileupload']['tmp_name'], 
					sprintf(self::$destinationPath . '/%s.%s', sha1_file($_FILES['fileupload']['tmp_name']), $validMimeType))
				) {

					return true;
				}
				else {

					$this->errors[] = $uploadErrors[$_FILES[$filesKeyIndex]['error']];
					return false;
				}

				closedir($dirHandle);
			}
		}

		public function removePhoto ($uniqueId) {
			
			if ($uniqueId === null) { return false; }

			$photoDeleted = false;
			$thumbnailDeleted = false;

			$dirHandle = opendir(self::$thumbnailPath);

			if ($dirHandle && file_exists(self::$thumbnailPath . DIRECTORY_SEPARATOR . $uniqueId)) {
				
				$thumbnailDeleted = unlink(self::$thumbnailPath . DIRECTORY_SEPARATOR . $uniqueId);
				closedir($dirHandle);
			}

			$dirHandle = opendir(self::$destinationPath);

			if ($thumbnailDeleted && $dirHandle) {
				
				$photoDeleted = unlink(self::$destinationPath . DIRECTORY_SEPARATOR . $uniqueId);
				closedir($dirHandle);
			}

			return $photoDeleted;
		}

		public function unlink ($fileName) {

			return unlink(self::$destinationPath . DIRECTORY_SEPARATOR . $fileName);
		}

		public function getDataResult () {

			return $this->dataResult;
		}
	}