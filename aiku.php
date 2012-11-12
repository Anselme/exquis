<?php

include_once('config.inc.php');
include_once('header.php');
include_once('footer.php');

$phrases = array();

$conn = new PDO("mysql:host=$host;dbname=$db",$user,$mdp);

$sql = "SELECT * FROM phrases WHERE active=1";
$q	 = $conn->query($sql) or die("failed!");
while($r = $q->fetch(PDO::FETCH_ASSOC)){
    $cadavres[] = utf8_encode($r['phrase']);
}

shuffle($phrases);

$phrases = "<div id=\"cloud_tags\">" ;
foreach($cadavres as $cadavre){
    $phrases .= "<a href=\"/\" rel=\"".rand(1,10)."\">".$cadavre.".</a> " ;
}
$phrases .= "</div>";

$main = <<<MAIN
$phrases
MAIN;

echo $header . $main . $footer;
