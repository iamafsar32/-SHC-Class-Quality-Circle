<?php
session_start();
include 'db.php';

$user = $_POST['username'];
$pass =($_POST['password']);

$q = mysqli_query($conn,
    "SELECT * FROM course_login 
     WHERE username='$user' AND password='$pass'"
);

if (mysqli_num_rows($q) == 1) {
    $_SESSION['course_user'] = $user;
    header("Location:dashboard.php");
} else {
    echo "Invalid Login";
}
?>
