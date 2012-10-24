<?

$host = "localhost" ;
$db = "exquis" ;
$user = "exquis" ;
$mdp = "10CadEx10" ;

$type_mots = array();

$conn = new PDO("mysql:host=$host;dbname=$db",$user,$mdp);

$sql = "SELECT * FROM type_mot";
$q	 = $conn->query($sql) or die("failed!");
while($r = $q->fetch(PDO::FETCH_ASSOC)){
    $type_mots[] = utf8_encode($r['type']);
}

$mot = array_rand($type_mots, 1 );
$type_mot_id = $mot + 1 ;


$header = <<<HEAD
<!DOCTYPE html>
<html lang="fr-FR">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="description" content="">
        <link rel="stylesheet" href="/css/style.css" type="text/css" media="all" />
        <link rel="shortcut icon" href="favicon.ico" />
        <title>Exquis</title>
    </head>

    <body>
        <!-- Prompt IE 6 users to install Chrome Frame.-->
        <!--[if lt IE 7]>
        <p class=chromeframe>Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p>
        <![endif]-->
        <div id="main-content">
            <div id="effect">
HEAD;

if (isset($_POST["type_mot_id"])) {

    /*
     * Insertion du nouveau mot
     */

    if($_POST["mot"] != ""){
        $sql = "INSERT INTO mots (type_mot_id,mot) VALUES (:type_mot_id,:mot)";
        $q = $conn->prepare($sql);
        $q->execute(array(':type_mot_id'=>$_POST["type_mot_id"],
                  ':mot'=>$_POST["mot"]));
    }

    /*
     * Sélection des phrases à afficher
     */
    $sujets = array();
    $verbes = array();
    $complements = array();

    $sql = "SELECT type_mot_id, mot FROM mots";
    $q	 = $conn->query($sql);

    while($r = $q->fetch()){
        switch($r["type_mot_id"]) {
        case '1':
            $sujets[] = htmlspecialchars($r["mot"]);
            break;
        case '2':
            $verbes[] =  htmlspecialchars($r["mot"]);
            break;
        case '3':
            $complements[] =  htmlspecialchars($r["mot"]);
            break;
        }
    }

    shuffle($sujets);
    shuffle($verbes);
    shuffle($complements);

    $phrases = "<div id=\"cloud_tags\">" ;

    while($sujet = array_pop($sujets)) {

        $verbe = array_pop($verbes);
        $complement = array_pop($complements);

        if($verbe && $complement){
            $phrases .= "<a href=\"/\" rel=\"".rand(1,10)."\">".$sujet . " " .$verbe . " " . $complement."</a>" ;
        }
    }
    $phrases .= "</div>";

    /*
     * Affichage
     * nuage de tags jquery ?
     */
    $main = <<<MAIN
$phrases
MAIN;

} else {

    $main = <<<MAIN
                <form method="POST">
                    <fieldset>
                        <legend>Apporter votre pierre à notre <strong>Cadavre</strong></legend>
                        <div class="control-group">
                            <label class="control-label" for="inputEmail">Choisissez un ...</label>
                            <div class="controls">
                            <input type="text" placeholder="$type_mots[$mot]" name="mot" required="required">
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


$footer = <<<FOOTER
            </div><!-- #effects -->
        </div> <!-- #main-content -->

        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
        <script type="text/javascript" src="/js/jquery.tagcloud.js"></script>
        <script type="text/javascript" src="/js/bootstrap.min.js"></script>
        <script>
          $.fn.tagcloud.defaults = {
            size: {start: 1, end: 3, unit: 'em'},
            color: {start: '#000', end: '#FFF'}
          };

          $(function () {
            $('#cloud_tags a').tagcloud();
          });

        </script>
    </body>
</html>
FOOTER;

echo $header . $main . $footer;
