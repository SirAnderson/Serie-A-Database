<?php
session_start();
?>

<html>

<head>
	<title>Serie A Database</title>	
	<LINK REL="SHORTCUT ICON" HREF="favicon.ico">
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon"/>
	<link rel="stylesheet" href="style.css" type="text/css" media="screen" />
	<meta name="author" content="Manuel Deleo & Lorenzo Livrini" />
	<meta name="description" content="Classifiche, risultati e statistiche Serie A Tim" />
	<meta name="keywords" content="calcio, serie a, tim, campionato" />	
</head>

<body>
	<div id="hmenu">
		<ul>
			<li><a href="index.php">Classifica</a></li>
			<li><a href="calendario.php">Calendario</a></li>
			<li><a href="login.php">Amministrazione</a></li>
		</ul>	
		<?php
		if(!empty($_SESSION['login'])){
			echo "<p><a href='php_logout.php'>Logout</a>";
		}
		?>
	</div>

	<div id="header">	
	</div>

	<div id="content">
		<div id="main">
		
				<?php
				
				include ('php_functions.php');
				$connection = new createConnection();
				$connection->connectToDatabase();
				$connection->selectDatabase();
				
				if ((isset($_GET['id'])) && (isset($_GET['casa'])) && (isset($_GET['trasf']))){
					$id_partita = $_GET['id'];
					$squadra_casa = $_GET['casa'];
					$squadra_trasf = $_GET['trasf'];
					
					echo "<h1>$squadra_casa - $squadra_trasf</h1>";
					echo "<h2>Presenze</h2>";
					
					echo "<h3>$squadra_casa</h3>";
					echo "<ul>\n\t";
					
					// Query per la formazione della squadra di casa
					$sql=queryPartita($id_partita);
					while ($partita = mysql_fetch_assoc($sql)) {
						$nome = $partita['nome'];	
						$casa = $partita['casa'];
						if($casa == 1) {
							echo "<li id=\"\">".$nome. "</li>\n";
						}
					}
					echo "</ul>\n";
					
					// Query per la formazione della squadra in trasferta
					echo "<h3>$squadra_trasf</h3>";
					echo "<ul>\n\t";
					$sql=queryPartita($id_partita);
					while ($partita = mysql_fetch_assoc($sql)) {
						$nome = $partita['nome'];	
						$casa = $partita['casa'];
						if($casa == 0) {
							echo "<li id=\"\">".$nome. "</li>\n";
						}
					}
					
					echo "</ul>\n";
					
					// Query per la i gol, le ammonizioni e le espulsioni della partita
					echo "<h2>Tabellino</h2></br>";
					echo "<h4>Gol</h4></br>";
					$sql=queryPartita($id_partita);
					while ($partita = mysql_fetch_assoc($sql)) {
						$nome = $partita['nome'];
						$gol_fatti = $partita['gol_fatti'];					
						
						if ($gol_fatti > 0) {
							for ($i = 0; $i < $gol_fatti; ++$i) {
								echo $nome."</br>";
							}
						}
						
					}
					
					echo "</br><h4>Ammoniti</h4></br>";
					$sql=queryPartita($id_partita);
					while ($partita = mysql_fetch_assoc($sql)) {
						$nome = $partita['nome'];
						$ammonizioni = $partita['ammonizioni'];

						if ($ammonizioni > 0) {
							echo $nome."</br>";
						}
						
					}
					
					echo "</br><h4>Espulsi</h4></br>";
					while ($partita = mysql_fetch_assoc($sql)) {
						$nome = $partita['nome'];
						$espulsioni = $partita['espulsioni'];

						if ($espulsioni > 0) {
							echo $nome."</br>";
						}
						
					}
					
				}
				
				?>
			
		</div>
	</div>
	
	<div id="footer">
		<p><a href="http://jigsaw.w3.org/css-validator/check/referer"> Valid <strong>CSS</strong> </a> | Designed and Mastered by <a href="mailto:manuel.deleo@studenti.unipr.it">Manuel Deleo</a> & <a href="mailto:lorenzo.livrini@studenti.unipr.it">Lorenzo Livrini</a></p>
	</div>
	
</body>

</html>
