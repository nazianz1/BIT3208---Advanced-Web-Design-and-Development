<?php
session_start();
session_destroy();
header("Location: ../Week3/login.php");
exit();
?>