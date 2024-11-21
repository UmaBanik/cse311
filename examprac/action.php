<?php
session_start();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include('connect.php');
    if (isset($_POST['save-admin'])) {
        // Save a new admin or update an existing one
        $AdminID = $_POST['AdminID'];
        $name = $_POST['Name'];
        $email = $_POST['Email'];
        $password = $_POST['Password'];
        $role = $_POST['Role'];

        if (!empty($AdminID)) {
            // Update admin
            if (!empty($password)) {
                // Update password if provided
                $update_query = "UPDATE admins 
                                 SET Name = '$name', Email = '$email', Password = '$password', Role = '$role' 
                                 WHERE AdminID = $AdminID";
            } else {
                // Keep the existing password
                $update_query = "UPDATE admins 
                                 SET Name = '$name', Email = '$email', Role = '$role' 
                                 WHERE AdminID = $AdminID";
            }
            mysqli_query($conn, $update_query);
        } else {
            // Insert new admin
            $insert_query = "INSERT INTO admins (Name, Email, Password, Role) 
                             VALUES ('$name', '$email', '$password', '$role')";
            mysqli_query($conn, $insert_query);
        }

        // After the form is submitted, reload the page to avoid resubmission and refresh the data
        header("Location: form.php");
        exit;
    } 
}

// Handle delete admin
if (isset($_GET['delete'])) {
    $AdminID = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM admins WHERE AdminID = $AdminID");
    header("Location: form.php");
    exit;
}
?>