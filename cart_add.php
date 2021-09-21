<?php
	include 'includes/session.php';

	$conn = $pdo->open();

	$output = array('error'=>false);

	$id = $_POST['id'];
	$quantity = $_POST['quantity'];

	if(isset($_SESSION['user']))
	{
		$stmt = $conn->prepare("SELECT *, COUNT(*) AS numrows FROM cart WHERE user_id=:user_id AND product_id=:product_id");
		$stmt->execute(['user_id'=>$user['id'], 'product_id'=>$id]);
		$row = $stmt->fetch();
		if($row['numrows'] < 1)
		{
			$stmt = $conn->prepare("SELECT price FROM products WHERE id=:product_id");
			$stmt->execute(['product_id'=>$id]);
			$row = $stmt->fetch();
			$price = $row['price'];
			$total_price = $price * $quantity;
			
			$stmt = $conn->prepare("SELECT sum(amount) `total_cart_amount` FROM cart WHERE user_id=:user_id");
			$stmt->execute(['user_id'=>$user['id']]);
			$row = $stmt->fetch();
			$total_cart_amount = $row['total_cart_amount'];
			
			$stmt = $conn->prepare("SELECT points_balance FROM users WHERE id=:user_id");
			$stmt->execute(['user_id'=>$user['id']]);
			$row = $stmt->fetch();
			$user_point_balance = $row['points_balance'];
			
			if(($total_price + $total_cart_amount) > $user_point_balance)
			{
				$output['error'] = true;
				$output['message'] = "You don't have sufficient points to add this product";
			}
			else
			{
				try
				{
					$_SESSION['cart_total'] = $total_price + $total_cart_amount;
					$stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity, amount) VALUES (:user_id, :product_id, :quantity, :amount)");
					$stmt->execute(['user_id'=>$user['id'], 'product_id'=>$id, 'quantity'=>$quantity, 'amount' => $total_price]);
					$output['message'] = 'Item added to cart';
					
				}
				catch(PDOException $e){
					$output['error'] = true;
					$output['message'] = $e->getMessage();
				}
			}
			
		}
		else
		{
			$output['error'] = true;
			$output['message'] = 'Product already in cart';
		}
	}
	else
	{
		if(!isset($_SESSION['cart']))
		{
			$_SESSION['cart'] = array();
		}

		$exist = array();

		foreach($_SESSION['cart'] as $row)
		{
			array_push($exist, $row['productid']);
		}

		if(in_array($id, $exist))
		{
			$output['error'] = true;
			$output['message'] = 'Product already in cart';
		}
		else
		{
			$data['productid'] = $id;
			$data['quantity'] = $quantity;
			$data['amount'] = $total_price;

			if(array_push($_SESSION['cart'], $data))
			{
				$output['message'] = 'Item added to cart';
			}
			else
			{
				$output['error'] = true;
				$output['message'] = 'Cannot add item to cart';
			}
		}

	}
	
	$pdo->close();
	echo json_encode($output);

?>