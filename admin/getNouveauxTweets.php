<?php
/*//////////////////////////////////////////*/
/*											*/
/* VERIFICATION PRESENCE DE NOUVEAUX TWEETS */
/*											*/
/*//////////////////////////////////////////*/

session_start();

// FONCTION QUI ECHAPPE LES CARACTERES INDESIRABLES
function safe($var)
{
	$var = trim($var);
	$var = htmlspecialchars($var);
	return $var;
}

//// ON INCLUT LE FICHIER QUI SE CONNECTE A L'API TWITTER
require_once('getTwitterInfos.php');

// SI LA CONNEXION EST AUTORISEE
if ($_SESSION["connect"] === $session && isset($_POST["id"]) && !empty($_POST["id"])) {
	// CHARGEMENT DU XML
	$dom = new DomDocument();
	$dom->preserveWhiteSpace = FALSE;
	$dom->formatOutput = TRUE;
	$dom->load('listeTweets.xml');
	$delete = $dom->getElementsByTagName("xml")->item(0);
	
	$_POST["id"] = safe($_POST["id"]);
	$nb = 0;
	$data = array('screen_name' => 'MDQAngersCentre','include_entities' => true,'since_id' => $_POST["id"]);
	$timeline = $connection->get('statuses/user_timeline', $data);
	// SI ON A DES NOUVEAUX TWEETS
	if(sizeof($timeline) > 0) {
		for($i=0;$i<sizeof($timeline);$i++){
			$matches = null;
			$returnValue = preg_match('/Exquis/', $timeline[$i]->source, $matches);
			if ($returnValue){
				$listeTweets_texte[] = $timeline[$i]->text;
				$listeTweets_date_Affichage[] = date("d/m/Y H:i:s", strtotime($timeline[$i]->created_at));
				$listeTweets_date[] = $timeline[$i]->created_at;
				$listeTweets_id[] = $timeline[$i]->id_str;
				$nb++;
			}
		}
		$listeTweets_texte = array_reverse($listeTweets_texte);
		$listeTweets_date = array_reverse($listeTweets_date);
		$listeTweets_id = array_reverse($listeTweets_id);
		$listeTweets_date_Affichage = array_reverse($listeTweets_date_Affichage);
		for ($i=0;$i<sizeof($listeTweets_id);$i++) {
			// ON ENREGISTRE LES NOUVEAUX TWEETS DANS LE XML
			$newTweet = $dom->createElement("tweet");
			$newTweet->setAttribute("id", $listeTweets_id[$i]);
			$newTweet->setAttribute("date", $listeTweets_date[$i]);
			$newContent = $dom->createCDATASection($listeTweets_texte[$i]);
			$newTweet->appendChild($newContent);
					
			$xml = $dom->getElementsByTagName("xml")->item(0);
			$dernierTweet = $dom->getElementsByTagName("tweet")->item(0);
			$xml->appendChild($newTweet);
			$dom->save('listeTweets.xml');
		}
	}else {
		$listeTweets_texte = '';
		$listeTweets_date = '';
		$listeTweets_id = '';
		$listeTweets_date_Affichage = '';
	}
	
	$reponse['nb'] = $nb;
	$reponse['listeTweets_texte'] = $listeTweets_texte;
	$reponse["listeTweets_date"] = $listeTweets_date_Affichage;
	$reponse["listeTweets_id"] = $listeTweets_id;
	
    header('Content-Type: application/json');
    echo json_encode($reponse);
}

?>