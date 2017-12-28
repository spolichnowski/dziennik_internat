<?php
	session_start();
	@$base = new mysqli("localhost", "internat", "internat", "internat");
	
	//Policzenie nieprzeczytanych uwag
	$result = $base->query('SELECT `id_student` FROM `users` WHERE `email` LIKE "'.$_SESSION['email'].'" AND `status` LIKE "parent"');
	$student = $result->fetch_row();
	if(isset($student))
	{
		$comment_count = $base->query('SELECT * FROM `comments` WHERE `id_student` LIKE "'.$student[0].'" AND `checked` LIKE 0')->num_rows;
	}
	//koniec liczenia
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
    </head>
  <body>
    <header id="header" class="">
      <nav data-offset-top="-50" data-spy="affix" class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
								<div>
                  <img class="navbar-brand-logo1" src="media/logo.png" alt="logo ZSK"/>
                  <span class="navbar-brand">Dzienniczek v0.2</span>
								</div>
            </div>
            <div class="collapse navbar-collapse navbar-right" id="myNavbar">
            <ol class="nav navbar-nav">
			<?php
				if($_SESSION['status']=='guardian')
				{
					echo '<li><a href="userpanel.php?location=check_presence">Sprawdź obecność</a></li>';
					echo '<li><a href="userpanel.php?location=rooms">Lista pokoi</a></li>';
					echo '<li><a href="userpanel.php?location=students">Lista uczniów</a></li>';
				}
				if($_SESSION['status']=='student')
				{
					echo'
					<li><a href="userpanel.php?location=presence">Frekwencja</a></li>
					<li><a href="userpanel.php?location=comments">Uwagi';
					if($comment_count != 0)
					{
						//Wyświetlenie ilości nieprzeczytanych uwag przy linku "UWAGI"
						echo '(',$comment_count,')';
					}					
					echo'
					</a></li>
					<li><a href="userpanel.php?location=personal_data">Dane ucznia</a></li>
					';
				}
				
				
			?>

                <li><a href="userpanel.php?location=messages">Wiadomości</a></li>
				<?php
					if($_SESSION['status']=='student')
					{
						$result = $base->query('SELECT concat(`first_name`," ",last_name) FROM `users` WHERE `email` LIKE "'.$_SESSION['email'].'"');
					}
					else
					{
						$result = $base->query('SELECT concat(`first_name`," ",last_name) FROM `guardians` WHERE `email` LIKE "'.$_SESSION['email'].'"');
					}
					echo '<li>' , $result->fetch_row()[0], '</li>'
				?>
                <li class="border margin"><span><a href="logowanie.php?error=logout">Wyloguj</a></span></li>
            </ol>
          </div>
      </nav>
  </header>
<!--
  <article class="margin-content col-md-3">
    <div class="nav2 col-md-3">
      <ol>
        <li><a>Obecność</a></li>
        <li><a>Uwagi</a></li>
        <li><a>Informacje o uczniu</a></li>
        <li><a>Usprawiedliwienia</a></li>
      </ol>
    </div>
  </article>
-->
<br><br><br><br>
  <article class="col-md-12 tabela">
			  <?php
				if(@$base->connect_errno)
				{
					die("Błąd połąćzenia z bazą danych ".$base->connect_errno);
				}
				else
				{
					$base->query('SET character_set_server = "UTF-8"');
					$base->set_charset("utf8");
					if(@$_GET['location'] == "presence")include("presence.php");
					if(@$_GET['location'] == "check_presence")include("check_presence.php");
					if(@$_GET['location'] == "comments")include("comments.php");
					if(@$_GET['location'] == "students")include("students.php");
					if(@$_GET['location'] == "personal_data")include("personal_data.php");
					if(@$_GET['location'] == "rooms")include("rooms.php");
					if(@$_GET['location'] == "make_comment")include("make_comment.php");
				}

			  $base->close();
			 ?>

  </article>

  <footer>
    <span>
      Stanisław Polichnowski && Jędrzej Wasik
    </span>
  </footer>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="bootstrap.min.js"></script>
  </body>
</html>
