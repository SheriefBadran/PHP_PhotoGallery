<?php
	
	class FileModel {

		protected $error;
		protected $uploadErrors = array(
			UPLOAD_ERR_OK		   =>	"File successfully uploaded.",
			UPLOAD_ERR_INI_SIZE	   =>	"File exceeds upload_max_filesize.",
			UPLOAD_ERR_FORM_SIZE   =>	"File exceeds MAX_FILE_SIZE.",
			UPLOAD_ERR_PARTIAL	   =>	"The uploaded file was only partially uploaded.",
			UPLOAD_ERR_NO_FILE	   =>	"No file was uploaded.",
			UPLOAD_ERR_NO_TMP_DIR  =>	"Missing a temporary folder.",
			UPLOAD_ERR_CANT_WRITE  =>	"Failed writing file to disk.",
			UPLOAD_ERR_EXTENSION   =>	"Extension stopped the file upload."
		);

		protected static $destinationFolder = '../data/uploads';
		protected static $tmpFile = 'tmpFilePath';
		protected static $actualFileName = 'uploadedFileName';
		protected static $fileType = 'type';
		protected static $fileSize = 'size';

		public function getUploadErrors () {

			return $this->uploadErrors;
		}

		public function getFileData ($filesKeyIndex) {

			if (!is_string($filesKeyIndex)) {
				
				throw new \Exception('Param must be of type string.');
			}

			if (!isset($_FILES[$filesKeyIndex]) && !empty($_FILES[$filesKeyIndex])) {
				
				$this->error = 'No file was uploaded.';
			}
			else if (!empty($_FILES[$filesKeyIndex]['error'])) {
				
				$this->error = $this->uploadErrors[$_FILES[$filesKeyIndex]['error']];
			}
			else {

				$fileData = array(
					self::$tmpFile        => $_FILES[$filesKeyIndex]['tmp_name'],
					self::$actualFileName => basename($_FILES[$filesKeyIndex]['name']),
					self::$fileType 	  => $_FILES[$filesKeyIndex]['type'],
					self::$fileSize 	  => $_FILES[$filesKeyIndex]['size']
				);
			}

			return $this->error === null ? $fileData : $this->error;
		}
	}