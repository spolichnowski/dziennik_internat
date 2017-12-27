<?php

	if(@$_GET['comment']==1)
	{
		echo '<div class="alert alert-success alert-dismissible">
		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> Dodano uwagę
		</div>';
	}


	if(isset($_GET['student_id']))
	{

	}
	else
	{
		$sql_students_list = 'SELECT * FROM `students`;';
		$result = $base->query($sql_students_list);
		echo '<table class="table table-bordered table-hover table-margi">';

		echo'
			<tr>
				<th>Imię i Nazwisko</th>
				<th>Data urodzenia</th>
				<th>Pokój</th>
			</tr>';

		while($row = $result->fetch_row())
		{
			echo'
			<tr>
				<td><a href="userpanel.php?location=presence&student_id='.$row[0].'">'.$row[1].' '.$row[2].'</a></td>
				<td>'.$row[3].'</td>
				<td>'.$row[5].'</td>
			</tr>';
		}
		echo '</table>';
	}
?>
