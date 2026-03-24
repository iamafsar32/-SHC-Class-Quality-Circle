<?php
session_start();

include 'db.php';

if (isset($_POST['username'], $_POST['password'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $q = mysqli_query($conn,
        "SELECT * FROM admin_login 
         WHERE username='$username' AND password='$password'"
    );

    if (mysqli_num_rows($q) == 1) {
        $_SESSION['admin_user'] = $username;

        header("Location: dashboard.php");
        exit;
    } else {
        echo "Invalid Username or Password";
    }

} 
?>
