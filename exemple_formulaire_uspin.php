<?php

include_once './UltimateSpinning.class.php';


if(!empty($_POST['spinnable'])){
	$spinnable = stripslashes($_POST["spinnable"]);
	
	if(!empty($_POST['kws'])) {
	$k = new UltimateSpinning($_POST['kws']);
  $k->spinIt();
  $kws = $k->spinnedText;
	
	$kws=explode("\r\n",$_POST['kws']); } else { $kws=array(); }

	
	$st = new UltimateSpinning($spinnable,$kws);
  $st->spinIt();
  $resultat = $st->spinnedText;
  
$resultat = str_replace('<script>','',$resultat);
$resultat = str_replace('</script>','',$resultat);

$kws = implode("\r\n",$kws);
	}
else {
	$kws = "voitures
chips";
	$spinnable = <<<END
{Hello|Bonjour|Salut} {je suis|moi c'est|mon nom c'est}, #1#Mark#/1##2#Sophie#/2##3#Michel#/3# et je suis très content#2#e#/2# d’être parmi vous ! Ma passion, c'est les [*0*].

[tag={h1|h2|h3|p}]Bienvenue sur mon {essai|texte} de spin{age|ing|}[/tag]


[blocks]
[blocks]{Puisque|Etant donné que} la mode est au [tag={i|em|b|strong|}][url={http://motercalo.pointslash.info|http://motercalo.wordpress.com}]{motercalo|moter calo}[/url][/tag].| J'ai décidé de vous en parler et de faire une petite parenthèse.[/blocks]
|
{Pour moi|De mon point de vue}, le spin{age|ing|} est {très|particulièrement|} {intéressant|avantageux} pour {différentes|{de nombreuses |}plusieurs} raisons :<br />
[list]C'est rapide; C'est pratique; Ca permet de {faire|générer} du contenu {pertinent|unique}; Ca augmente votre {ROI|productivité}[/list]
|
{Nous le savons tous|On le sait tous}, {content is king|le contenu est roi}, {ainsi|donc|} il faut {particulièrement|} le soigner.
[/blocks]
Merci pour m'avoir écouté#2#e#/2#.
C'était #1#Mark#/1##2#Sophie#/2##3#Michel#/3# en direct de CNN avec un paquet de [*1*].
END;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr" dir="ltr">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Uspin</title>
	<style type="text/css">
	body {
	font-family: "Trebuchet MS", Verdana;
	color: grey;
	}
	</style>
  </head>

  <body>
  <h1>Ultimate Spin - démo</h1>
  <h3>Balises possibles :</h3>
  <ul>
  <li>{bonjour|hello|coucou} : prend une possibilité au hasard entre les crochets (spin classique)</li>
  <li>#1#Mark#/1##2#Sophie#/2# : sélectionne soit "Mark" soit "Sophie" et gardera tous les éléments 1 ou 2 dans la suite du texte. Idéal pour varier masculin/féminin par exemple, ou bien garder une certaine séquence de mots clés.</li>
  <li>[tag={h1|h2|h3|p}]Titre[/tag] : sortira le titre en h1, h2, h3 ou en paragraphe. Le titre ou tout texte contenu à l'intérieur des deux balises [tag] est spinnable bien entendu. </li>
  <li>Le "tag" fonctionne également avec n'importe type de balise fonctionnant par "paires" : p, div, span, em, u, b, strong, etc. Si l'argument est vide, il n'y aura pas de tag.</li>
  <li>[blocks]numero 1|numero 2|numero 3[/blocks] : mélange les blocks entre eux. Récursif. <b>Attention, ne doit pas être contenu à l'intérieur d'une balise de spin classique.</b></li>
  <li>[list={li|=>|-}]premier;deuxieme;troisieme[/list] : génère une liste soit sous forme de ul/li, soit avec des retours à la ligne avec le caractère passé en argument en tant que séparateur. Si pas d'argument passé, alors un caractère au hasard est mis en argument. Les éléments de la liste doivent être séparés par des ;</li>
  <li>[size=15]texte[/size] génèrera &lt;span style="font-size:15px">texte&lt;/span></li>
  <li>[img]http://url[/size] génèrera &lt;img src="http://url" /></li>
  <li>[flickr]voiture[/flickr] génèrera une image de voiture au hasard sur flickr avec le titre comme légende.</li>
  <li>[*0*], [*1*] : remplacera par nième mot clé présent dans le premier tableau. Idéal pour doper un texte avec un mot clé et/ou le géolocaliser.</li>
  </ul>
  <h3>Conseils :</h3>
  <ul>
  <li>Variez la taille : faites des grands, des petits paragraphes, n'hésitez pas à "sacrifier" des phrases grace à {phrase|}. C'est dur, mais nécessaire.</li>
  <li>Cassez la structure autant que possible. Cela est rendu facile grâce à la balise [tag]. Disséminez des [tag={em|b|strong|u}] un peu partout.</li>
  <li>Changez les paragraphes, voire les phrases, d'ordre (pour conserver une cohérence au niveau du sens, assurez-vous qu'ils sont interchangeables au niveau de l'emplacement du texte, donc parlez de 2 aspects différents de votre sujet).</li>
  </ul>
  
  
<form  action="" method="post">
	
		Vos mots-clés (facultatif, 1 par ligne) :<br>
		<textarea name="kws" cols="20" rows="5" style="font-family:arial"><?php echo $kws; ?></textarea><br><br>
	
		Votre texte de spin :<br>
		<textarea name="spinnable" cols="110" rows="30" style="font-family:arial"><?php echo $spinnable; ?></textarea>
	
	<br />
	<input type="submit" value="Go" />
</form>
<h2>Résultat :</h2>
<?php echo stripslashes($resultat); ?>

  <body>
  </html>