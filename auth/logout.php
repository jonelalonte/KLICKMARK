<?php
session_start();

// Linisin lahat ng session variables
$_SESSION = array();

// Sirain ang mismong session
session_destroy();

// I-redirect pabalik sa login page pagkatapos mag-logout
header("Location: login.php");
exit();
?>