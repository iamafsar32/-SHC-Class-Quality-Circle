<?php
session_start();
session_destroy();
header("Location: green_login.php");
exit;
?>
