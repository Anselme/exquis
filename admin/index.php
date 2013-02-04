<?php
session_start();

// IMPORTATION DES VARIABLES DE CONNEXION
require_once('config_vars.php');

//// ON INCLUT LE FICHIER QUI SE CONNECTE A L'API TWITTER
require_once('getTwitterInfos.php');

// FONCTION QUI ECHAPPE LES CARACTERES INDESIRABLES
function safe($var)
{
	$var = trim($var);
	$var = htmlspecialchars($var);
	return $var;
}

// VERIFICATION DU MOT DE PASSE POUR L'ACCES
if (isset($_POST["password"]) && !empty($_POST["password"])) {
	$pwd = safe($_POST["password"]);
	if ($pwd === $ConnectMDP){
		$_SESSION["connect"] = $session;	
	}
}
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="fr"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang="fr"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9 lang="fr""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="fr"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Modération des Tweets - Printemps des Poètes</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">
		<meta name="HandheldFriendly" content="true">
        <meta name="robots" content="nofollow">
        <meta name="robots" content="noindex">
        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/main.css">
        <script src="js/vendor/modernizr-2.6.2.min.js"></script>
    </head>
    <body itemscope itemtype="http://schema.org/WebPage">
    <?php 
	$dom = new DomDocument();
	$dom->preserveWhiteSpace = FALSE;
	$dom->formatOutput = TRUE;
	$dom->load('listeTweets.xml');
	$xml = $dom->getElementsByTagName("xml")->item(0);
	
	$nb = 0;
	
	?>
    	<header>
        	<img src="img/logo_MDQ.jpeg" alt="logo_MDQ" width="128" height="90"><h1>Modération des Tweets - Printemps des Poètes 2013</h1>
            <?php 
			// SI LA CONNEXION EST ACCEPTEE, ON AFFICHE LES INFORMATIONS SUR LES TWEETS
			if ($_SESSION["connect"] === $session) {
				// ON RECUPERE LES TWEETS ENREGISTRES DANS LE XML
				$listeTweets = $dom->getElementsByTagName('tweet');
				foreach($listeTweets as $tweet) {
					$XMLlisteTweets_texte[] = $tweet->firstChild->nodeValue;
					$XMLlisteTweets_date[] = $tweet->getAttribute("date");
					$XMLlisteTweets_id[] = $tweet->getAttribute("id");
					$nb++;
				}
				// ON RECUPERE LES TWEETS DE L'UTILISATEUR MDQAngersCentre
				// ID DU DERNIER TWEET ENREGISTRE DANS LE XML
				$dernierTweet = $dom->getElementsByTagName("tweet")->item($nb-1);
				$lastId = $dernierTweet->getAttribute("id");
				
				$data = array('screen_name' => 'MDQAngersCentre','include_entities' => true, 'count' => 200, 'since_id' => $lastId);
				$timeline = $connection->get('statuses/user_timeline', $data);
				
				// SI ON A DES NOUVEAUX TWEETS
				if(sizeof($timeline) > 0) {
					// BOUCLE POUR COMPTER LES TWEETS
					for($i=0;$i<sizeof($timeline);$i++){
						// ON VERIFIE LA SOURCE DU TWEET -> APPLICATION "Exquis"
						$matches = null;
						$returnValue = preg_match('/Exquis/', $timeline[$i]->source, $matches);
						// SI OUI ON STOCKE LES DONNEES DANS DES TABLEAUX
						if ($returnValue) {
							$listeTweets_texte[] = $timeline[$i]->text;
							$listeTweets_date[] = $timeline[$i]->created_at;
							$listeTweets_id[] = $timeline[$i]->id_str;
							$nb++;
							
						}
					}
					// ON REORGANISE LES TABLEAUX POUR L'AFFICHAGE EN ORDRE DECROISSANT
					$listeTweets_texte = array_reverse($listeTweets_texte);
					$listeTweets_date = array_reverse($listeTweets_date);
					$listeTweets_id = array_reverse($listeTweets_id);
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
						$XMLlisteTweets_texte[] = $listeTweets_texte[$i];
						$XMLlisteTweets_date[] = $listeTweets_date[$i];
						$XMLlisteTweets_id[] = $listeTweets_id[$i];
					}
				}
				$XMLlisteTweets_texte = array_reverse($XMLlisteTweets_texte);
				$XMLlisteTweets_date = array_reverse($XMLlisteTweets_date);
				$XMLlisteTweets_id = array_reverse($XMLlisteTweets_id);
				
            echo '<div class="informations">
				<a class="lien_mots" href="mots.php" title="Accéder à la modération des mots..." rel="external">Accès à la modération des mots</a>
            	<div class="nb_tweets">
                Nombre total de tweets : <span>'.$nb.'</span>
                </div>
                <div class="nouveaux_tweets">
                <span class="nb_nvx_tweets"></span><span class="texte_nouveaux_tweets">Pas de nouveaux tweets</span>
                </div>
            </div>';
			}
			?>
        </header>
        <div id="main" role="main">
        <?php
		// SI LA SESSION UTLISATEUR N'EST PAS ACTIVE, AFFICHAGE DU FORMULAIRE DE CONNEXION
			if (!isset($_SESSION["connect"]) || empty($_SESSION["connect"]) || $_SESSION["connect"] !== $session) {
				echo '<form class="form-connect" method="post" action="'.$_SERVER["PHP_SELF"].'">
        			<input type="password" name="password" placeholder="Entrez votre mot de passe..." required>
        			<input type="submit" name="submit" value="Envoyer">
			</form>	';
			}
		
		?>
        
    		<?php
			// SI LA CONNEXION EST ACCEPTEE, ON AFFICHE LES TWEETS AVEC #printempsdespoetes
			if ($_SESSION["connect"] === $session) {
				// BOUCLE POUR ANALYSER LES RESULTATS
				for($i=0;$i<sizeof($XMLlisteTweets_id);$i++){
					// SI LE HASHTAG EST PRESENT, ON AFFICHE LE TWEET
					echo '<article class="tweet tweet-'.$XMLlisteTweets_id[$i].'"><div class="date">'.date("d/m/Y H:i:s", strtotime($XMLlisteTweets_date[$i])).'</div><span>'.$XMLlisteTweets_texte[$i].'</span><button class="button-supprimer button id_'.$XMLlisteTweets_id[$i].'" type="button" name="supprimer">Supprimer ce Tweet</button></article>';
				}
			}
			?>
    	</div>
        <footer>
        
        </footer>
    </body>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.8.2.min.js"><\/script>')</script>
    <script src="js/main.js"></script>
</html>
