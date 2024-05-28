<?php
require_once "../../../import/BDD.php";
session_start();

if (isset($_GET['event']) && isset($_SESSION['email'])) {
    $event = $_GET['event'];
    $email = $_SESSION['email'];

    $sql = "DELETE FROM ParticipationEvent WHERE emailParticipant = ? AND nomEvent = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ss", $email, $event);
    $stmt->execute();

    header("Location: ../../../index.php");
    exit();
}else{
    header('location:../../html/singin.php?status=0');
}?>
