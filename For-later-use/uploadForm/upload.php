<?php
if (isset($_POST['submit'])) {
    if (
        (
            ($_FILES['picture']['type'] == "image/gif") ||
            ($_FILES['picture']['type'] == "image/jpeg") || // Example for additional image types
            ($_FILES['picture']['type'] == "image/png")   // Example for additional image types
        ) &&
        ($_FILES['picture']['size'] < 1000000)
    ) {
        if ($_FILES['picture']['error'] > 0) {
            echo "Error " . $_FILES['picture']['error'];
        } else {
            echo "Upload: " . $_FILES['picture']['name'] . "</br>";
            echo "Type: " . $_FILES['picture']['type'] . "</br>";
            echo "Size: " . ($_FILES['picture']['size'] / 1024) . " KB</br>";
            echo "Stored: " . $_FILES['picture']['tmp_name'] . "</br>";

            if (file_exists("img/" . $_FILES['picture']['name'])) {
                echo "Can't upload. File already exists there!!";
            } else {
                move_uploaded_file($_FILES['picture']['tmp_name'], "img/" . $_FILES['picture']['name']);
                echo "Stored in: " . "img/" . $_FILES['picture']['name'];
                $conn=mysqli_connect("localhost", "root", "","img");
                $pic=$_FILES['picture']['name'];
               
                $sql="INSERT INTO `images` (`filename`) VALUES ('$pic')";
                echo $sql;
                mysqli_query($conn, $sql);
              
            }
        }
    }
}
