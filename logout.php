<?php

session_start();
session_unset();
session_destroy();

setcookie("user_email", "", time() - 10000000, "/");
setcookie("user_firstname", "", time() - 10000000, "/");

header("Location: index.php");
exit();
?>
