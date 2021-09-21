<?php
	include 'includes/session.php';
	include 'includes/slugify.php';

	if(isset($_POST['edit'])){
		$id = $_POST['id'];
                
                $user_id = $_POST['user_id'];
                $user_name = $_POST['user_name'];
                $product_id = $_POST['product_id'];
		$email = $_POST['user_email'];
                $old_product_name = $_POST['old_product_name'];
                $new_product_name = $_POST['new_product_name'];
                $order_id = $_POST['order_txt_id'];
		$conn = $pdo->open();
                
                function orderReceivedMail($dealer_name, $order_id, $old_product_name, $new_product_name)
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
                As confirmed with you, the following product has been changed from your order.<br/><br/>

                Please find the details of your order below:<br/><br/>';

                                                                        $mail_body .= '<table class="tbl_orders"><tr>'
                                                                                . '<th class="td_orders">Order ID</th>'
                                                                                . '<th class="td_orders">OLD Product Name</th>'
                                                                                . '<th class="td_orders">New Product Name</th>'
                                                                                . '</tr>';
                                                                        $mail_body .= '<tr><td class="td_orders">'.$order_id.'</td><td class="td_orders">'.$old_product_name.'</td><td class="td_orders">'.$new_product_name.'</td></tr>';
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
                
                
		try{
                    $stmt = $conn->prepare("UPDATE details SET product_id=:product_id WHERE id=:id");
                    $stmt->execute(['product_id'=>$product_id, 'id'=>$id]);

                    $mail_body = orderReceivedMail($user_name, $order_id, $old_product_name, $new_product_name);
                    sendEmail($email, "Continental CRD4 | Product Change Confirmation", $mail_body, '');

                    $_SESSION['success'] = 'Product Changed Successfully';
		}
		catch(PDOException $e){
			$_SESSION['error'] = $e->getMessage();
		}
		
		$pdo->close();
	}
	else{
		$_SESSION['error'] = 'Fill up form first';
	}

	header('location: sales.php');

?>