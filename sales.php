<?php
include 'includes/session.php';


function orderReceivedMail($dealer_name, $details)
{
	
	$mail_body = '<!DOCTYPE html PUBLIC>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>:: CONTINENTAL ::</title>
        <style>
            body { margin:0px; padding:0px;}
            .tbl_orders { border: solid 1px #000000; border-collapse: collapse;}
            .td_orders { border: solid 1px #000000;}
        </style>
    </head>
    <body>
        <table border="0" cellpadding="0" cellspacing="0" style="width:600px; float:left; background-color:#cbcaca; margin:0px; padding:0px;">
            <tr align="center" valign="top" style="width:100%; float:left; margin:0px; padding:0px">
                <td align="center" valign="top" colspan="3" style="width:100%; float:left;">
                    <img src="http://crd4.thetrinket.in/images/mail_img.jpg" style="width:100%;"/>
                </td>
            </tr>
            <tr align="center" valign="top" style="width:100%; float:left; background-color:#cbcaca; margin:-35px 0px 0px 0px; padding:0px;">
                <td align="center" valign="top" style="width:5%; min-height:20px; float:left;">&nbsp;</td>
                <td align="center" valign="top" style="width:90%; float:left; background-color:#faa61a; text-align:left;">
                     <table border="0" cellpadding="0" cellspacing="0"  style="width:100%; margin:0px; padding:0px; float:left;">
                        <tr align="center" valign="top" style="width:100%; float:left;">
                            <td align="center" valign="top" style="width:5%; min-height:20px; float:left;">&nbsp;</td>
                            <td align="center" valign="top" style="width:85%; padding:0px 10px; float:left; text-align:left; color:#000; font-size:14px; line-height:21x; font-family: Arial, Helvetica, sans-serif; ">
                                <br/><br/>Dear '.$dealer_name.',<br/><br/>
Your Order has been received!<br/><br/>

Please find the details of your order below:<br/><br/>';
							
							$mail_body .= '<table class="tbl_orders"><tr><th class="td_orders">Order ID</th><th class="td_orders">Product Code</th><th class="td_orders">Product Name</th><th class="td_orders">Quantity</th></tr>';
							foreach($details as $detail)
							{
								$mail_body .= '<tr><td class="td_orders">'.$detail['order_id'].'</td><td class="td_orders">'.$detail['model_no'].'</td><td class="td_orders">'.$detail['product_name'].'</td><td class="td_orders">'.$detail['quantity'].'</td></tr>';
							}
							$mail_body .= '</table>';
                            $mail_body .= '<br><br>Best Regards,<br/>
<strong>Continental Rewards Dhamaka</strong><br/>
<strong>Continental India Pvt Ltd</strong><br/><br/></td>
                            <td align="center" valign="top" style="width:5%; min-height:20px; float:left;">&nbsp;</td>
                        </tr>
                     </table>
                </td>
                <td align="center" valign="top" style="width:5%; min-height:20px; float:left;">&nbsp;</td>
            </tr>
            <tr align="center" valign="top" style="width:100%; float:left; margin:0px; padding:0px;">
                <td align="center" valign="top" colspan="3" style="width:100%; float:left; height:20px;">&nbsp;
                </td>
            </tr>
        </table>
    </body>
</html>';
	
	return $mail_body;
}

function sendEmail($email_id,$subject,$body,$cc=NULL)
{
	$to      = $email_id;
	$subject = $subject;
	$message = $body;
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= 'BCC: vinod@novuslogic.co.in' . "\r\n";
	$headers .= 'From: Continental Rewards <continentalrewards@thetrinket.in>' . "\r\n" .
    'Reply-To: continentalrewards@thetrinket.in' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

	mail($to, $subject, $message, $headers,'-freturn@thetrinket.in');
}

	if(isset($_POST['pay'])){
		$payid = $_POST['pay'];
		$delivery_address = $_POST['delivery_address'];
		$no_of_items = 1;
		//echo($delivery_address); die;
		$date = date('Y-m-d');
		
		$points_balance =  $user['points_balance'];
		$cart_total = $_SESSION['cart_total'];
		
		if($points_balance < $cart_total)
		{
			$_SESSION['error'] = "You don't have sufficient balance. Please remove an item from the cart to proceed";
			header('location: cart_view.php');
		}
		elseif($cart_total == '0')
		{
			$_SESSION['error'] = "Your cart is Empty! Please add a product to proceed";
			header('location: shop.php');
		}
		else
		{
			$conn = $pdo->open();

			try
			{
				
				$stmt = $conn->prepare("INSERT INTO sales (user_id, pay_id, sales_date, no_of_items, delivery_address) VALUES (:user_id, :pay_id, :sales_date, :no_of_items, :delivery_address)");
				$stmt->execute(['user_id'=>$user['id'], 'pay_id'=>$payid, 'sales_date'=>$date, 'no_of_items' => $no_of_items, 'delivery_address' => $delivery_address]);
				$salesid = $conn->lastInsertId();
				
				
				try{
					$stmt = $conn->prepare("SELECT * FROM cart LEFT JOIN products ON products.id=cart.product_id WHERE user_id=:user_id");
					$stmt->execute(['user_id'=>$user['id']]);
					$total_cart_amount = 0;
					$no_of_items = 0;
					foreach($stmt as $row)
					{
						$no_of_items++;
						$qty = $row['quantity'];
						$total_cart_amount += $row['price'] * $qty;
						//$total_cart_amount += $row['amount'];
						$stmt = $conn->prepare("INSERT INTO details (sales_id, product_id, quantity) VALUES (:sales_id, :product_id, :quantity)");
						$stmt->execute(['sales_id'=>$salesid, 'product_id'=>$row['product_id'], 'quantity'=>$row['quantity']]);
						$details_id = $conn->lastInsertId();
						$order_id = "CRD4".str_pad($details_id,5,"0",STR_PAD_LEFT);
						
						$stmt = $conn->prepare("UPDATE details SET order_id = :order_id WHERE id = :details_id");
						$stmt->execute(['order_id' => $order_id, 'details_id'=>$details_id]);
					}
					
					$stmt = $conn->prepare("UPDATE sales SET no_of_items = :no_of_items WHERE id = :sales_id");
					$stmt->execute(['no_of_items' => $no_of_items, 'sales_id'=>$salesid]);

					$stmt = $conn->prepare("UPDATE users SET points_balance = points_balance - :total_cart_amount, points_redeemed =  points_redeemed + :total_cart_amount WHERE id = :user_id");
					$stmt->execute(['total_cart_amount' => $total_cart_amount, 'user_id'=>$user['id']]);
					
					$stmt = $conn->prepare("DELETE FROM cart WHERE user_id=:user_id");
					$stmt->execute(['user_id'=>$user['id']]);

					$_SESSION['success'] = 'Transaction successful. Thank you.';
					
					//Send Acknowledgement Email
					$stmt = $conn->prepare("SELECT sales.id `sales_id`, products.model_no, products.name `product_name`, details.order_id, details.quantity FROM details LEFT JOIN products ON products.id=details.product_id LEFT JOIN sales ON sales.id = details.sales_id WHERE sales_id=:sales_id");
					$stmt->execute(['sales_id'=>$salesid]);
					$details = array();
					foreach($stmt as $row)
					{
						$details[] = $row;
					}
					
					$mail_body = orderReceivedMail($user['dealer_name'], $details);
					//echo $mail_body; die;
					sendEmail($user['email'], "Continental CRD4 | Order Received", $mail_body, '');
				}
				catch(PDOException $e){
					$_SESSION['error'] = $e->getMessage();
				}

			}
			catch(PDOException $e){
				$_SESSION['error'] = $e->getMessage();
			}

			$pdo->close();
			header('location: orders.php');
		}
	}
	
?>