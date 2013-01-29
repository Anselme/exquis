<?php
header('Content-Type: text/html; charset=utf-8');

// Inclusion du fichier API OAuth Twitter
require_once('twitteroauth.php');

// IMPORTATION DES VARIABLES DE CONNEXION
require_once('config_vars.php');
 
// Définition des codes API Twitter
define('CONSUMER_KEY', $CONSUMER_KEY);
define('CONSUMER_SECRET', $CONSUMER_SECRET);
define('ACCESS_TOKEN', $ACCESS_TOKEN);
define('ACCESS_TOKEN_SECRET', $ACCESS_TOKEN_SECRET);
 
// Connexion OAuth Twitter
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, ACCESS_TOKEN, ACCESS_TOKEN_SECRET);
 
// Définition du message à envoyer, et envoie du message sur Twitter
date_default_timezone_set('GMT');
?>