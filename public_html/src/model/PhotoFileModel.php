<?php
	// TODO: FIRST THOUGHT WAS TO HAVE A MORE GENERAL FILEMODEL BUT AS IT IS NOW, IF THERE WAS TIME LEFT, I WOULD HAVE
	// REVERSED THAT DESITION TO PUT ALL LOGIC INTO THE PhotoFileModel, AND ALSO REARRANGE THE ORDER OF OPERATIONS.
	class PhotoFileModel extends FileModel {

		// TODO: Change the errors array to a variable!
		public $errors = array();
		private $dataResult = array();
		private $uniquePhotoId;
		private static $destinationPath = PhotoUploadDestinationPath;
		private static $thumbnailPath = ThumbnailPath;
		private static $argumentExceptionMessage = 'Param must be of type string';
		private static $noPhotoErrorMessage = 'No photo was uploaded.';

		// TODO: Fix internal string dependency, how to use $maximumPhotoNameLength when initializing $longPhotoNameErrorMessage.
		private static $longPhotoNameErrorMessage = 'The photo name can have maximum 55 characters.';
		private static $maximumPhotoNameLength = 55;

		private static $notValidPhotoErrorMessage = 'The file was not a valid photo.';

		public function getUniquePhotoId () {

			return $this->uniquePhotoId;
		}

		protected function validatePhotoMimeType ($filePath) {

			if (!is_string($filePath)) {
				
				throw new ArgumentException(self::$argumentExceptionMessage);
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

			// TODO: Implement this validation rule in the $photoModel as well, throw exception and take care of it.
			if (!empty($_FILES[$filesKeyIndex]['name']) && strlen($_FILES[$filesKeyIndex]['name']) <= self::$maximumPhotoNameLength) {

				$validMimeType = $this->validatePhotoMimeType($_FILES[$filesKeyIndex]['tmp_name']);
			}
			else {

				if (empty($_FILES[$filesKeyIndex]['name'])) {
					
					$this->errors[] = self::$noPhotoErrorMessage;
				}
				else {

					$this->errors[] = self::$longPhotoNameErrorMessage;
				}

				return false;
			}
			

			if ($validMimeType === false) {

				$this->errors[] = self::$notValidPhotoErrorMessage;
				return false;
			}

			$this->dataResult = $this->getFileData($filesKeyIndex);

			if (!is_array($this->dataResult)) {
				
				$this->errors[] = $this->dataResult;

				return false;
			}
				
			$validMimeType = $this->validatePhotoMimeType($this->dataResult[self::$tmpFile]);

			if ($dirHandle = opendir(self::$destinationPath)) {
				
				if(file_exists(sprintf(self::$destinationPath . '/%s.%s', sha1_file($_FILES[$filesKeyIndex]['tmp_name']), $validMimeType))) {

					$this->errors[] = 'The photo is already uploaded!';
					return false;
				}

				closedir($dirHandle);
			}

			$uniqueFilePath = sprintf(self::$destinationPath . '/%s.%s', sha1_file($_FILES[$filesKeyIndex]['tmp_name']), $validMimeType);
			$splitFilePath = explode(DIRECTORY_SEPARATOR, $uniqueFilePath);
			$this->uniquePhotoId = $splitFilePath[count($splitFilePath) - 1];


			// Create a name from the photo's binary data.
			if ($dirHandle = opendir(self::$destinationPath)) {

				if (move_uploaded_file($_FILES['fileupload']['tmp_name'], 
					sprintf(self::$destinationPath . '/%s.%s', sha1_file($_FILES[$filesKeyIndex]['tmp_name']), $validMimeType))
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

			$uniqueId = stripslashes(trim($uniqueId));
			$uniqueId = str_replace(array(";", "&", "#"), "", $uniqueId);

			$uniqueId = filter_var($uniqueId, FILTER_SANITIZE_STRING);

			$photoDeleted = false;
			$thumbnailDeleted = false;

			$dirHandle = opendir(self::$thumbnailPath);

			if ($dirHandle && file_exists(self::$thumbnailPath . DIRECTORY_SEPARATOR . $uniqueId)) {
				
				$thumbnailDeleted = unlink(self::$thumbnailPath . DIRECTORY_SEPARATOR . $uniqueId);
			}
			closedir($dirHandle);

			$dirHandle = opendir(self::$destinationPath);

			if ($thumbnailDeleted && $dirHandle) {
				
				$photoDeleted = unlink(self::$destinationPath . DIRECTORY_SEPARATOR . $uniqueId);
			}
			closedir($dirHandle);

			return $photoDeleted;
		}

		public function unlink ($fileName) {

			//TODO: Let the controller know if the file was not deleted! unlink returns bool on either success or failure.
			$dirHandle = opendir(self::$destinationPath);

			if (file_exists(self::$destinationPath . DIRECTORY_SEPARATOR . $fileName)) {
				
				unlink(self::$destinationPath . DIRECTORY_SEPARATOR . $fileName);
			}

			closedir($dirHandle);
		}

		public function getDataResult () {

			return $this->dataResult;
		}
	}