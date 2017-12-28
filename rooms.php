<?php

		$sql_rooms_list = 'SELECT `rooms`.`id`,count(students.room), `capacity` FROM `rooms`
		LEFT JOIN `students` ON `rooms`.`id` = `students`.`room`
		GROUP BY `rooms`.`id`
		;';
		$result = $base->query($sql_rooms_list);
		echo '<table class="table table-bordered table-hover table-margi">';

		echo'
			<tr>
				<th>Numer</th>
				<th>Ilość uczniów</th>
				<th>Pojemność</th>
			</tr>';

		while($row = $result->fetch_row())
		{
			echo'
			<tr>
				<td><a href="userpanel.php?location=students&room_id='.$row[0].'">'.$row[0].'</a></td>
				<td>'.$row[1].'</td>
				<td>'.$row[2].'</td>
			</tr>';
		}
		echo '</table>';

?>
