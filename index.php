<?php

include_once('config.inc.php');
include_once('header.php');
include_once('footer.php');

$type_mots = array();

$conn = new PDO("mysql:host=$host;dbname=$db",$user,$mdp);

$sql = "SELECT * FROM type_mot";
$q	 = $conn->query($sql) or die("failed!");
while($r = $q->fetch(PDO::FETCH_ASSOC)){
    $type_mots[] = utf8_encode($r['type']);
}

$mot = array_rand($type_mots, 1 );
$type_mot_id = $mot + 1 ;


if (isset($_POST["type_mot_id"])) {

    /***************************
     *
     * Insertion du nouveau mot
     * et
     * Création de la phrase
     *
     **************************/

    $mots_saisis = strip_tags($_POST["mot"]);
    $type_mot_id = (int) $_POST["type_mot_id"];

    if($mots_saisis != ""){
        $sql = "INSERT INTO mots (type_mot_id,mot) VALUES (:type_mot_id,:mot)";
        $q = $conn->prepare($sql);
        $q->execute(array(':type_mot_id'=>$type_mot_id,
            ':mot'=>$mots_saisis));
    }

    /*
     * Sélection de la phrase à afficher
     */
    $sujets_pluriel = array();
    $sujets_singulier = array();
    $verbes_pluriel = array();
    $verbes_singulier = array();
    $complements = array();

    $sql = "SELECT type_mot_id, id, mot FROM mots";
    $q	 = $conn->query($sql);

    while($r = $q->fetch()){
        switch($r["type_mot_id"]) {
        case '1':
            $sujets_singulier[] = htmlspecialchars($r["mot"]);
            break;
        case '2':
            $verbes_singulier[] =  htmlspecialchars($r["mot"]);
            break;
        case '3':
            $complements[] =  htmlspecialchars($r["mot"]);
            break;
        case '4':
            $sujets_pluriel[] =  htmlspecialchars($r["mot"]);
            break;
        case '5':
            $verbes_pluriel[] =  htmlspecialchars($r["mot"]);
            break;
        }
    }

    shuffle($sujets_singulier);
    shuffle($sujets_pluriel);
    shuffle($verbes_singulier);
    shuffle($verbes_pluriel);
    shuffle($complements);

    $phrases = "<div id=\"cloud_tags\">" ;


    $sujet_singulier = array_pop($sujets_singulier);
    $sujet_pluriel = array_pop($sujets_pluriel);
    $verbe_singulier = array_pop($verbes_singulier);
    $verbe_pluriel = array_pop($verbes_pluriel);
    $complement = array_pop($complements);

    $quantite = rand();

    switch($type_mot_id){
    case '1': //sujet singulier
        $sujet = $mots_saisis;
        $verbe = $verbe_singulier;
        break;
    case '2': //verbe singulier
        $sujet = $sujet_singulier;
        $verbe = $mots_saisis;
        break;
    case '3': // complement
        ($quantite%2)?$sujet= $sujet_singulier:$sujet=$sujet_pluriel ;
        ($quantite%2)?$verbe= $verbe_singulier:$verbe=$verbe_pluriel ;
        $complement = $mots_saisis;
        break;
    case '4': //sujet pluriel
        $sujet = $mots_saisis;
        $verbe = $verbe_pluriel;
        break;
    case '5': //verbe pluriel
        $sujet = $sujet_pluriel;
        $verbe = $mots_saisis;
        break;
    }

    $phrases .= "<a href=\"/\" rel=\"".rand(1,10)."\">".ucfirst($sujet)."</a> " ;
    $phrases .= "<a href=\"/\" rel=\"".rand(1,10)."\">".strtolower($verbe)."</a> " ;
    $phrases .= "<a href=\"/\" rel=\"".rand(1,10)."\">".strtolower($complement).".</a> " ;
    $phrases .= "</div>";


    /*
     * Insertion de la phrase non active
     *
     */
    $sql = "INSERT INTO phrases (phrase, active) VALUES (:phrase,:active)";
    $q = $conn->prepare($sql);
    $q->execute(array(
        ':phrase'=> ucfirst($sujet)." ".strtolower($verbe)." ".strtolower($complement),
        ':active' => 0,
    ));

    $phrase_id = $conn->lastInsertId();


    /*
     * Affichage
     * nuage de tags jquery
     */
    $main = <<<MAIN
$phrases
                <form method="POST">
                    <fieldset>
                            <input type="hidden" name="publication" value="$phrase_id">
                        <div class="form-actions">
                            <button id="publier" type="submit" class="btn btn-primary">Publier cet exquis cadavre</button>
                        </div>
                    </fieldset>
                </form>
MAIN;

} else {
    /***************************
     *
     * Formulaire
     * de la
     * Page d'accueil
     *
     * *************************/

    $main = <<<MAIN
                <form method="POST">
                    <fieldset>
                        <legend>Apportez votre pierre à notre Cadavre</legend>
                        <div class="control-group">
                            <label class="control-label" for="inputEmail">Choisissez un ... <strong>$type_mots[$mot]</strong></label>
                            <div class="controls">
                            <input type="text" placeholder="..." name="mot" required="required">
                            <input type="hidden" name="type_mot_id" value="$type_mot_id">
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">J'envoie mon mot exquis</button>
                        </div>
                    </fieldset>
                </form>
MAIN;
}


if(isset($_POST["publication"])) {
    /***************************
     *
     * Activation de la phrase dans la BDD
     * Et
     * Publication twitter
     *
     * *************************/


    $publication = (int) $_POST["publication"] ;
    $sql = "UPDATE phrases SET active = :active WHERE id = :publication";
    $q = $conn->prepare($sql);
    $q->execute(array(
        ':active' => 1,
        ':publication' => $publication,
    ));

    require_once('tmhOAuth.php');
    $sql = "SELECT * FROM phrases WHERE id = :publication";
    $q	 = $conn->prepare($sql);
    $q->execute(array(
        ':publication' => $publication,
    ));

    while($r = $q->fetch(PDO::FETCH_ASSOC)){
        $message = $r['phrase'];
    }

    $tmhOAuth = new tmhOAuth(array(
	'consumer_key' => $consumerKey ,
	'consumer_secret' => $consumerSecret ,
	'user_token' => $accessToken ,
	'user_secret' => $accessTokenSecret ,
    'curl_ssl_verifypeer'   => false
	));

	$tmhOAuth->request('POST', $tmhOAuth->url('1/statuses/update'), array(
	'status' => $message
	));
}


echo $header . $main . $footer;
