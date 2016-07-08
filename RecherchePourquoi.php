<?php
	$recherche = $chaine;
	$rechercheAvecMaj = ucfirst($recherche); 
	$adresse = "http://www.pourquois.com/liste.html"; // adresse de la page à exploiter
	$page = @file_get_contents ($adresse); // récupérér le contenu de la page
	if(preg_match('#'.$rechercheAvecMaj.'#', $page)){
	  		echo "trouvé!";
	  		preg_match( "/title=(.*)".$rechercheAvecMaj."/U", $page, $match );
	  		$k = array_rand($match);
   			$ReponseComplete = $match[$k];
		}
		else {
		}
?>