<?php
session_start();
require_once "../../../import/BDD.php";

if (!isset($_SESSION['email'])||$_SESSION['email']==""){
    header('location:../../html/singin.php?status=0');
}else{
    $email = $_SESSION['email'];
}

$sql = "SELECT idRole FROM Users where email = '$email'";
$result = mysqli_query($db,$sql);
$result = mysqli_fetch_array($result,MYSQLI_ASSOC);

$sql2 = "INSERT INTO ParticipationEvent (emailParticipant, nomEvent, idRole) VALUES (?, ?, ?)";
$stmt = $db->prepare($sql2);
$stmt->bind_param("ssi", $email, $_GET['event'], $result['idRole']);
$stmt->execute();

header('location:../../../index.php?status=reussi');