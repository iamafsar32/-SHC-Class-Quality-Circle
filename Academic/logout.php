<?php
session_start();
session_unset();
session_destroy();
header("Location: academic_login.php");
exit;
?>
