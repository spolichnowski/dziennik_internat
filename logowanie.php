<?php

session_start();
if(@$_GET['error']=="logout")
{
	session_regenerate_id();
	session_destroy();
}
else if(!empty($_SESSION))
{
	Header("Location:userpanel.php");
}
else
{
	@$base = new mysqli("localhost", "internat", "internat", "internat");
	if(@$base->connect_errno)
	{
		die("Błąd połąćzenia z bazą danych ".$base->connect_errno);
	}
	if(!empty($_POST))
	{
		if(sql_injection_protected_password($_POST['password']) && sql_injection_protected_username($_POST['username']))
		{
			logging_procedure($_POST['username'],md5($_POST['password']), $base);
		}
		else Header("Location:logowanie.php?error=sql");
	}
}
function sql_injection_protected_username($text)
{

	if(preg_match('/^[A-Za-z0-9]*@(?:[a-z\d]+[a-z\d-]+\.){1,5}[a-z]{2,6}$/i', $text))
	{
		return true;
	}
	else
	{
		return false;
	}
}

function sql_injection_protected_password($text)
{

	if(preg_match('/^[^"\'%|=]+$/D', $text))
	{
		return true;
	}
	else
	{
		return false;
	}
}
function logging_procedure($user, $password, $base)
{
	$sql_logging = 'SELECT * FROM `users` WHERE `email` LIKE "'.$user.'" AND `password` LIKE "'.$password.'"';
	$result = $base->query($sql_logging);
	if($result->num_rows == 1)
	{
		$_SESSION['email']=$user;
		$_SESSION['status']="student";
		$_SESSION['salt']=time();
		echo "true";
		Header("Location:userpanel.php");
		return;
	}
	$sql_logging_guardian = 'SELECT * FROM `guardians` WHERE `email` LIKE "'.$user.'" AND `password` LIKE "'.$password.'"';
	$result = $base->query($sql_logging_guardian);
	if($result->num_rows == 1)
	{
		$wynik_id = $base->query('SELECT id FROM `guardians` WHERE email like "'.$user.'"');

		$_SESSION['email']=$user;
		$_SESSION['status']="guardian";
		$_SESSION['id']=$wynik_id->fetch_assoc()['id'];
		$_SESSION['salt']=time();
		Header("Location:userpanel.php");
		return;
	}
	else
	{
		Header("Location:logowanie.php?error=login");
	}
}


?>

<!DOCTYPE html>
<html lang="pl">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="">
    <title>Dzienniczek</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  </head>
<body class="background">
	<?php
	if(@$_GET['error'] == "logout")
	{
		echo '<div class="alert alert-success alert-dismissible">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> Zostałeś pomyślnie wylogowany
  </div>';
	}

	if(@$_GET['error'] == "security")
	{
		echo '<div class="alert alert-danger alert-dismissible">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Naruszenie zasad bezpieczeństwa
  </div>';
	}
	?>
	<nav>
    <div>
          <img class="navbar-brand-logo" src="media/logo.png" alt="logo ZSK"/>
          <span class="navbar-brand">Dzienniczek v0.2</span>
        <span class="right logpanel">
          <span>Panel logowania</span>
        </span>
    </div>
  </nav>
  <section>
  <div class="conteiner">
    <div class="card card-container">
      <h1>Zaloguj się</h1>
      <form class="form-signin" action="logowanie.php" method="post">
        <h4>Email:</h4>
        <input type="text" name="username">
			<?php
					if(@$_GET['error'] == "sql")
					{
						echo '<ul style="list-style-type: none;">
								<li>Nieprawidłowe znaki</li>
								</ul>';
					}
					if(@$_GET['error'] == "login")
					{
						echo '<ul style="list-style-type: none;">
								<li>Nieprawidłowy email lub hasło</li>
								</ul>';
					}
					?>

        <h4>Hasło:</h4>
        <input type="password" name="password">

        <button class="btn btn-lg btn-primary btn-block btn-signin">Zaloguj</button>
      </form>
    </div>
  </div>
</section>
 <footer>
   <span>
     Stanisław Polichnowski && Jędrzej Wasik
   </span>
 </footer>
 </body>
 </html>
