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
				$connection = new createConnection(); //i created a new object
				$connection->connectToDatabase(); // connected to the database
				$connection->selectDatabase();// closed connection
				
				// Query per le statistiche della squadra
				if (isset($_GET['id'])) {
					$id_squadra = $_GET['id'];
					$sql=queryInfoSquadra($id_squadra);
					$squadra = mysql_fetch_assoc($sql);
					$nome_squadra = $squadra['nome_squadra'];
					$punti = $squadra['punti'];
					$vittorie = $squadra['vittorie'];
					$pareggi = $squadra['pareggi'];
					$sconfitte = $squadra['sconfitte'];
					$gol_fatti = $squadra['gol_fatti'];
					$gol_subiti = $squadra['gol_subiti'];
					$diff_reti = $squadra['diff_reti'];
					
					
					// Riempe una tabella con le statistiche della squadra
					echo "<h1>$nome_squadra</h1>";
					echo "<h2>Statistiche</h2>";
					echo "<table>\n\t<tr><th id=\"squadth\">Punti</th>"
					. "<th id=\"squadth\">Vittorie</th><th id=\"squadth\">Pareggi</th><th id=\"squadth\">Sconfitte</th>"
					. "<th id=\"squadth\">Gol Fatti</th><th id=\"squadth\">Gol Subiti</th><th id=\"squadth\">Diff. Reti</th></tr>";
					echo "\t<tr><td id=\"squadtd\">".$punti. "</td>\n";
					echo "\t<td id=\"squadtd\">".$vittorie. "</td>\n";
					echo "\t<td id=\"squadtd\">".$pareggi. "</td>\n";
					echo "\t<td id=\"squadtd\">".$sconfitte. "</td>\n";
					echo "\t<td id=\"squadtd\">".$gol_fatti. "</td>\n";
					echo "\t<td id=\"squadtd\">".$gol_subiti. "</td>\n";
					echo "\t<td id=\"squadtd\">".$diff_reti. "</td></tr>\n";
					echo "</table>";
					
					echo "<h2>Rosa</h2>";
					
					// Query per la rosa della squadra
					$result_calciatori_query = queryCalciatoriFromSquadra("SELECT calciatore.nome, calciatore.id_calciatore, calciatore.presenze, calciatore.gol_fatti, calciatore.gol_subiti, calciatore.ammonizioni, calciatore.espulsioni " 
						. "FROM calciatore, contratto "
						. "WHERE calciatore.id_calciatore=contratto.id_calciatore "
						. "AND contratto.id_squadra=$id_squadra "
						. "ORDER BY calciatore.nome ASC");
						
					echo "<table id=\"classtab\">\n\t<tr><th id=\"tabpresclass\">Nome</th>"
						. "<th id=\"squadth\">Presenze</th><th id=\"squadth\">Gol Fatti</th><th id=\"squadth\">Gol Subiti</th>"
						. "<th id=\"ammcalc\">Ammonizioni</th><th id=\"espcalc\">Espulsioni</th></tr>";
						
						// Riempe una tabella con la rosa della squadra
					while ($giocatore = mysql_fetch_assoc($result_calciatori_query)){ 
							$nome = $giocatore['nome']; 
							$presenze = $giocatore['presenze'];
							$gol_fatti = $giocatore['gol_fatti'];
							$gol_subiti = $giocatore['gol_subiti'];
							$ammonizioni = $giocatore['ammonizioni'];
							$espulsioni = $giocatore['espulsioni'];

							echo "\t<tr><td id=\"tabpresclass\"><div id=\"txt3\">".$nome. "</div></td>\n";
							echo "\t<td id=\"classtd\">".$presenze. "</td>\n";
							echo "\t<td id=\"classtd\">".$gol_fatti. "</td>\n";
							echo "\t<td id=\"classtd\">".$gol_subiti. "</td>\n";
							echo "\t<td id=\"classtd\">".$ammonizioni. "</td>\n";
							echo "\t<td id=\"classtd\">".$espulsioni. "</td></tr>\n";
							
						}
					echo "</table>";
				}
				
				?>
			
		</div>
	</div>
	
	<div id="footer">
		<p><a href="http://jigsaw.w3.org/css-validator/check/referer"> Valid <strong>CSS</strong> </a> | Designed and Mastered by <a href="mailto:manuel.deleo@studenti.unipr.it">Manuel Deleo</a> & <a href="mailto:lorenzo.livrini@studenti.unipr.it">Lorenzo Livrini</a></p>
	</div>
	
</body>

</html>
