<?php
header ('Content-type:text/html; charset=utf-8');
// timestamp en millisecondes du début du script (en PHP 5)
$timestamp_debut = microtime(true);
/*
 *  Script PHP qui traite les requêtes AJAX envoyées par le client 
**/
 
// Récupération des paramètres
$chaine = '';
if( isset($_GET['chaine']) ){
    $chaine = $_GET['chaine'];
    // on met en minuscule
    $chaine = strtolower($chaine);
}

$RechercheQuestion = array("quel", "quelle","quels","quelles","lequel","laquelle","lesquels","lesquelles","duquels","auquel","auxquels","qui","où","que","comment", "dis moi" , "raconte");

$RechercheQuestionWiki = array("c'est quoi", "recherche");

$salutation = array("bonjour","salut","hello","yo");

$pourquoi = "pourquoi";

$ListeBlague = array(
	"Deux enfants sont en conversation dans la chambre. Le petit garçon demande à la petites fille. Que vas-tu demander pour Noël? je vais demander une Barbie, et toi? Moi, je vais demander un Tampax. C'est quoi un Tampax? J'en sais rien... mais à la télé, ils disent qu'avec un Tampax, on peut aller à la plage tous les jours, faire du vélo, faire du cheval, danser, aller en boîte, courir, faire un tas de choses sympas, et le meilleur.. , sans que personne s'en aperçoive!",
	"Deux oeufs discutent : pourquoi t'es tout vert et aussi poilu ? parce que je suis un kiwi connard",
	"Pourquoi les vaches ferment elles les yeux pendant la traite de lait ? Pour faire du lait concentré !",
	);

$ListeReponsesRandom = array("Je n'ai pas compris","Trés intéressant","Tu m'en diras tant","C'est pas faux","blablablabla c'est vraiment très intéressant","tout a fais","bien entendu","ok","Vraiment?");

$ListeReponsesQuestionRandom = array("Une question?","c'est une question?","Que veux tu dire par ' ".$chaine." ' ?", "Pourquoi ne cherche tu pas sur google ?","Je vois bien que tu essaye de communiquer, aller encore un petit effort, tu peut le faire");

$ListeDenominatif = array(" un "," une "," le "," les "," la "," des "," du ", " l'");

$ListeNominatif = array(" tu "," ton "," ta "," toi ");

$ListePronomPersonnelle = array(" je "," moi "," mon "," m'");

$Listepresentation = array("Je suis Sophie, a ton service","Je suis Sophie","Appelle moi Sophie","Pourquoi veux tu savoir mon nom?","Mon nom est System Operating Projet High Intelligence Evolution, mais tu peut m'appeller Sophie" );


$ListeInfoIa = array("nom","prenom","appelle","age","habite");

$identiteIA = array(
      'nom' => 'Sophie', 
      'prenom' => 'Sophie', 
      'appelle' => 'Sophie', 
      'age' => 'quelques milliers de secondes', 
  );

$rep2 = 0;
$rep1 = 0;

//Recherche de salutation
for($NbSalut=0;$NbSalut<sizeof($salutation);$NbSalut++){
	if(preg_match('#'.$salutation[$NbSalut].'#', $chaine)){
  		$rep1 = $salutation[$NbSalut];
	}
}


//recherche sur wikipedia
for($i=0;$i<sizeof($RechercheQuestionWiki);$i++){
	if(preg_match('#'.$RechercheQuestionWiki[$i].'#', $chaine)){
		for($i2=0;$i2<sizeof($ListeDenominatif);$i2++){
			if(preg_match('#'.$ListeDenominatif[$i2].'#', $chaine)){
				//RECHERCHE SUR WIKIPEDIA
				$tab = explode($ListeDenominatif[$i2],$chaine);
				$recherche = $tab[1];
				//$recherche = substr($recherche,1); 
				$xmlUrl = "https://fr.wikipedia.org/w/api.php?action=opensearch&search=".$recherche."&format=xml&limit=1"; // XML feed file/URL
				$xmlStr = file_get_contents($xmlUrl);
				$xmlObj = simplexml_load_string($xmlStr);
			    if($xmlObj->Section->Item->Description) {
			        $rep2 = $xmlObj->Section->Item->Description;
			    } else {
			        $rep2 = "Je ne trouve pas de de bonne definition de ".$recherche." sur wikipedia";
			    }
			}
		}
	}
}
//recherche de question
for($i=0;$i<sizeof($RechercheQuestion);$i++){
	if(preg_match('#'.$RechercheQuestion[$i].'#', $chaine)){
		if(preg_match('#'."heure".'#', $chaine)){
		   $rep2 = "Il est exactement ".date('H')." heure ".date('i');
		}
		elseif(preg_match('#'."blague".'#', $chaine)){
		   $k = array_rand($ListeBlague);
   			$rep2 = $ListeBlague[$k];
		}
		else{
			$k = array_rand($ListeReponsesQuestionRandom);
  			$rep2 = $ListeReponsesQuestionRandom[$k];
		}
		for($i3=0;$i3<sizeof($ListeNominatif);$i3++){
			if(preg_match('#'.$ListeNominatif[$i3].'#', $chaine)){

				for($i4=0;$i4<sizeof($ListeInfoIa);$i4++){
					if(preg_match('#'.$ListeInfoIa[$i4].'#', $chaine)){
						$rep2 = $identiteIA[$ListeInfoIa[$i4]];
					}
				}
			}
		}
	}
}

	// La virgule
	 if($rep1 !== 0 && $rep2 !== 0)
	 {
	 	$virgule = ", ";
	 }
	 else
	 {
	 	$virgule = "";
	 }

	 // construction de la phrase
	 //salutation
	 if($rep1 !== 0)
	 {
	 	$rep1 = html_entity_decode($rep1, ENT_NOQUOTES, "UTF-8");
	 }
	 else
	 {
	 	$rep1 = "";
	 }

	 //Reponses questions
	 if($rep2 !== 0)
	 {
	 	$rep2 = html_entity_decode($rep2, ENT_NOQUOTES, "UTF-8");
	 }
	 else
	 {
	 	$rep2 = "";
	 }
	 $rep = $rep1.$virgule.$rep2;

	// timestamp en millisecondes de la fin du script
	$timestamp_fin = microtime(true);
	// différence en millisecondes entre le début et la fin
	$difference_ms = $timestamp_fin - $timestamp_debut;

	$retour = array(
	    'chaine'    => $rep,
	    'date'      => date('d/m/Y H:i:s'),
	    'phpversion'=> phpversion(),
	    'tempsexectution'=> $difference_ms,
	);
	 
	// Envoi du retour (on renvoi le tableau $retour encodé en JSON)
	header('Content-type: application/json');
	echo json_encode($retour);
?>