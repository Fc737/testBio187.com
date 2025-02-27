<?php
session_start();
session_unset();
session_destroy();
session_start();
die(header("Location: login.php"));
?>