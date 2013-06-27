<?php

$xml = new DomDocument;
$xml->Load('admins.xml');

$admins = $xml->getElementsByTagName('admin');

for ($i=0; $i<$admin->length; $i++) {

	if($admin->item($i)->getElementsByTagName('nom')->item(0)->childNodes->item(0)->nodeValue == $_POST["nom"])
		echo ("success");

}

?>