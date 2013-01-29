<?php
/*//////////////////////////////////*/
/*									*/
/*		 SUPRESSION D'UN TWEET      */
/*									*/
/*//////////////////////////////////*/

session_start();

// IMPORTATION DES VARIABLES DE CONNEXION
require_once('config_vars.php');

// ON INCLUT LE FICHIER QUI SE CONNECTE A L'API TWITTER
require_once('getTwitterInfos.php');

// FONCTION QUI ECHAPPE LES CARACTERES INDESIRABLES
function safe($var)
{
	$var = trim($var);
	$var = htmlspecialchars($var);
	return $var;
}

// SI LA CONNEXION EST AUTORISEE
if ($_SESSION["connect"] === $session) {
	// CHARGEMENT DU XML
	$dom = new DomDocument();
	$dom->preserveWhiteSpace = FALSE;
	$dom->formatOutput = TRUE;
	$dom->load('listeTweets.xml');
	$delete = $dom->getElementsByTagName("xml")->item(0);
	
	// SI LES VARIABLES D'ID ET DE CONTENU ONT ETE BIEN RECUES
	if (isset($_POST["id"]) && !empty($_POST["id"]) && isset($_POST["content"]) && !empty($_POST["content"])) {
		$_POST["id"] = safe($_POST["id"]);
		$_POST["content"] = safe($_POST["content"]);
		
		// ON SUPPRIME LE HASHTAG DU TWEET POUR RETROUVER LA PHRASE
		$pattern = '/#printempsdespoètes /';
		$replacement = '';
		$phrase = preg_replace($pattern, $replacement, $_POST["content"]);
		$phrase = str_replace('\\','',$phrase);
		$phrase = utf8_encode($phrase);
		
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
		$req_check=$bdd->prepare("DELETE FROM phrases WHERE phrase = ?");
		$req_check->execute(array( $phrase));
		$req_check->closeCursor();
		
		// SUPPRESSION DU TWEET DU COMPTE TWITTER
		$result = $connection->post('statuses/destroy/'.$_POST["id"]);
		
		// SUPPRESSION DU TWEET DANS LE FICHIER XML
		$del = $delete->getElementsByTagName("tweet");
 
		foreach($del as $suppr){
			if ($suppr->hasAttribute("id") == $_POST["id"]) {
				if ($suppr->getAttribute("id") == $_POST["id"]){
					$delete->removeChild($suppr);
				}
			}
		}
 		$dom->save("listeTweets.xml");
		
		echo 'ok';
	}
}
?>