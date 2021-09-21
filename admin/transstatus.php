<?php
	include 'includes/session.php';

	$id = $_POST['id'];

	$conn = $pdo->open();

	$output = array('list'=>'');

	$stmt = $conn->prepare("SELECT * FROM order_status_details WHERE details_id=:id");
	$stmt->execute(['id'=>$id]);

	$total = 1;
	foreach($stmt as $row){
                $path1 = $row['status_doc'];
                $path=str_replace(" ", '%20', $path1);
		$image = (!empty($row['status_doc'])) ? '<a href=../images/'.$path.'>Download</a>' : 'not uploaded';
		$output['list'] .= "
			<tr class='prepend_items'>
				<td>".$total."</td>
				<td>".$row['created']."</td>
				<td>".$row['order_status']."</td>
				<td>".$row['status_comment']."</td>
                                <td>".$image." <form action='update_status_document.php' enctype='multipart/form-data' method='POST'>
                                <input type='hidden' name='id' value=".$row['id']."> <input type='hidden' name='details_id' value=".$row['details_id'].">"
                        . "<input type='hidden' name='status' value='".$row['order_status']."'>
                                    <input type='file' name='status_doc' onchange='form.submit()'></form> </td>
			</tr>
		";
	$total++;
                
        }
	
	
	$pdo->close();
	echo json_encode($output);

?>