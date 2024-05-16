<?php

// Connexion à la base de données
$servername = "dwarves.iut-fbleau.fr";
$username = "tanchou";
$password = "MotdepasseUpec77**";
$dbname = "tanchou";

// Crée une connexion
$db = new mysqli($servername, $username, $password, $dbname);

// Vérifie la connexion
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}