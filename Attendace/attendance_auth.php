<?php
session_start();
include 'db.php';

$user = $_POST['username'];
$pass =($_POST['password']);

$q = mysqli_query($conn,
    "SELECT * FROM attendance_login 
     WHERE username='$user' AND password='$pass'"
);

if (mysqli_num_rows($q) == 1) {
    $_SESSION['attendance'] = $user;
    header("Location: attendance_dashboard.php");
} else {
    echo "Invalid Login";
}
?>
