<?php
@$base = new mysqli("localhost", "internat_teacher", "internat_teacher", "internat");
	if(@$base->connect_errno)
	{
		die("Błąd połączenia z bazą danych ".$base->connect_errno);
	}
session_start();
function prepare_day($i)
{
	if($i<10)
	{
		return '0'.$i;
	}
	else return $i;
}

if($_SESSION['status']=='guardian')
{
	if(@empty($_POST['student_presence']))
	{

	$result = $base->query('SELECT * FROM `guardians` WHERE email like "'.$_SESSION['email'].'"');
	echo $result->num_rows, '<br>';

	$hour = $_POST['hour'];
	echo 'hour>> ',$hour,'<br>';

	$liczba = (count($_POST)+1)/2;
	echo $liczba;

	for($i = 1; $i<$liczba; $i++)
	{
		$student = 'id'.$i;
		$student_status = 'presence_value'.$i;
		@$id = $_POST[$student];
		@$status = $_POST[$student_status];
		//echo $id,' => ',$status, '<br>';
		if(isset($id) && isset($status))
		{
			$zapytanie = 'INSERT INTO `presence` (`id_student`,`presence_date`,`status`,`id_guardian`) values ('.$id.',"'.date("Y-m-d").' '.$hour.':00:00","'.$status.'",'.$_SESSION['id'].')';
			$base->query($zapytanie);
			echo $zapytanie, '<br>';
		}
		else
		{
			break;
		}
	}
	header('Location:userpanel.php?location=check_presence');
	}
	else
	{
		echo '<pre>';
		print_r($_POST);
		echo '</pre>';
		for($i=1;$i<=(count($_POST)-3)/3;$i++)
		{
			$day = prepare_day($i);
			$presence8 = $_POST['year'].'-'.$_POST['month'].'-'.$day.' 08:00:00';
			$presence15 = $_POST['year'].'-'.$_POST['month'].'-'.$day.' 15:00:00';
			$presence20 = $_POST['year'].'-'.$_POST['month'].'-'.$day.' 20:00:00';
			$presence_value8 = $_POST['presence'.$day.'08'];
			$presence_value15 = $_POST['presence'.$day.'15'];
			$presence_value20 = $_POST['presence'.$day.'20'];
			if($presence_value8 !='?')
			{
				$sql_update_presence8 = 'INSERT INTO `presence` (`id_student`,`presence_date`,`status`,`id_guardian`)
				VALUES("'.$_POST['student_presence'].'", "'.$presence8.'", "'.$presence_value8.'",'.$_SESSION['id'].' )
				ON DUPLICATE KEY
				UPDATE status = "'.$presence_value8.'";';
				@$base->query($sql_update_presence8);
			}
			if($presence_value15 !='?')
			{
				$sql_update_presence15 = 'INSERT INTO `presence` (`id_student`,`presence_date`,`status`,`id_guardian`)
				VALUES("'.$_POST['student_presence'].'", "'.$presence15.'", "'.$presence_value15.'",'.$_SESSION['id'].' )
				ON DUPLICATE KEY
				UPDATE status = "'.$presence_value15.'";';
				@$base->query($sql_update_presence15);
			}
			if($presence_value20 !='?')
			{
				$sql_update_presence20 = 'INSERT INTO `presence` (`id_student`,`presence_date`,`status`,`id_guardian`)
				VALUES("'.$_POST['student_presence'].'", "'.$presence20.'", "'.$presence_value20.'",'.$_SESSION['id'].' )
				ON DUPLICATE KEY
				UPDATE status = "'.$presence_value20.'";';
				@$base->query($sql_update_presence20);
			}

		}
		Header("Location:userpanel.php?location=students");
	}
	$base->close();

}
else
{
	session_destroy();
	header('Location:logowanie.php?error=security');
}

?>
