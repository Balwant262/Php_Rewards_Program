<?php

include 'simplexlsx.class.php';
include('common.php');
include('db_live.php');

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

function randomPassword()
{
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789!@#$%^&*()";
    $pass = array(); // remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; // put the length -1 in cache
    for ($i = 0; $i < 8; $i ++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); // turn the array into a string
}

$xlsx = new SimpleXLSX('Dealers.xlsx');

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
	$dealer_code = mysqli_escape_string($db,trim($r[1]));
	$dealer_name = mysqli_escape_string($db,trim($r[2]));
	$total_points_earned = mysqli_escape_string($db,trim($r[5]));
	$contact_info = mysqli_escape_string($db,trim($r[7]));
	$firstname = mysqli_escape_string($db,trim($r[8]));
	$lastname = mysqli_escape_string($db,trim($r[9]));
	$address1 = mysqli_escape_string($db,trim($r[10]));
	$address2 = mysqli_escape_string($db,trim($r[11]));
	$address3 = mysqli_escape_string($db,trim($r[12]));
	$city = mysqli_escape_string($db,trim($r[13]));
	$state = mysqli_escape_string($db,trim($r[14]));
	$pincode = mysqli_escape_string($db,trim($r[15]));
	$email = mysqli_escape_string($db,trim($r[16]));
	
	$password_orig = randomPassword();	
	$password = password_hash($password_orig, PASSWORD_DEFAULT);
	
	$address = $address1."<br>".$address2."<br>".$address3."<br>".$city."<br>".$state."<br>Pincode: ".$pincode;
	
	$rlt = mysqli_fetch_array($sql,MYSQLI_ASSOC);
	$category_id = $rlt['id'];
	mysqli_query($db, "INSERT INTO dealers (`email`, `password_orig`, `password`, `dealer_code`, `dealer_name`, `firstname`, `lastname`, `address`, `city`, `state`, `pincode`, `contact_info`, `total_points_earned`, `points_balance`) values ('$email', '$password_orig', '$password', '$dealer_code', '$dealer_name', '$firstname', '$lastname', '$address', `$city`,`$state`,`$pincode`,`$contact_info`,`$total_points_earned`,`$total_points_earned`)");
	$dealer_id = mysqli_insert_id($db);
	if(!$dealer_id)
	{
		echo "$dealer_code: Dealer not created<br>";
		echo mysqli_error($db);
		$k++; 
		continue;
	}
	else
		$new_entries++;
	
	
	$k++;
	
}
echo '</table>';

echo '</td><td valign="top">';


echo '</td></tr>';
echo "<tr><td>New Entries: $new_entries</td></tr><tr><td>Updated: $updated</td></tr></table>";

?><?php

?><?php

?>