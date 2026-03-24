<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $ids = $_POST['id'];
    $statuses = $_POST['status'];
    // Check if files were uploaded, otherwise initialize as empty array
    $files = isset($_FILES['leave_file']) ? $_FILES['leave_file'] : null;

    $targetDir = "uploads/";

    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    foreach ($ids as $index => $id) {
        $id = mysqli_real_escape_string($conn, $id);
        $status = mysqli_real_escape_string($conn, $statuses[$index]);
        $fileUpdateSql = "";

        // Process file only if it exists and there's no error
        if ($files && !empty($files['name'][$index]) && $files['error'][$index] == 0) {
            $fileName = $files['name'][$index];
            $fileTmpName = $files['tmp_name'][$index];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            
            // Secure filename with timestamp
            $newFileName = "leave_" . $id . "_" . time() . "." . $fileExtension;
            $targetFilePath = $targetDir . $newFileName;

            $allowTypes = array('jpg', 'png', 'jpeg', 'pdf');
            if (in_array($fileExtension, $allowTypes)) {
                if (move_uploaded_file($fileTmpName, $targetFilePath)) {
                    $fileUpdateSql = ", file_path = '$newFileName'";
                }
            }
        }

        $sql = "UPDATE leave_letters 
                SET status = '$status' $fileUpdateSql 
                WHERE id = '$id'";
        
        mysqli_query($conn, $sql);
    }

    // Redirect back to the main admin page with the success parameter
    header("Location: leave_dashboard.php?success=1");
    exit();
} else {
    // If someone tries to access this script directly without POST
    header("Location: leave_admin.php");
    exit();
}
?>