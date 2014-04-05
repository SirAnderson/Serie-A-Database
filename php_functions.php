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

class createConnection //create a class for make connection
{
    var $host="localhost";
    var $username=" ";    // specify the sever details for mysql
    var $password=" ";
    var $database="serie a database";
    var $myconn;

    function connectToDatabase() // create a function for connect database
    {

        $conn= mysql_connect($this->host,$this->username,$this->password);

        if(!$conn)
        {
            die ("Impossibile connettersi");
        }

        else
        {
            $this->myconn = $conn;
        }
        return $this->myconn;

    }

    function selectDatabase()
    {
        mysql_select_db($this->database);

        if(mysql_error())
        {
            echo "Impossibile trovare il database ".$this->database;
        };       
    }

    function closeConnection()
    {
        mysql_close($this->myconn);
    }

}

function queryCalciatoriFromSquadra($query) { //Query per selezionare nome e id dei calciatori di una squadra

	$result = mysql_query($query);
	return $result;
}

function queryNomeSquadraFromId($id) { //Query per selezionare nome di una squadra dato l'id

	$result = mysql_query("SELECT nome_squadra "
						."FROM squadra "
						."WHERE id_squadra=$id");
	$id_squadra = mysql_fetch_row($result);
	return $id_squadra[0];
}

function queryClassifica() { //Query per ottenere la classifica
    $sql = "SELECT `id_squadra`, `nome_squadra`, sum(`vittorie`*3+`pareggi`) as punti, `vittorie`, `pareggi`, `sconfitte`, `gol_fatti`, `gol_subiti`, \n"
	. "SUM(`gol_fatti`-`gol_subiti`) as diff_reti\n"
    . "FROM `squadra`\n"
    . "GROUP BY `nome_squadra`, `vittorie`, `pareggi`, `sconfitte`, `gol_fatti`, `gol_subiti`\n"
    . "ORDER BY punti desc, diff_reti desc ";
	$result = mysql_query($sql);
	return $result;
}

function queryInfoSquadra($id) { //Query per ottenere statistiche di una squadra, dato l'id
    $sql = "SELECT `nome_squadra`, sum(`vittorie`*3+`pareggi`) as punti, `vittorie`, `pareggi`, `sconfitte`, `gol_fatti`, `gol_subiti`, \n"
	. "SUM(`gol_fatti`-`gol_subiti`) as diff_reti\n"
    . "FROM `squadra`\n"
	. "WHERE `id_squadra` = $id";
	if($result = mysql_query($sql)) {
		return $result;
	}
	else die(mysql_error());
}

function queryCalendario($giorn) { //Query per ottenere risultati di una data giornata
	$sql = "SELECT s1.nome_squadra as squadcasa, s2.nome_squadra as squadtrasf, partita.gol_casa, partita.gol_trasferta, partita.id_partita \n"
		. "FROM partita, squadra as s1, squadra as s2 \n"
		. "WHERE partita.giornata = $giorn \n"
		. "AND partita.id_squadra_casa=s1.id_squadra \n"
		. "AND partita.id_squadra_trasferta=s2.id_squadra";
		
		if($result = mysql_query($sql)) {
			return $result;
		}
		else die(mysql_error());
}

function queryPartita($id_partita) { // Query per ottenere formazioni, gol, ammonizioni ed espulsioni di una partita, dato l'id della partita
		$sql = "SELECT calciatore.nome, impiego.gol_fatti, impiego.ammonizioni, impiego.espulsioni, impiego.casa \n"
		. "FROM impiego, calciatore \n"
		. "WHERE impiego.id_partita = $id_partita \n"
		. "AND impiego.id_calciatore = calciatore.id_calciatore";
		
		if($result = mysql_query($sql)) {
			return $result;
		}
		else die(mysql_error());
}
?>
