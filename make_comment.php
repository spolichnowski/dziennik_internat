<?php
if($_SESSION['status']!= 'guardian')
{
	session_destroy();
	header('Location:logowanie.php?error=security');
}
				echo '
				<form method="post" action="" id="make_comment">
					<textarea name="comment" form="make_comment" cols="50" rows="10">Wpisz opis uwagi</textarea> <br>
					<a><button>Prześlij uwagę</button></a>
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