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
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="fr"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang="fr"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9 lang="fr""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="fr"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Modération des Mots - Printemps des Poètes</title>
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
    	<header>
        	<img src="img/logo_MDQ.jpeg" alt="logo_MDQ" width="128" height="90"><h1>Modération des Mots - Printemps des Poètes 2013</h1>
        </header>
        <div id="main" role="main" class="mots">
    		<?php
			// SI LA CONNEXION EST ACCEPTEE, ON AFFICHE LES TWEETS AVEC #printempsdespoetes
			if ($_SESSION["connect"] === $session) {
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
				$req_check=$bdd->prepare("SELECT * FROM mots");
				$req_check->execute();
				while($donnees=$req_check->fetch(PDO::FETCH_OBJ))
				{
					if ($donnees->type_mot_id == 1) {
						$sujets_singulier_mots[] = $donnees->mot;
						$sujets_singulier_ids[] = $donnees->id;
					}
					if($donnees->type_mot_id == 4) {
						$sujets_pluriel_mots[] = $donnees->mot;
						$sujets_pluriel_ids[] = $donnees->id;
					}
					if($donnees->type_mot_id == 2) {
						$verbes_singulier_mots[] = $donnees->mot;
						$verbes_singulier_ids[] = $donnees->id;
					}
					if($donnees->type_mot_id == 5) {
						$verbes_pluriel_mots[] = $donnees->mot;
						$verbes_pluriel_ids[] = $donnees->id;
					}
					if ($donnees->type_mot_id == 3) {
						$complements_mots[] = $donnees->mot;
						$complements_ids[] = $donnees->id;
					}
					
					//echo utf8_decode($donnees->mot).'<br />';
				}
				$req_check->closeCursor();
				
				echo 	'<table>
  							<tr>
    							<th>Sujets au singulier</th>
  							</tr>';
							for($i=0;$i<sizeof($sujets_singulier_ids);$i++){
							echo '<tr><td><button class="supprimer_mot id-'.$sujets_singulier_ids[$i].'" type="button" name="supprimer">'.utf8_decode($sujets_singulier_mots[$i]).'</button></td></tr>';
				}
						echo '</table>';
				
				echo 	'<table>
  							<tr>
    							<th>Sujets au pluriel</th>
  							</tr>';
				for($i=0;$i<sizeof($sujets_pluriel_ids);$i++){
					echo '<tr><td><button class="supprimer_mot id-'.$sujets_pluriel_ids[$i].'" type="button" name="supprimer">'.utf8_decode($sujets_pluriel_mots[$i]).'</button></td></tr>';
				}
				echo '</table>';
				
				echo 	'<table>
  							<tr>
    							<th>Verbes au singulier</th>
  							</tr>';
				for($i=0;$i<sizeof($verbes_singulier_ids);$i++){
					echo '<tr><td><button class="supprimer_mot id-'.$verbes_singulier_ids[$i].'" type="button" name="supprimer">'.utf8_decode($verbes_singulier_mots[$i]).'</button></td></tr>';
				}
				echo '</table>';
				
				echo 	'<table>
  							<tr>
    							<th>Verbes au pluriel</th>
  							</tr>';
				for($i=0;$i<sizeof($verbes_pluriel_ids);$i++){
					echo '<tr><td><button class="supprimer_mot id-'.$verbes_pluriel_ids[$i].'" type="button" name="supprimer">'.utf8_decode($verbes_pluriel_mots[$i]).'</button></td></tr>';
				}
				echo '</table>';
				
				echo 	'<table>
  							<tr>
    							<th>Compléments d\'objets</th>
  							</tr>';
				for($i=0;$i<sizeof($complements_ids);$i++){
					echo '<tr><td><button class="supprimer_mot id-'.$complements_ids[$i].'" type="button" name="supprimer">'.utf8_decode($complements_mots[$i]).'</button></td></tr>';
				}
				echo '</table>';
			}
			?>
    	</div>
        <footer>
        
        </footer>
    </body>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.8.2.min.js"><\/script>')</script>
    <script src="js/mots.js"></script>
</html>
