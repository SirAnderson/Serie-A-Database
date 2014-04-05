<?php
// inizializzazione della sessione
session_start();
// se la sessione di autenticazione 
// è già impostata non sarà necessario effettuare il login
// e il browser verrà reindirizzato alla pagina inserimento formazioni
if (isset($_SESSION['login']))
{
	header("Location: partita_squadre.php");
} 
// controllo sul parametro d'invio
if(isset($_POST['submit']) && (trim($_POST['submit']) == "Login"))
{ 
  // controllo sui parametri di autenticazione inviati
  if( !isset($_POST['username']) || $_POST['username']=="" )
  {
    echo "Attenzione, inserire la username.";
  }
  
  elseif( !isset($_POST['password']) || $_POST['password'] =="")
  {
    echo "Attenzione, inserire la password.";
  }
  else{
    // validazione dei parametri tramite filtro per le stringhe
    $username = trim(filter_var($_POST['username'], FILTER_SANITIZE_STRING));
    $password = trim(filter_var($_POST['password'], FILTER_SANITIZE_STRING));
	
    include ('php_functions.php');

    $connection = new createConnection(); //i created a new object

    $connection->connectToDatabase(); // connected to the database

    $connection->selectDatabase();// closed connection
    // interrogazione della tabella
    $auth = mysql_query("SELECT id_login FROM usr WHERE user_name = '$username' AND user_password = '$password'");
    // controllo sul risultato dell'interrogazione
        if(mysql_num_rows($auth)==0)
	{
        // reindirizzamento alla homepage in caso di insuccesso
        header("Location: index.php");
	}
	else{
        // chiamata alla funzione per l'estrazione dei dati
		$res =  mysql_fetch_row($auth);
        // creazione del valore di sessione
		$_SESSION['login'] = $res;
        // reindirizzamento alla pagina di amministrazione in caso di successo
          header("Location: partita_squadre.php");
    }
  } 
}
else{
  // form per l'autenticazione
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
		</div>

		<div id="header">	
		</div>

		<div id="content">
			<div id="main">

				<h1>Accesso</h1></br> <h1>all'amministrazione:</h1>
				<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
					<p>Username:<br /></p>
					<input name="username" type="text"><br />
					<p>Password:<br /></p>
					<input name="password" type="password" size="20"><br />
					<input name="submit" class ="button" type="submit" value="Login">
				</form>


			</div>
		</div>
		
	
	<div id="footer">
		<p><a href="http://jigsaw.w3.org/css-validator/check/referer"> Valid <strong>CSS</strong> </a> | Designed and Mastered by <a href="mailto:manuel.deleo@studenti.unipr.it">Manuel Deleo</a> & <a href="mailto:lorenzo.livrini@studenti.unipr.it">Lorenzo Livrini</a></p>
	</div>
		
	</body>

	</html>
	
	<?php
	}
	?>
