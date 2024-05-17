<?php

session_start();
require_once "../../import/BDD.php";

if (isset($_SESSION['email'])){
    $email = $_SESSION['email'];

    $sql = "SELECT idRole FROM Users where email = '$email'";
    $result = mysqli_query($db,$sql);
    $result = mysqli_fetch_array($result,MYSQLI_ASSOC);

    $sql = "DELETE FROM tanchou.Event WHERE nomEvent = ?;";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s",$event);
    $stmt->execute();
    $status = 1;
    $msg = "Le compte ".$email." a bien ete supprimer";
    session_unset();
    session_destroy();
}else{
    $msg = "Erreur lors de la suppression";
    $status = 0;
}

header("location: ../html/singup.php?status=".$status."&msg=$msg");