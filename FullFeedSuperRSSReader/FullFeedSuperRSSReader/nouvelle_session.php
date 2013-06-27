<?php	
$trimestre = $_GET["trimestre"];
$annee = $_GET["annee"];
$xml = '../horaire_session_'.$annee.'_'.$trimestre.'.xml';

if(file_exists($xml))
{
	echo("La session sélectrionnée existe déjà. Pour l'afficher, veuillez utiliser la fonctionnalité \"récupérer\".");
}
else
{
	$newDoc = new DOMDocument();
	$xml_newSession = $newDoc->createElement("horaire_session");
	$newDoc->appendChild( $xml_newSession );
	$newDoc->save($xml);
	chmod($xml, 0777);
	
	$nomTrim = "";
	switch($trimestre){
		case 1:
			$nomTrim = "Hiver";
		break;
		case 2:
			$nomTrim = "Été";
		break;
		case 3:
			$nomTrim = "Automne";
		break;
	}
	echo("La session ".$nomTrim."_".$annee." a été ajoutée.");
	
}
?>