<?php
session_start();
if($_SESSION['status']!= 'guardian')
{
	session_destroy();
	header('Location:logowanie.php?error=security');
}

@$base = new mysqli("localhost", "internat", "internat", "internat");
				if(@$base->connect_errno)
				{
					die("Błąd połąćzenia z bazą danych ".$base->connect_errno);
				}
				$base->set_charset("utf8");
				
				echo '
				<form method="post" action="" id="make_comment">
					<textarea name="comment" form="make_comment" cols="50" rows="10">Wpisz opis uwagi</textarea> <br>
					<input type="submit"><br>
				</form>
				<a href="userpanel.php?location=students"><button>Anuluj</button></a> <br>
				';
				if(!empty($_POST))
				{
					if(strlen(@$_POST['comment']) < 30)
					{
						echo 'Wpisz conajmniej 30 znaków w opisie';
					}
					else
					{
						$sql_comment = 'INSERT INTO `comments` (id_student, description, id_guardian)
						VALUES ('.$_GET['student_id'].',"'.$_POST['comment'].'",'.$_SESSION['id'].')
						';
						$base->query($sql_comment);
						header("Location:userpanel.php?location=students&comment=1");
					}
				}
				
?>