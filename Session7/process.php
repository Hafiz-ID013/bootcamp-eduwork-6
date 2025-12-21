<?php
    // process.php - handle form submission from Task7.php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Retrieve form data
        $name = $_POST['name'];
        $price = $_POST['price'];
        $description = $_POST['description'];
        $category = $_POST['category'];

        // Data validation can be added here
        if (empty($name) || empty($price) || empty($description) || empty($category)) {
            die("All fields are required.");
        }
        

        // Handle file upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            // Get file details
            $fileTmpPath = $_FILES['image']['tmp_name'];
            $fileName = $_FILES['image']['name'];
            $fileSize = $_FILES['image']['size'];
            $fileType = $_FILES['image']['type'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            // Sanitize file name
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;

            // Check if file has one of the allowed extensions
            $allowedfileExtensions = array('jpg', 'png', 'webp');
            if (in_array($fileExtension, $allowedfileExtensions) && $fileSize <= 2 * 1024 * 1024) {
                // Directory in which the uploaded file will be moved
                $uploadFileDir = './uploaded_files/';
                $dest_path = $uploadFileDir . $newFileName;

                if(move_uploaded_file($fileTmpPath, $dest_path)) {
                    echo "File is successfully uploaded.<br>";
                } else {
                    echo "There was an error moving the uploaded file.<br>";
                }
            } else {
                echo "Upload failed. Allowed file types: " . implode(',', $allowedfileExtensions) . " and max size 2MB.<br>";
            }
        } else {
            echo "No file uploaded or there was an upload error.<br>";
        }

        // Display submitted data
        echo "<h2>Submitted Product Data:</h2>";
        echo "Name: " . htmlspecialchars($name) . "<br>";
        echo "Price: " . htmlspecialchars($price) . "<br>";
        echo "Description: " . htmlspecialchars($description) . "<br>";
        echo "Category: " . htmlspecialchars($category) . "<br>";
    } else {
        echo "Invalid request method.";
    }
?>