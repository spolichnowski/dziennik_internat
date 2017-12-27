<?php
function presence_value($text)
{
	if($text == 'present')
	{
		return '<td style="color: green;">Obecny</td>';
	}
	if($text == 'not_present')
	{
		return '<td style="color: red;">Nieobecny</td>';
	}
	if($text == 'justified')
	{
		return '<td style="color: yellow;">Usprawiedliwiony</td>';
	}
	if($text == 'exempt')
	{
		return '<td style="color: blue;">Zwolniony</td>';
	}
}

if(!isset($_GET['month']))
{
	$month = date("m");
	$_GET['month']=$month*$_SESSION['salt'];
}
else
{
	$month = $_GET['month']/$_SESSION['salt'];
}
if(empty($_GET['year']))
{
	$year = date("Y");
	$_GET['year']=$year*$_SESSION['salt'];
}
else
{
	$year = $_GET['year']/$_SESSION['salt'];
}
if($_GET['month']/$_SESSION['salt'] > 12)
{
	$month = 1;
	$year++;
	$_GET['year']=$year*$_SESSION['salt'];
	$_GET['month']=1*$_SESSION['salt'];
}
if($_GET['month'] == 0)
{
	$month = 12;
	$year--;
	$_GET['year']=$year*$_SESSION['salt'];
	$_GET['month']=12*$_SESSION['salt'];
}

$presence = array();
if(isset($_GET['student_id']) && $_SESSION['status'] == 'guardian')
{
	$name = $base->query('SELECT concat(First_name," ",Last_name) as "imie" FROM `students` where `id` LIKE '.$_GET['student_id'].'');
	echo $name->fetch_assoc()['imie'], '<br>';
	$result = $base->query('SELECT * FROM `presence` where `id_student` LIKE "'.$_GET['student_id'].'" AND month(presence_date) like '.$month.' AND year(presence_date) like '.$year.'');

	include("check_student_presence.php");
}
else
{
	$result = $base->query('SELECT * FROM `presence` where `id_student` LIKE (SELECT `id_student` from `users` where email like "'.$_SESSION['email'].'") AND month(presence_date) like '.$month.' AND year(presence_date) like '.$year.'');

while ($row = $result->fetch_assoc())
{
	$presence[$row['presence_date']] = $row['status'];
}

echo '
	<a href="userpanel.php?location=presence&month='.($month-1)*$_SESSION['salt'].'&year='.$year*$_SESSION['salt'].'&student_id='.@$_GET['student_id'].'"><button class="button-style">Poprzedni miesiąc</button></a>
	<a href="userpanel.php?location=presence&month='.($month+1)*$_SESSION['salt'].'&year='.$year*$_SESSION['salt'].'&student_id='.@$_GET['student_id'].'"><button class="button-style">Następny miesiąc</button></a>
	<br>
';
echo '
<table class="table table-bordered table-hover table-margin">
	<tr>
		<th>Data</th>
		<th>Godzina 8:00</th>
		<th>Godzina 15:00</th>
		<th>Godzina 20:00</th>
	</tr>';
	for($i=1; $i<=date("t",mktime(0,0,0,$month,1,1)); $i++)
	{
		if($i<10)
		{
			$day = "0".$i;
		}
		else
		{
			$day = $i;
		}
		echo '
		<tr>
			<td>'.$i.'-'.$month.'-'.$year.'</td>';
			echo presence_value(@$presence[$year.'-'.$month.'-'.$day.' 08:00:00']);
			echo presence_value(@$presence[$year.'-'.$month.'-'.$day.' 15:00:00']);
			echo presence_value(@$presence[$year.'-'.$month.'-'.$day.' 20:00:00']);

		echo '</tr>';
	}
echo'
</table>
';
}


?>
