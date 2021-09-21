<?php
	include 'includes/session.php';
	include 'includes/slugify.php';

	if(isset($_POST['edit'])){
		$id = $_POST['id'];
		$order_status = $_POST['order_status'];
                $status_comment = $_POST['status_comment'];
                $product_name = $_POST['product_name'];
                $email = $_POST['email'];
                $dealer_name = $_POST['dealer_name'];
                $order_id = $_POST['order_id'];
                $product_code = $_POST['product_code'];
                $product_code = $_POST['product_code'];
		$photo = $_FILES['status_doc']['name'];
                
		$conn = $pdo->open();
                
                if(!empty($photo)){
                    $ext = pathinfo($photo, PATHINFO_EXTENSION);
                    $new_filename = $photo;
                    move_uploaded_file($_FILES['status_doc']['tmp_name'], '../images/'.$new_filename);
                    $filename = $new_filename;	
                }else{
                        $filename = '';
                }
                
                
                
                
                function orderReceivedMail($dealer_name, $product_name, $order_status, $status_comment, $order_id, $product_code)
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
                Your Order Status has been updated!<br/><br/>

                Please find the details of your order below:<br/><br/>';

                                                                        $mail_body .= '<table class="tbl_orders"><tr>'
                                                                                . '<th class="td_orders">Order ID</th>'
                                                                                . '<th class="td_orders">Product Code</th>'
                                                                                . '<th class="td_orders">Product Name</th>'
                                                                                . '<th class="td_orders">Order Status</th><th class="td_orders">Comment</th></tr>';
                                                                        $mail_body .= '<tr><td class="td_orders">'.$order_id.'</td><td class="td_orders">'.$product_code.'</td><td class="td_orders">'.$product_name.'</td><td class="td_orders">'.$order_status.'</td><td class="td_orders">'.$status_comment.'</td></tr>';
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
                    
                        if($order_status == 'Product Dispatched'){
                            $stmt = $conn->prepare("UPDATE details SET status=:status,tracking_id=:tracking_id,bill_document=:bill_document WHERE id=:id");
                            $stmt->execute(['status'=>$order_status,'tracking_id'=>$status_comment,'bill_document'=>$filename, 'id'=>$id]);
                        
                        }else if($order_status == 'Delivery Confirmed'){
                            $stmt = $conn->prepare("UPDATE details SET status=:status,order_confirmation_doc=:order_confirmation WHERE id=:id");
                            $stmt->execute(['status'=>$order_status,'order_confirmation'=>$filename, 'id'=>$id]);
                        }
                        else{
			$stmt = $conn->prepare("UPDATE details SET status=:status WHERE id=:id");
			$stmt->execute(['status'=>$order_status, 'id'=>$id]);
                        
                        
                        }
                        
                        $stmt = $conn->prepare("insert into order_status_details(details_id,order_status,status_comment,status_doc)VALUES(:details_id,:order_status,:status_comment,:status_doc)");
			$stmt->execute(['details_id'=>$id, 'order_status'=>$order_status , 'status_comment'=>$status_comment , 'status_doc'=>$filename]);
 
                        
                        $mail_body = orderReceivedMail($dealer_name, $product_name, $order_status, $status_comment, $order_id, $product_code);
                        //echo $mail_body; die;
                        sendEmail($email, "Continental CRD4 | Delivery Status", $mail_body, '');
                        
			$_SESSION['success'] = 'Order Statues Updated Successfully';
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