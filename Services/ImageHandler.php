<?php
namespace Services;

class ImageHandler {
    protected const MAX_SIZE = 1000; // in kilobytes
    protected const IMAGE_WIDTH = 300;
    protected const IMAGE_HEIGHT = 300;
    protected const RESIZE_TYPE = 'width';

    protected $upMessages = [];

    public function handleImageUpload($file,$path) {
        // Check if a file was uploaded
        if (!isset($file['name']) || empty($file['name'])) {
            $this->upMessages[] = 'No file was uploaded.';
            return;
        }

        // Get the image details
        $imageName = md5(time().rand(1,1000000)).$file['name'];
        $imageSize = $file['size'];
        $imageTmpName = $file['tmp_name'];
        $imageError = $file['error'];
        $imageType = $file['type'];

        // Check for upload errors
        if ($imageError !== UPLOAD_ERR_OK) {
            $this->upMessages[] = 'An error occurred during the file upload.';
            return;
        }

        // Check the file size
        if ($imageSize > self::MAX_SIZE * 1024) {
            $this->upMessages[] = 'The file is too large.';
            return;
        }

        // Check the file type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($imageType, $allowedTypes)) {
            $this->upMessages[] = 'Only JPEG, PNG, and GIF files are allowed.';
            return;
        }

        // Check the image dimensions
        list($width, $height) = getimagesize($imageTmpName);
        if ($width < self::IMAGE_WIDTH || $height < self::IMAGE_HEIGHT) {
            $this->upMessages[] = 'The image dimensions are too small.';
            return;
        }

        // The file is valid, you can proceed with your image handling logic

        $dest=$path.$imageName;

        // Move the uploaded file to the destination
        copy($imageTmpName,$dest);
        return $imageName;       

    }

    public function getMessages() {
        return $this->upMessages;
    }
}