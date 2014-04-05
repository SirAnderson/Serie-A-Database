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

// inizializzazione della sessione
session_start();
// controllo sul valore di sessione
if (!isset($_SESSION['login']))
{
 // reindirizzamento alla home page in caso di login mancato
 header("Location: index.html");
}

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

			echo "<br />"; // putting a html break

			$connection->selectDatabase();// closed connection
			
			//Inserisce partita e impieghi nel database
			if ((isset($_POST['check_casa'])) && (isset($_POST['check_trasf']))){
				//Recupero i giocatori che hanno giocato (array contentente id dei giocatori)
				$array_check_casa = $_POST['check_casa'];
				$array_check_trasf = $_POST['check_trasf'];
				
				//Se il numero dei giocatori di ogni squadra è > 11 possiamo procedere
				if((count($array_check_casa)>=11) && ((count($array_check_trasf))>=11)) {
				
					//Query per la creazione della partita, con campi gol_fatti e gol_subiti vuoti
					$query_crea_partita="INSERT INTO `partita` (`id_partita`, `giornata`, `id_squadra_casa`, `id_squadra_trasferta`, `gol_casa`, `gol_trasferta`)"
															." VALUES (NULL, '".$_POST['giorn']."', '".$_POST['casa']."', '".$_POST['trasf']."', '0', '0');";
					$ret=mysql_query($query_crea_partita);
					if(! $ret ) {
						die('Errore: ' . mysql_error());
					}
															
					$last_id = mysql_insert_id();
					
					$totale_gol_casa=0;
					$totale_gol_trasferta=0;
					
					//Inizializzo la query per l'inserimento degli impieghi
					$query_inserisci_impiego="INSERT INTO `impiego` "
									."(`id_formazione`, `id_calciatore`, `id_partita`, `gol_fatti`, `gol_subiti`, `ammonizioni`, `espulsioni`, `casa`) VALUES ";
									
					//Per ogni giocatore della squadra di casa selezionato nel form
					foreach($array_check_casa as $id) {
						// Lo inserisco nella tabella
						$query_inserisci_impiego.="(NULL, $id, $last_id, ";
					
						if (isset($_POST['gol_fatti_casa'])) {
							$array_gol_fatti_casa=$_POST['gol_fatti_casa'];
							foreach($array_gol_fatti_casa as $num) {
								//Aggiorno i suoi gol fatti
								$numarray=explode("-", $num);
								if($numarray[0] == $id) {
									$query_inserisci_impiego.=$numarray[1].", ";
								}
							}
							
							$array_gol_subiti_casa=$_POST['gol_subiti_casa'];
							foreach($array_gol_subiti_casa as $num) {
								//Aggiorno i suoi gol subiti
								$numarray=explode("-", $num);
								if($numarray[0] == $id) {
									$query_inserisci_impiego.=$numarray[1].", ";
									$totale_gol_trasferta+=$numarray[1];
								}
							}
							
							$array_ammonizioni_casa=$_POST['ammonizioni_casa'];
							foreach($array_ammonizioni_casa as $num) {
								//Aggiorno la sua ammonizione, se presente
								$numarray=explode("-", $num);
								if($numarray[0] == $id) {
									$query_inserisci_impiego.=$numarray[1].", ";
								}
							}
							
							$array_espulsioni_casa=$_POST['esplusioni_casa'];
							foreach($array_espulsioni_casa as $num) {
								//Aggiorno la sua espulsione, se presente
								$numarray=explode("-", $num);
								if($numarray[0] == $id) {
									$query_inserisci_impiego.=$numarray[1].", ";
								}
							}
						}
						
						$query_inserisci_impiego.="1), ";
						
					}
					
					$cont=0;
					//Per ogni giocatore della squadra di casa selezionato nel form
					foreach($array_check_trasf as $id) {
						// Lo inserisco nella tabella
						$query_inserisci_impiego.="(NULL, $id, $last_id, ";
					
						if (isset($_POST['gol_fatti_trasf'])) {
							//Aggiorno i suoi gol fatti
							$array_gol_fatti_trasf=$_POST['gol_fatti_trasf'];
							foreach($array_gol_fatti_trasf as $num) {
								$numarray=explode("-", $num);
								if($numarray[0] == $id) {
									$query_inserisci_impiego.=$numarray[1].", ";
								}
							}
							
							$array_gol_subiti_trasf=$_POST['gol_subiti_trasf'];
							foreach($array_gol_subiti_trasf as $num) {
								//Aggiorno i suoi gol subiti
								$numarray=explode("-", $num);
								if($numarray[0] == $id) {
									$query_inserisci_impiego.=$numarray[1].", ";
									$totale_gol_casa+=$numarray[1];
								}
							}
							
							$array_ammonizioni_trasf=$_POST['ammonizioni_trasf'];
							foreach($array_ammonizioni_trasf as $num) {
								//Aggiorno la sua ammonizione, se presente
								$numarray=explode("-", $num);
								if($numarray[0] == $id) {
									$query_inserisci_impiego.=$numarray[1].", ";
								}
							}
							
							$array_esplusioni_trasf=$_POST['esplusioni_trasf'];
							foreach($array_esplusioni_trasf as $num) {
								//Aggiorno la sua espulsione, se presente
								$numarray=explode("-", $num);
								if($numarray[0] == $id) {
									$query_inserisci_impiego.=$numarray[1].", ";
								}
							}
						}
						
						$query_inserisci_impiego.="0)";
						//Se ci sono altri giocatori continua con un altro inserimento, altrimenti chiudo la query
						if(++$cont!=count($array_check_trasf)) {
							$query_inserisci_impiego.=",";
						}
						else
							$query_inserisci_impiego.=";";
					}
					
					//Infine eseguo la query
					$ret=mysql_query($query_inserisci_impiego);
					if(! $ret ) {
						die('Errore: ' . mysql_error());
					}
					
					//Inizializzo ed eseguo la query per aggiornare i gol della partita precedentemente inserita
					$query_aggiorna_partita=
					"UPDATE squadra \n"
					."SET gol_fatti = CASE id_squadra \n"
					."WHEN ".$_POST['casa']." THEN $totale_gol_casa \n"
					."WHEN ".$_POST['trasf']." THEN $totale_gol_trasferta \n"
					."END, \n"
					."gol_subiti = CASE id_squadra \n"
					."WHEN ".$_POST['casa']." THEN $totale_gol_trasferta \n"
					."WHEN ".$_POST['trasf']." THEN $totale_gol_casa \n"
					."END "
					."WHERE id_squadra IN (".$_POST['casa'].", ".$_POST['trasf'].") ";
					
					//Inizializzo ed eseguo la query per aggiornare i dati relativi alle squadre coinvolte (vittoria, pareggio, sconfitta)
					$query_aggiorna_classifica=
					"UPDATE squadra \n"
						."SET vittorie = CASE \n"

						."WHEN $totale_gol_casa < $totale_gol_trasferta AND id_squadra=".$_POST['trasf']." \n"
						."THEN vittorie + 1 \n"
						
						."WHEN $totale_gol_casa > $totale_gol_trasferta AND id_squadra=".$_POST['casa']." \n"
						."THEN vittorie + 1 \n"
						."END, \n"
						
						."sconfitte = CASE \n"
						
						."WHEN $totale_gol_casa < $totale_gol_trasferta AND id_squadra=".$_POST['casa']." \n"
						."THEN sconfitte + 1 \n"
						
						."WHEN $totale_gol_casa > $totale_gol_trasferta AND id_squadra=".$_POST['trasf']." \n"
						."THEN sconfitte + 1 \n"
						."END, \n"
						
						."pareggi = CASE \n"
						
						."WHEN $totale_gol_casa = $totale_gol_trasferta AND id_squadra=".$_POST['casa']." \n"
						."THEN pareggi + 1 \n"
						
						."WHEN $totale_gol_casa = $totale_gol_trasferta AND id_squadra=".$_POST['trasf']." \n"
						."THEN pareggi + 1 \n"

						."END \n"
						."WHERE id_squadra IN (".$_POST['casa'].", ".$_POST['trasf'].") ";
					$ret=mysql_query($query_aggiorna_partita);
					if(! $ret ) {
						die('Errore: ' . mysql_error());
					}
					
					$ret=mysql_query($query_aggiorna_classifica);
					if(! $ret ) {
						die('Errore: ' . mysql_error());
					}
				}
			}
			
			
			
			$id_post_casa = 0;
			$id_post_trasf = 0;
			$giorn = 0;
			
			// Tiene salvata la giornata dopo averla selezionata
			if (isset($_POST['giorn'])) {
				$giorn = $_POST['giorn'];
		
			}
			// Tiene salvata la squadra di trasferta dopo averla selezionata
			if (isset($_POST['trasf'])) {
				$id_post_trasf=$_POST['trasf'];
			}
			// Tiene salvata la squadra di casa dopo averla selezionata
			if (isset($_POST['casa'])){
				$id_post_casa=$_POST['casa'];
			}
			?>

			<form action="" method="post"> 
			<fieldset id="partfieldset">
				<legend>Inserisci formazione</legend>
				
				<!-- Scelgo la giornata della partita -->
				<select id="select1" name="giorn" onchange='this.form.submit()'>
					<?php
					echo "\t<option value=\"\">--GIORNATA--</option>\n";
					for ($i = 1; $i <= 38; $i++) {
						echo "\t<option value=\"$i\"";
						if ($giorn==$i) {
							echo " selected";
						}
						echo ">$i</option>\n";
					}
					?>
				</select>
				
				<!-- Scelgo la squadra in casa della partita -->
				<select id="select1" name="casa" onchange='this.form.submit()'>

					<?php

					//Recupero i dati dal DB 
					$strSQL = "SELECT nome_squadra, id_squadra  " 
					. "FROM squadra "
					. "ORDER BY nome_squadra"; 
					$result = mysql_query($strSQL); 

					//Visualizzo le 20 squadre tra cui scegliere
					echo "\t<option value=\"\">--CASA--</option>\n";
					while ($row = mysql_fetch_assoc($result)){
						$squadra = $row['nome_squadra'];  
						$id = $row['id_squadra'];
		 
						echo "\t<option value=\"$id\"";
						if($id_post_casa==$id) {
							echo " selected";
						}
					echo ">$squadra</option>\n"; 
					} 
					?>

				</select>
				
				<!-- Scelgo la squadra in trasferta della partita -->
				<select id="select1" name="trasf" onchange='this.form.submit()'>

					<?php

					//Recupero i dati dal DB 
					$strSQL = "SELECT nome_squadra, id_squadra " 
					. "FROM squadra "
					. "ORDER BY nome_squadra"; 

					$result = mysql_query($strSQL); 

					//Visualizzo le 20 squadre tra cui scegliere
					echo "\t<option value=\"\">--TRASFERTA--</option>\n";
					while ($row = mysql_fetch_assoc($result)){ 
						$squadra = $row['nome_squadra'];  
						$id = $row['id_squadra'];  
						
						echo "\t<option value=\"$id\"";
						if($id_post_trasf==$id) {
							echo " selected";
						}
						echo ">$squadra</option>\n";
					} 
					?>

				</select>
				
			</fieldset>
			<?php


			
			//Se giornata, squadra di casa e di traferta sono stati scelti, 
			// costruisco il form per inserire i dati dei giocatori.
			if ((isset($_POST['casa'])) && (isset($_POST['trasf'])) && (isset($_POST['giorn']))) {
				if ((($_POST['casa'])!='') && (($_POST['giorn'])!='') && (($_POST['trasf'])!='')) {
					if(($_POST['casa'])!=($_POST['trasf'])) {
						
						//Query per ottenere i giocatori della squadra di casa
						$result_calciatori_query = queryCalciatoriFromSquadra("SELECT calciatore.nome, calciatore.id_calciatore " 
						. "FROM calciatore, contratto "
						. "WHERE calciatore.id_calciatore=contratto.id_calciatore "
						. "AND contratto.id_squadra='$id_post_casa'"
						. "AND contratto.giornata_inizio <= '$giorn'"
						. "AND contratto.giornata_fine >= '$giorn'");
						
						// TABELLA SQUADRA CASA
						echo "<h1>".queryNomeSquadraFromId($id_post_casa)."</h1>";
						
						echo "<div id=\"txtbox\"><table>\n"
						. "<tr><th id=\"tabpres\">Presenza</th><th>Gol Fatti</th><th>Gol Subiti</th><th>Ammon.</th><th>Espuls.</th></tr>";
						//Recupero i giocatori della squadra di casa dal DB
						while ($giocatore = mysql_fetch_assoc($result_calciatori_query)){ 
							$calciatore = $giocatore['nome'];  
							$k1 = $giocatore['id_calciatore'];
							echo "\t<tr><td id=\"tabpres\"><div id=\"txt\"><input class =\"check\""
							."type=\"checkbox\" name=\"check_casa[]\" value=\"$k1\">"
							.$calciatore. "</div></td>";
							
							//Aggiungo menu per i gol fatti
							echo "<td><select id=\"goldropdown\" name=\"gol_fatti_casa[]\">\n";
							echo "\t<option value=\"$k1-0\" selected=\"selected\">--GF--</option>\n";
							for ($i = 1; $i <= 8; $i++) {
								echo "\t<option value=\"$k1-$i\""
								.">$i</option>\n";
							}
							echo "</select></td>\n";
							
							//Aggiungo menu per i gol subiti
							echo "<td><select id=\"goldropdown\" name=\"gol_subiti_casa[]\">\n";
							echo "\t<option value=\"$k1-0\" selected=\"selected\">--GS--</option>\n";
							for ($i = 1; $i <= 8; $i++) {
								echo "\t<option value=\"$k1-$i\""
								.">$i</option>\n";
							}
							echo "</select></td>";
							
							//Aggiungo menu per le ammonizioni
							echo "<td><select id=\"goldropdown\" name=\"ammonizioni_casa[]\">\n";
							echo "\t<option value=\"$k1-0\" selected=\"selected\">--AMM--</option>\n";
							
							echo "\t<option value=\"$k1-1\""
							.">Si</option>\n"
							."<option value=\"$k1-0\""
							.">No</option>\n";
							
							echo "</select></td>";
							
							//Aggiungo menu per le espulsioni
							echo "<td><select id=\"goldropdown\" name=\"esplusioni_casa[]\">\n"
							."\t<option value=\"$k1-0\" selected=\"selected\">--ESP--</option>\n"
							."\t<option value=\"$k1-1\""
							.">Si</option>\n"
							."<option value=\"$k1-0\""
							.">No</option>\n"
							."</select></td></tr>\n";
						}
						echo "</table></div>\n";
				
						//Query per ottenere i giocatori della squadra in trasferta
						$result_calciatori_query = queryCalciatoriFromSquadra("SELECT calciatore.nome, calciatore.id_calciatore " 
						. "FROM calciatore, contratto "
						. "WHERE calciatore.id_calciatore=contratto.id_calciatore "
						. "AND contratto.id_squadra='$id_post_trasf'"
						. "AND contratto.giornata_inizio <= '$giorn'"
						. "AND contratto.giornata_fine >= '$giorn'");
						
						// TABELLA SQUADRA TRASFERTA
						echo "<h1>".queryNomeSquadraFromId($id_post_trasf)."</h1>";
						
						echo "<div id=\"txtbox\"><table>\n";
						
						//Recupero i giocatori della squadra in trasferta dal DB
						while ($giocatore = mysql_fetch_assoc($result_calciatori_query)){ 
						
							$calciatore = $giocatore['nome'];  
							$k2 = $giocatore['id_calciatore'];
							echo "\t<tr><td id=\"tabpres\"><div id=\"txt\"><input class =\"check\""
							."type=\"checkbox\" name=\"check_trasf[]\" value=\"$k2\">"
							.$calciatore. "</div></td>";
							
							//Aggiungo menu per i gol fatti
							echo "<td><select id=\"goldropdown\" name=\"gol_fatti_trasf[]\">\n"
							."\t<option value=\"$k2-0\" selected=\"selected\">--GF--</option>\n";
							
							for ($i = 1; $i <= 8; $i++) {
								echo "\t<option value=\"$k2-$i\""
								.">$i</option>\n";
							}
							echo "</select></td>";

							//Aggiungo menu per i gol subiti
							echo "<td><select id=\"goldropdown\" name=\"gol_subiti_trasf[]\">\n"
							."\t<option value=\"$k2-0\" selected=\"selected\">--GS--</option>\n";
							
							for ($i = 1; $i <= 8; $i++) {
								echo "\t<option value=\"$k2-$i\""
								.">$i</option>\n";
							}
							echo "</select></td>";
							
							//Aggiungo menu per le ammonizioni
							echo "<td><select id=\"goldropdown\" name=\"ammonizioni_trasf[]\">\n"
							."\t<option value=\"$k2-0\" selected=\"selected\">--AMM--</option>\n"
							."\t<option value=\"$k2-1\""
							.">Si</option>\n"
							."<option value=\"$k2-0\""
							.">No</option>\n"
							."</select></td>";
							
							//Aggiungo menu per le espulsioni
							echo "<td><select id=\"goldropdown\" name=\"esplusioni_trasf[]\">\n"
							."\t<option value=\"$k2-0\" selected=\"selected\">--ESP--</option>\n"
							."\t<option value=\"$k2-1\""
							.">Si</option>\n"
							."<option value=\"$k2-0\""
							.">No</option>\n"
							."</select></td></tr>\n";
						}
						echo "</table></div>"
						."<input id =\"submitbutton\" type=\"submit\" name=\"submit\">"
						."</form>";
					}
					else 
						echo "</form>";
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
