<?php
//force redirect to secure page
if($_SERVER['SERVER_PORT'] != '443') {
	header('Location: https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
	exit();
	}

date_default_timezone_set("Europe/Helsinki");

$sanalistojen_filenamet = array("kaikki" => "sanat.txt",
							    "rajattu" => "sanat_pituus_5-10.txt");

$sanalista = "";

$ERIKOISMERKIT = "!#$%&()*+,-.:;=?@[]^_{|}~";
$satunnainen_erikoismerkki = $ERIKOISMERKIT[rand(0, strlen($ERIKOISMERKIT)-1)];
$satunnainen_numero = random_int(0, 9);

if (isset($_GET['sanalista'])){ 
	if ($_GET['sanalista'] == 'kaikki') { $sanalista = "kaikki"; }
}

if (strlen($sanalista) == 0){ $sanalista = "rajattu"; }

$filename = $sanalistojen_filenamet[$sanalista];

if (isset($_GET['sanoja'])){ $valittavien_sanojen_lukumaara = intval($_GET['sanoja']); }
else { $valittavien_sanojen_lukumaara = 4; }

$valitut_sanat = "";
$sanat_array = file($filename);
$sanoja_yhteensa = count($sanat_array);

for ($i = 0; $i < $valittavien_sanojen_lukumaara; $i++){
	$valitut_sanat .= trim($sanat_array[array_rand($sanat_array)]);	
}

$ss_iso_kirjain_numero_ja_erikoismerkki = ucfirst($valitut_sanat) . $satunnainen_numero . $satunnainen_erikoismerkki;

echo "
<!DOCTYPE html>
<html lang='fi'>
	<head>
		<title>Sanageneraattori</title>
		<script>
			window.onload = function() {
				document.getElementById('sanojen_maara').focus();
			}; 
		</script>
	</head>
	<body>

		<p>
			Käytetään sanalistaa <a href='{$filename}' target='_blank'>{$filename}</a><br>
			Vaihda sanalistaa: <a href='{$_SERVER["SCRIPT_NAME"]}?sanalista=kaikki&sanoja={$valittavien_sanojen_lukumaara}'>kaikki</a> <a href='{$_SERVER["SCRIPT_NAME"]}?sanalista=rajattu&sanoja={$valittavien_sanojen_lukumaara}'>rajattu (pituus 5-10 merkkiä)</a>
		</p>
		<p>Ladattu {$sanoja_yhteensa} sanaa</p>
		<form action='{$_SERVER["SCRIPT_NAME"]}' method='GET'>
			Näin monta sanaa generoidaan:<br>
			<input name='sanoja' id='sanojen_maara' value='{$valittavien_sanojen_lukumaara}' /><br>
			<input type='submit' />
			<input name='sanalista' type='hidden' value='{$sanalista}' />
		</form>
		
		<p>
			<b>{$valitut_sanat}</b> <br />
			<b>{$ss_iso_kirjain_numero_ja_erikoismerkki}</b>
		</p>
	</body>
</html>";
?>