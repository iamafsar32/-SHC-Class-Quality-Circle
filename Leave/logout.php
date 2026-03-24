<?php
session_start();
session_destroy();
header("Location: leave_login.php");
exit;
?>
