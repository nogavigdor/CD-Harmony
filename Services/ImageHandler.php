<?php
namespace Services;

class ImageHandler {
    private const MAX_SIZE = 1000; // in kilobytes
    private const IMAGE_WIDTH = 300;
    private const IMAGE_HEIGHT = 300;
    private const RESIZE_TYPE = 'width';

    private $errorMessages = [];

    public function handleImageUpload($file,$path) {
        // Check if a file was uploaded
        if (!isset($file['name']) || empty($file['name'])) {
            $this->errorMessages[] = 'No file was uploaded.';
        }

        // Get the image details
        $imageName = md5(time().rand(1,1000000)).$file['name'];
        $imageSize = $file['size'];
        $imageTmpName = $file['tmp_name'];
        $imageError = $file['error'];
        $imageType = $file['type'];

        // Check for upload errors
        if ($imageError !== UPLOAD_ERR_OK) {
            $this->errorMessages[] = 'An error occurred during the file upload.';
            return false;
        }

        // Check the file size
        if ($imageSize > self::MAX_SIZE * 1024) {
            $this->errorMessages[] = 'The file is too large. Please upload a file that is less than 1MB.';
            return false;
        }

        // Check the file type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($imageType, $allowedTypes)) {
            $this->errorMessages[] = 'Only JPEG, PNG, and GIF files are allowed.';
            return false;
        }

        // Check the image dimensions
        list($width, $height) = getimagesize($imageTmpName);
        if ($width < self::IMAGE_WIDTH || $height < self::IMAGE_HEIGHT) {
            $this->errorMessages[] = 'The image dimensions are too small. Please upload an image that is at least 300x300 pixels.';
            return false;
        }

        if (count($this->errorMessages) > 0) {
            return false;
        }

        // The file is valid, you can proceed with your image handling logic

        $dest=$path.$imageName;

        // Move the uploaded file to the destination
        copy($imageTmpName,$dest);
        return $imageName;       

    }

    public function getErrorMessages() {
        return $this->errorMessages;
    }
}