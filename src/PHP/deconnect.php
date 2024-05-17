<?php
session_start();
$email = $_SESSION['email'];
session_unset();
session_destroy();
header("location:../html/singin.php?email=$email");
