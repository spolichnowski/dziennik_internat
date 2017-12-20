<?php

if($_SESSION['status'] == 'guardian')
{
	if(!isset($_POST['student_id']))
	{
		$result = $base->query('SELECT id, concat(first_name," ", last_name) as "name" FROM `students`');
		echo '<h3> Dziesiejsza data to: ';
		echo date("d-m-Y"),"</h3>";
		echo '<h4>Wybierz godzinę:</h4><select class="form-control form-control-sm" name="hour" form="presence">
		<option value ="08">8:00</option>
		<option value ="15">15:00</option>
		<option value ="20">20:00</option>
		</select>
		';
		echo '<form id="presence" method="post" action="presence_validate.php"><table class="table table-bordered table-hover table-margin">';


		echo '<tr><th>Imię i 	nazwisko</th><th>Obecny</th><th>Nieobecny</th><th>Zwolniony</th></tr>';
	while ($row = $result->fetch_assoc())
	{
	echo '
		<tr>
			<td>'.$row['name'].'</td>
			<td>
			<input type="hidden" name="id'.$row['id'].'" value="'.$row['id'].'">
			<input class="form-check-input" type="radio" name="presence_value'.$row['id'].'" value="present"></td>
			<td><input class="form-check-input" type="radio" name="presence_value'.$row['id'].'" value="not_present"></td>
			<td><input class="form-check-input" type="radio" name="presence_value'.$row['id'].'" value="justified"></td>
		<tr>';

	}
	echo '</table>';
	echo '<button class="button-style2">Zapisz</button>';
	echo '</form>';
	}
	else
	{

	}

}
else
{
	session_destroy();
	header('Location:logowanie.php?error=security');
}
?>
