<?php
session_start();
session_destroy();
header("Location: notice_login.php");
exit;
?>
