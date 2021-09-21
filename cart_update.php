<?php
	include 'includes/session.php';

	$conn = $pdo->open();

	$output = array('error'=>false);

	$id = $_POST['id'];
	$qty = $_POST['qty'];

	if(isset($_SESSION['user']))
	{
		try
		{
			$total = 0;
			$subtotal = 0;
			$stmt = $conn->prepare("SELECT *, cart.id AS cartid FROM cart LEFT JOIN products ON products.id=cart.product_id WHERE user_id=:user");
			$stmt->execute(['user'=>$user['id']]);
			foreach($stmt as $row)
			{
				$subtotal = $row['price']*$row['quantity'];
				$total += $subtotal;
				
			}
			$_SESSION['cart_total'] = $total;
			$stmt = $conn->prepare("UPDATE cart SET quantity=:quantity, amount=:amount WHERE id=:id");
			$stmt->execute(['quantity'=>$qty, 'amount'=>$subtotal, 'id'=>$id]);
			$output['message'] = 'Updated';
		}
		catch(PDOException $e)
		{
			$output['message'] = $e->getMessage();
		}
	}
	else{
		foreach($_SESSION['cart'] as $key => $row){
			if($row['productid'] == $id){
				$_SESSION['cart'][$key]['quantity'] = $qty;
				$output['message'] = 'Updated';
			}
		}
	}

	$pdo->close();
	echo json_encode($output);

?>