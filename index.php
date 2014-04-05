<!-- 
     Copyright 2014 Manuel Deleo, Lorenzo Livrini 
     
     This file is part of Serie A Database.

     Serie A Database is free software: you can redistribute it and/or modify
     it under the terms of the GNU General Public License as published by
     the Free Software Foundation, either version 3 of the License, or
     (at your option) any later version.

     Serie A Database is distributed in the hope that it will be useful,
     but WITHOUT ANY WARRANTY; without even the implied warranty of
     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     GNU General Public License for more details.

     You should have received a copy of the GNU General Public License
     along with this program.  If not, see <http://www.gnu.org/licenses/>. 
-->

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
			<h1>Classifica Serie A</h1>
			<table id="classtab">
				<tr><th id="tabpresclass">Squadra</th><th id="classth">Punti</th><th id="classth">Vittorie</th><th id="classth">Pareggi</th><th id="classth">Sconfitte</th><th id="classth">Gol Fatti</th><th id="classth">Gol Subiti</th><th id="classth">Diff. Reti</th></tr>
				<?php
				
				include ('php_functions.php');
				$connection = new createConnection();
				$connection->connectToDatabase();
				$connection->selectDatabase();
				
				// Query per ottenere la classifica, ordinata per punti e diff. reti
				$sql=queryClassifica();
				while ($squadra = mysql_fetch_assoc($sql)){ 
					$id_squadra = $squadra['id_squadra'];
					$nome_squadra = $squadra['nome_squadra'];
					$punti = $squadra['punti'];
					$vittorie = $squadra['vittorie'];
					$pareggi = $squadra['pareggi'];
					$sconfitte = $squadra['sconfitte'];
					$gol_fatti = $squadra['gol_fatti'];
					$gol_subiti = $squadra['gol_subiti'];
					$diff_reti = $squadra['diff_reti'];
					
					// Cliccando sul nome di una squadra, si viene indirizzati alla sua pagina con statistiche e rosa
					echo "\t<tr><td id=\"tabpresclass\"><div id=\"txt2\"><a href='squadre.php?id=$id_squadra'><strong>".$nome_squadra."</strong></a></div></td>\n";
					echo "\t<td id=\"classtd\">".$punti. "</td>\n";
					echo "\t<td id=\"classtd\">".$vittorie. "</td>\n";
					echo "\t<td id=\"classtd\">".$pareggi. "</td>\n";
					echo "\t<td id=\"classtd\">".$sconfitte. "</td>\n";
					echo "\t<td id=\"classtd\">".$gol_fatti. "</td>\n";
					echo "\t<td id=\"classtd\">".$gol_subiti. "</td>\n";
					echo "\t<td id=\"classtd\">".$diff_reti. "</td></tr>\n";
				}
				
				?>
			
			</table>
		</div>
	</div>
	
	<div id="footer">
		<p><a href="http://jigsaw.w3.org/css-validator/check/referer"> Valid <strong>CSS</strong> </a> | Designed and Mastered by <a href="mailto:manuel.deleo@studenti.unipr.it">Manuel Deleo</a> & <a href="mailto:lorenzo.livrini@studenti.unipr.it">Lorenzo Livrini</a></p>
	</div>
	
</body>

</html>
