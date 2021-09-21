<?php

include 'simplexlsx.class.php';
//include('common.php');
include('db.php');

$db = mysqli_connect(DB_SERVER,DB_USER,DB_PASSWORD);
mysqli_select_db($db,DB);

function dbConnect()
{
	$this->db = mysqli_connect(DB_SERVER,DB_USER,DB_PASSWORD);
	if($this->db)
		mysqli_select_db($this->db,DB);
}
		
function dbClose()
{
	mysqli_close($this->db);
}

$xlsx = new SimpleXLSX('Products.xlsx');

echo '<table cellpadding="10">
<tr><td valign="top">';

// output worsheet 1

list($num_cols, $num_rows) = $xlsx->dimension();

//echo '<h1>Sheet 1</h1>';
echo '<table>';
$k=0;
$new_entries = 0;
$updated = 0;
foreach( $xlsx->rows() as $r ) 
{
	if($k<1)
	{
		$k++;
		continue;
	}
	$row_no = $k+1;
	$model_no = mysqli_escape_string($db,trim($r[0]));
	$brand_name = mysqli_escape_string($db,trim($r[1]));
	$product_name = mysqli_escape_string($db,trim($r[2]));
	$description = mysqli_escape_string($db,trim($r[3]));
	$photo = mysqli_escape_string($db,trim($r[4]));
	$price = mysqli_escape_string($db,trim($r[5]));
	$category_name = mysqli_escape_string($db,trim($r[6]));
	$slug = str_replace(' ','-',strtolower($product_name));
	
	
	$sql = mysqli_query($db, "SELECT id from category where name = '$category_name'");
	if(mysqli_num_rows($sql) > 0)  
	{
		$rlt = mysqli_fetch_array($sql,MYSQLI_ASSOC);
		$category_id = $rlt['id'];
		mysqli_query($db, "INSERT INTO products (`name`, `category_id`, `brand_name`, `model_no`, `description`, `photo`, `price`, `slug`, `created`, `modified`) values ('$product_name', '$category_id', '$brand_name', '$model_no', '$description', '$photo', '$price', '$slug', now(),now())");
		$product_id = mysqli_insert_id($db);
		if(!$product_id)
		{
			//echo "$product_name: Product not created<br>";
			echo mysqli_error($db);
			$k++; 
			continue;
		}
		else
			$new_entries++;
	}
	else
	{
		echo "$brand_name: Brand not found<br>";
		$k++; 
		continue;
	}
	
	
	$k++;
	
}
echo '</table>';

echo '</td><td valign="top">';


echo '</td></tr>';
echo "<tr><td>New Entries: $new_entries</td></tr><tr><td>Updated: $updated</td></tr></table>";

?><?php

?><?php

?>