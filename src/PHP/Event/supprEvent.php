<?php
session_start();
require_once "../../../import/BDD.php";

$email = $_SESSION['email'];

if (isset($_GET['event'])){
    $event = $_GET['event'];
}

$sql = "SELECT idRole FROM Users where email = '$email'";
$result = mysqli_query($db,$sql);
$result = mysqli_fetch_array($result,MYSQLI_ASSOC);

if ($result['idRole'] <2){
    header('location: ../../../index.php');
}


$sql = "DELETE FROM tanchou.Event WHERE nomEvent = ?;";
$stmt = $db->prepare($sql);
$stmt->bind_param("s",$event);
$stmt->execute();
$status = 1;
header("location: gestionEvent.php?status=".$status."&msg=L'event ".$event." a bien ete supprimer");