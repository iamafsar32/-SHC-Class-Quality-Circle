
<?php
session_start();
include 'db.php';

$user = $_POST['username'];
$pass =($_POST['password']);

$q = mysqli_query($conn,
    "SELECT * FROM green_login 
     WHERE username='$user' AND password='$pass'"
);

if (mysqli_num_rows($q) == 1) {
    $_SESSION['green_user'] = $user;
    header("Location: green_dashboard.php");
} else {
    echo "Invalid Login";
}
?>
