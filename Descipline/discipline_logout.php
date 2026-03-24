<?php
session_start();
session_destroy();
header("Location: discipline_login.php");
