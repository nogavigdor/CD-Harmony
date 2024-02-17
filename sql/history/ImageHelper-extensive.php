<?php
namespace Services;

class ImageHandler {
    protected const MAX_SIZE = 3000; // in kilobytes
    protected  const IMAGE_WIDTH = 300;
    protected  const IMAGE_HEIGHT = 300;
    protected const RESIZE_TYPE = 'width';


    protected $upMessages = [];

    public function handleImageUpload($file) {
        // Perform necessary checks, such as ensuring the file exists and is valid
        if ($file['name']) {
            // Get the image details
            $imageName = $file['name'];
            $tmpFile = $file['tmp_name'];
            $imageType = getimagesize($tmpFile);
    
            if ($this->isValidImageType($imageType[2]) && $this->isSizeValid($tmpFile)) {
                $prefix = uniqid();
                $iName = $prefix . "_" . $imageName;
                $newName = "img/" . $iName;
    
                $resizer = new Resizer();
                $resizer->load($tmpFile);
    
                switch ($resizeType) {
                    case 'width':
                        if ($size > $maxWidth) {
                            $this->upMessages[] = "Error: Image width exceeds the maximum allowed width of $maxWidth";
                            return;
                        }  elseif ($size < 300) {
                            $this->upMessages[] = "Error: Image width must be at least 300 pixels.";
                            return;
                        } else {
                            $resizer->resizeToWidth($size);
                            $this->upMessages[] = "Image resized to new width of $size";
                        }
                        break;
    
                    case 'height':
                        if ($size > $maxHeight) {
                            $this->upMessages[] = "Error: Image height exceeds the maximum allowed height of $maxHeight";
                            return;
                        }   elseif ($size < 300) {
                            $this->upMessages[] = "Error: Image height must be at least 300 pixels.";
                            return;
                        } else {
                            $resizer->resizeToHeight($size);
                            $this->upMessages[] = "Image resized to new height of $size";
                        }
                        break;
    
                    case 'scale':
                        $scale = $size;
                        $resizer->scale($scale);
                        $this->upMessages[] = "Image resized to new scale of $scale%";
                        break;
    
                    default:
                      
                        break;
                }
    
                $resizer->save($newName);
                $mysqli = new mysqli("localhost", "admin2", "123456", "imgy");
                $mysqli->query("INSERT INTO imgs (filename) VALUES ('$iName')");
                $this->upMessages[] = "Upload successful!";
            }
        }
    }

    protected function isFileNameExists($fileName) {
        $filePath = "img/" . $fileName;
        return file_exists($filePath);
    }


    public function deleteImage($fileName) {
        // Perform necessary checks, such as ensuring the file exists and is valid
        if (!$this->isFileNameExists($fileName)) {
            $this->upMessages[] = "Error: The specified image does not exist.";
            return;
        }
    
        // Delete the image from storage
        $filePath = "img/" . $fileName;
        unlink($filePath);
    
        // Delete the image record from the database (adjust based on your storage mechanism)
        $mysqli = new mysqli("localhost", "admin2", "123456", "imgy");
        $mysqli->query("DELETE FROM imgs WHERE filename = '$fileName'");
    
        $this->upMessages[] = "Image '$fileName' has been deleted successfully.";
    }
    
    public function updateImage($oldFileName, $newFile) {
        // Delete the old image
        $this->deleteImage($oldFileName);
    
        // Proceed with uploading the new image
        $this->handleImageUpload($newFile, 'width', 100, 800, 600);
        
        // Get the upload messages from the new image upload
        $newUploadMessages = $this->getUploadMessages();
    
        // Display the messages for the new image upload
        foreach ($newUploadMessages as $msg) {
            $this->upMessages[] = $msg;
        }
    }
    
    

    public function getUploadMessages() {
        return $this->upMessages;
    }

    protected function isValidImageType($imageType) {
        return in_array($imageType, [IMAGETYPE_JPEG, IMAGETYPE_GIF, IMAGETYPE_PNG]);
    }

    protected function isSizeValid($tmpFile) {
        $size = filesize($tmpFile);
        return $size < self::MAX_SIZE * 1024;
    }
}

// Example usage in your controller or wherever you handle form submissions
$imageHandler = new ImageHandler();

if (isset($_POST['submit'])) {
    $file = $_FILES['image'];
    $resizeType = $_POST['Rtype'];
    $size = $_POST['size'];

    $imageHandler->handleImageUpload($file, $resizeType, $size);
    $upMessages = $imageHandler->getUploadMessages();
}

// Display upload messages
foreach ($upMessages as $msg) {
    echo "<h3>$msg</h3>";
}
