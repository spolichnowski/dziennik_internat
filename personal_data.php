<?php

$result = $base->query(
'
SELECT *
FROM `students`
WHERE `id` LIKE
(SELECT `id_student` from `users` WHERE email like "'.$_SESSION['email'].'");

'
);
function plec($plec)
{
	if($plec == 'male')return 'Mężczyzna';
	else return 'Kobieta';
}
$comments = array();
$row = $result->fetch_row();
echo
'<table class="table table-bordered table-hover table-margin">
	<tr>
		<th>Imię</th>
		<td>'.$row[1].'</td>
	</tr>
	<tr>
		<th>Nazwisko</th>
		<td>'.$row[2].'</td>
	</tr>
	<tr>
		<th>Data Urodzenia</th>
		<td>'.$row[3].'</td>
	</tr>
	<tr>
		<th>Płeć</th>
		<td>'.plec($row[4]).'</td>
	</tr>
</table>';
?>
