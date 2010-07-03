<?php

namespace Verbier;

class FileUpload {
	
	static $defaultUploadPath = 'uploads/';
	static $validMimeTypes    = array('image/png', 'image/gif', 'image/jpeg');
	
	/**
	 * The file name
	 *
	 * @var string
	 */
	protected $name;
	
	/**
	 * File size (in bytes)
	 *
	 * @var integer
	 */
	protected $size;
	
	/**
	 * The mime type of the file
	 *
	 * @var string
	 */
	protected $contentType;
	
	/**
	 * The path to the file
	 *
	 * @var string
	 */
	protected $path;
	
	/**
	 * Constructor
	 *
	 * @param string $options 
	 * @author Hans-Kristian Koren
	 */
	public function __construct($options) {
		$this->name        = $options['name'];
		$this->size        = $options['size'];
		$this->contentType = $options['type'];
		$this->path        = $options['tmp_name'];
		$this->errorCode   = isset($options['error']) ? $options['error'] : NULL;
	}
	
	/**
	 * Get the name of the uploaded file
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}
	
	/**
	 * Get the size (in bytes) of the uploaded file
	 * @return integer
	 */
	public function getSize() {
		return $this->size;
	}
	
	/**
	 * Get the content type (mime) of this file
	 *
	 * @return string
	 * @author Hans-Kristian Koren
	 */
	public function getContentType() {
		return $this->contentType;
	}
	
	/**
	 * Get the path to this file
	 *
	 * @return string
	 * @author Hans-Kristian Koren
	 */
	public function getPath() {
		return $this->path;
	}
	
	/**
	 * Move the uploaded file to a new location
	 *
	 * @param string $targetLocation 
	 * @return bool
	 * @author Hans-Kristian Koren
	 */
	public function copy($targetLocation) {
		return move_uploaded_file($this->getPath(), $targetLocation);
	}
	
	/**
	 * Is this an uploaded file?
	 *
	 * @return bool
	 * @author Hans-Kristian Koren
	 */
	public function isUploaded() {
		return is_uploaded_file($this->path);
	}
	
	public function isValid() {
		return in_array($this->getContentType(), self::$validMimeTypes);
	}
	
	public function save() {
		if (!$this->isValid()) {
			$this->errorCode = UPLOAD_ERR_EXTENSION;
			return FALSE;
		}
		
		if ($this->isUploaded()) {
			$targetLocation = self::$defaultUploadPath . $this->getName();
			if ($this->copy($targetLocation)) {
				return TRUE;
			}
		}
		return FALSE;
	}
	
	/**
	 * Get the error message given by the upload
	 *
	 * @return string
	 * @author Hans-Kristian Koren
	 */
	public function getErrorMessage() {
		switch ($this->errorCode) {
			case UPLOAD_ERR_INI_SIZE: return 'The uploaded file exceeds the upload_max_filesize directive in php.ini'; break;
			case UPLOAD_ERR_FORM_SIZE: return 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form. '; break;
			case UPLOAD_ERR_PARTIAL: return 'The uploaded file was only partially uploaded. '; break;
			case UPLOAD_ERR_NO_FILE: return 'No file was uploaded.'; break;
			case UPLOAD_ERR_NO_TMP_DIR: return 'Missing a temporary folder.'; break;
			case UPLOAD_ERR_CANT_WRITE: return 'Failed to write file to disk'; break;
			case UPLOAD_ERR_EXTENSION: return 'File upload stopped by extension.'; break;
		}
	}
}