<?php

$result = $base->query(
'SELECT id ,description, date_comments, guardians.first_name, guardians.last_name
FROM `comments`
JOIN `guardians` on `guardians`.`id` = `comments`.`id_guardian`
WHERE `id_student` LIKE (SELECT `id_student` from `users` WHERE email like "'.$_SESSION['email'].'")'
);

echo '<table class="table table-bordered table-hover table-margin">';
echo $result->num_rows;
$comments = array();
while ($row = $result->fetch_assoc())
{
	$comments[$row['id']] = $row['description'];
}
	echo '
	<tr>
		<td>'.@$comments['1'].'</td>
	</tr>';
	echo '</table>';
?>
