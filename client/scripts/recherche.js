function reponseRobot(phraseRep){
	FinRep = "<span class='ApresRepRobot'></span>";

	// fils de discution robot
	/*$("#parole").append("<p class='phraserobot'>" + phraseRep + " </p>");
	$("#parole").append(FinRep);*/
	 $("#dialogue").typed('reset');
	 $("#dialogue").typed({
        strings: [phraseRep],
         typeSpeed: 0,
      });

		var u = new SpeechSynthesisUtterance();
			u.text = phraseRep;
			u.lang = 'fr-FR';
			u.rate = 1.2;
			speechSynthesis.speak(u);
}

function rechercheSens(phrase){
	 $.getJSON(
		'../json.php',
		{chaine: phrase},
		function(data){
		        $('#retour').hide();
		        $('#retour').html('')
		        .append('<b>Reponses</b> : '+data.chaine+'<br/>')
		        .append('<b>Date</b> : '+data.date+'<br/>')
		        .append('<b>Version PHP</b> : '+data.phpversion+'<br/>')
		        .append('<b>Vitesse de la reponses</b> : '+data.tempsexectution+'<br/>');
		    $('#retour').fadeIn();
		    reponseRobot(data.chaine);
		}
	);
}