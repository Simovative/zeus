<?php

namespace Simovative\Zeus\Http\Post;

/**
 * Represents a file that was uploaded by an post request.
 *
 * @author Benedikt Schaller
 */
class UploadedFile
{

    /**
     * @var string
     */
    private $inputName;
    /**
     * @var int
     */
    private $inputIndex;
    /**
     * @var string
     */
    private $label;
    /**
     * @var string
     */
    private $type;
    /**
     * @var string
     */
    private $path;
    /**
     * @var int
     */
    private $errorCode;
    /**
     * @var int
     */
    private $size;

    /**
     * @param string $inputName
     * @param int $inputIndex
     * @param string $label
     * @param string $type
     * @param string $path
     * @param int $errorCode
     * @param int $size
     * @author Benedikt Schaller
     */
    public function __construct($inputName, $inputIndex, $label, $type, $path, $errorCode, $size)
    {
        $this->inputName = $inputName;
        $this->inputIndex = $inputIndex;
        $this->label = $label;
        $this->type = $type;
        $this->path = $path;
        $this->errorCode = $errorCode;
        $this->size = $size;
    }

    /**
     * Returns the uploaded files from a global files array.
     *
     * @param array $filesArray
     * @return UploadedFile[]
     * @author Benedikt Schaller
     */
    public static function createFromGlobal($filesArray)
    {
        $uploadedFiles = array();
        $filesArray = self::normalizeFilesArray($filesArray);
        foreach ($filesArray as $key => $files) {
            foreach ($files as $index => $file) {
                $uploadedFiles[] = new UploadedFile(
                    $key,
                    $index,
                    $file['name'],
                    $file['type'],
                    $file['tmp_name'],
                    $file['error'],
                    $file['size']
                );
            }
        }
        return $uploadedFiles;
    }

    /**
     * Method to make handling of the files array easier, copied from
     * http://php.net/manual/en/features.file-upload.post-method.php.
     *
     * @param array $filesArray
     * @return array
     * @author Benedikt Schaller
     */
    private static function normalizeFilesArray($filesArray)
    {
        $normalizedFilesArray = [];
        foreach ($filesArray as $key => $file) {
            if (!is_array($file['name'])) {
                $normalizedFilesArray[$key][] = $file;
                continue;
            }
            foreach ($file['name'] as $fileIndex => $name) {
                $normalizedFilesArray[$key][$fileIndex] = [
                    'name' => $name,
                    'type' => $file['type'][$fileIndex],
                    'tmp_name' => $file['tmp_name'][$fileIndex],
                    'error' => $file['error'][$fileIndex],
                    'size' => $file['size'][$fileIndex]
                ];
            }
        }
        return $normalizedFilesArray;
    }

    /**
     * Returns if we have a valid uploaded file or if someone tried to manipulate the request.
     *
     * @return bool
     * @author Benedikt Schaller
     */
    public function isValidUploadedFile()
    {
        return is_uploaded_file($this->path);
    }

    /**
     * @return string
     * @author Benedikt Schaller
     */
    public function getInputName()
    {
        return $this->inputName;
    }

    /**
     * @return int
     * @author Benedikt Schaller
     */
    public function getInputIndex()
    {
        return $this->inputIndex;
    }

    /**
     * @return string
     * @author Benedikt Schaller
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Returns the transmitted file type, but you should not rely on this,
     * because this can be manipulated.
     *
     * @return string
     * @author Benedikt Schaller
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     * @author Benedikt Schaller
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Returns the Size in bytes.
     *
     * @return int
     * @author Benedikt Schaller
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @return int
     */
    public function getErrorCode(): int
    {
        return $this->errorCode;
    }
}
