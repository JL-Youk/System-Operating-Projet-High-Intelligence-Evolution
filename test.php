<?php
	$adresse = "https://www.youtube.com/"; // adresse de la page à exploiter
	$page = @file_get_contents ($adresse); // récupérér le contenu de la page
	echo $page;
?>