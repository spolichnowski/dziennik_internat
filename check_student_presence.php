<?php
$days = date("t",mktime(1,1,1,$month,1,$year));

function presence_color($a,$day,$hour)
{
	if($GLOBALS['month'] == date("m") && $day > date("d"))
	{
		return;
	}
	if($day >$GLOBALS['days'] || $GLOBALS['month'] > date("m") || $GLOBALS['year'] > date("Y"))
	{
		return;
	}

	if(empty($a))
	{
		return '
		<td>
		<select class="form-control form-control-sm" name="presence'.$day.$hour.'" form="student_presence">
			<option value="?" selected>?</option>
			<option value="present">Obecny</option>
			<option value="not_present">Nieobecny</option>
			<option value="justified">Usprawiedliwiony</option>
			<option value="exempt">Zwolniony</option>
		</select>
		</td>
		';
	}
	if($a == 'present')
	{
		return '
		<td style="background-color: rgba(0, 119, 4, 0.8);">
		<select class="form-control form-control-sm" name="presence'.$day.$hour.'" form="student_presence" style="color: green;">
			<option value="present">Obecny</option>
			<option value="not_present">Nieobecny</option>
			<option value="justified">Usprawiedliwiony</option>
			<option value="exempt">Zwolniony</option>
		</select>
		</td>
		';
	}
	if($a == 'not_present')
	{
		return '
		<td style="background-color: rgba(179, 0, 0, 0.9);">
		<select class="form-control form-control-sm" name="presence'.$day.$hour.'" form="student_presence" style="color: red;">
			<option value="present">Obecny</option>
			<option value="not_present" selected>Nieobecny</option>
			<option value="justified">Usprawiedliwiony</option>
			<option value="exempt">Zwolniony</option>
		</select>
		</td>
		';
	}
	if($a == 'justified')
	{
		return '
		<td style="background-color: rgba(255, 195, 0, 0.9);">
		<select class="form-control form-control-sm" name="presence'.$day.$hour.'" form="student_presence" style="color: rgb(255, 195, 0);">
			<option value="present">Obecny</option>
			<option value="not_present">Nieobecny</option>
			<option value="justified" selected>Usprawiedliwiony</option>
			<option value="exempt">Zwolniony</option>
		</select>
		</td>
		';
	}
	if($a == 'exempt')
	{
		return '
		<td style="background-color: rgba(0, 0, 207, 0.9);">
		<select class="form-control form-control-sm" name="presence'.$day.$hour.'" form="student_presence" style="color: rgb(0, 0, 207);">
			<option value="present">Obecny</option>
			<option value="not_present">Nieobecny</option>
			<option value="justified">Usprawiedliwiony</option>
			<option value="exempt" selected>Zwolniony</option>
		</select>
		</td>
		';
	}
}

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
<form id="student_presence" method="post" action="presence_validate.php">
<input type="hidden" name="student_presence" value="'.@$_GET['student_id'].'">
<input type="hidden" name="year" value="'.$year.'">
<input type="hidden" name="month" value="'.$month.'">
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
			<td>'.$i.'-'.$month.'-'.$year.'</td>
			'.presence_color(@$presence[$year.'-'.$month.'-'.$day.' 08:00:00'],$day,'08').'
			'.presence_color(@$presence[$year.'-'.$month.'-'.$day.' 15:00:00'],$day,'15').'
			'.presence_color(@$presence[$year.'-'.$month.'-'.$day.' 20:00:00'],$day,'20').'

		</tr>';
	}
echo'
</table>
<input class="button-style3" type="submit" value="Zapisz">
</form>

';
	echo '<a href="userpanel.php?location=students"><button class="button-style">Wróć</button></a>';
?>
