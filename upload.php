<?php
include 'func.php';

$file_id = generateRandomString(random_int(5,10));

// Specify the target directory to save the uploaded files
$targetDirectory = "files/$file_id/";

// Create the target directory if it doesn't exist
if (!file_exists($targetDirectory)) {
  mkdir($targetDirectory, 0777, true);
}

if (isset($_FILES['file'])) {
  $uploadedFile = $_FILES['file']['tmp_name'];
  $originalFileName = $_FILES['file']['name'];
  $targetFilePath = $targetDirectory . $originalFileName;

  // Move the uploaded file to the target directory
  if (move_uploaded_file($uploadedFile, $targetFilePath)) {
    echo "File uploaded successfully.";
  } else {
    echo "Error uploading file.";
  }
}
?>
