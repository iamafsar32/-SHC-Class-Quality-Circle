<?php
session_start();
session_unset();
session_destroy();
header("Location: attendance_login.php");
exit;
?>
