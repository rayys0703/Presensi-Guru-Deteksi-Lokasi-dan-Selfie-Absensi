<?php 
// Aini

session_start();
unset($_SESSION['nip']);
//session_destroy();
 
header("Location: login");
 
?>