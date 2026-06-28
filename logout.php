<?php
/*Destroys user session and logs out*/

session_start();
session_destroy();
header("Location: index.php");
exit();
?>