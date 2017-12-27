<?php


$result = $base->query(' SELECT * FROM `users` WHERE `email` LIKE "'.$_SESSION['email'].'" and status like "parent" ');
if($result->num_rows >0)
{
	$base->query(
		'
		UPDATE `comments`
		SET `checked`=1
		WHERE `id_student` LIKE
		(
			SELECT `id_student`
			FROM `users`
			WHERE `email` LIKE "'.$_SESSION['email'].'"
		);
		'
);
}

$result = $base->query(
'SELECT id ,description, date_comments, guardians.first_name, guardians.last_name
FROM `comments`
JOIN `guardians` on `guardians`.`id` = `comments`.`id_guardian`
WHERE `id_student` LIKE (SELECT `id_student` from `users` WHERE email like "'.$_SESSION['email'].'")'
);

echo '<table class="table table-bordered table-hover table-margin">';
while ($row = $result->fetch_row())
{
	echo '
	<tr>
		<td>'.@$row[1].'</td>
		<td>'.@$row[3].' '.@$row[4].'</td>
		<td>'.@$row[2].'</td>
	</tr>';
}
	
	echo '</table>';
?>
