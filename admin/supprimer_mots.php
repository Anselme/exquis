<?php
/*//////////////////////////////////*/
/*									*/
/*		 SUPRESSION D'UN MOT      */
/*									*/
/*//////////////////////////////////*/

session_start();

// IMPORTATION DES VARIABLES DE CONNEXION
require_once('config_vars.php');

// FONCTION QUI ECHAPPE LES CARACTERES INDESIRABLES
function safe($var)
{
	$var = trim($var);
	$var = htmlspecialchars($var);
	return $var;
}

// SI LA CONNEXION EST AUTORISEE
if ($_SESSION["connect"] === $session) {
	// SI LES VARIABLES D'ID ET DE CONTENU ONT ETE BIEN RECUES
	if (isset($_POST["id"]) && !empty($_POST["id"])) {
		$_POST["id"] = safe($_POST["id"]);
		
		/* SUPPRESSION DE LA PHRASE DE LA BASE DE DONNEES */
		// CONNEXION A LA BDD
		try
		{
			$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
			$bdd = new PDO('mysql:host='.$mysqlHost.';dbname='.$mysqlDBName.'', $mysqlUser, $mysqlPassword, $pdo_options);
			$bdd->exec("SET CHARACTER SET utf8");
		}
		catch(Exception $e)
		{
        	die('Erreur : '.$e->getMessage());
		}
		
		// SUPPRESION DE L'ENTREE DANS LA BDD
		$req_check=$bdd->prepare("DELETE FROM mots WHERE id = ?");
		$req_check->execute(array( $_POST["id"]));
		$req_check->closeCursor();
		
		echo 'ok';
	}
}
?>