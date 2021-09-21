<?php
include 'includes/session.php';
require_once('sxlsx/swift/lib/swift_required.php');

$conn = $pdo->open();


function loginDetailsToDealer($email_id, $password)
{
	
	$mail_body = '<!DOCTYPE html PUBLIC>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>:: CONTINENTAL ::</title>
        <style>
            body { margin:0px; padding:0px;}
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
                                <br/><br/>Dear Partner,<br/><br/>
We welcome you to the Continental Rewards Dhamaka!<br/><br/>

Continental Rewards Dhamaka is an exclusive rewards & engagement program designed especially for Dealers like you!<br/><br/>

The Program is loaded with innovative & rewarding offers which you will experience with lots of enjoyment throughout with
Continental Rewards Dhamaka<br/><br/>

Unlock these great benefits today by visiting our new Continental Rewards Dhamaka website: -<br/><br/>

Your Credentials<br/>
URL: http://crd4.thetrinket.in/<br/>
E-Mail ID : '.$email_id.'<br/>
Password : '.$password.'<br/><br/>

Wishing you a joyous journey ahead!<br/><br/>

Best Regards,<br/>
<strong>Continental Rewards Dhamaka</strong><br/>
<strong>Continental India Pvt Ltd</strong><br/><br/>
                            </td>
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


function sendSwiftMail($email_id,$subject,$body, $file_path = NULL,$cc=NULL,$bcc=NULL)
{
	if(!$bcc)
	{
		$bcc = array('varsha@thetrinket.in','vinod@novuslogic.co.in');
	}

	$transport = Swift_MailTransport::newInstance();
	$mailer = Swift_Mailer::newInstance($transport);
	$message = Swift_Message::newInstance()
	    ->setFrom(array('continentalrewards@thetrinket.in' => 'Continental Rewards'))
	    ->setTo(array($email_id))
	    ->setBCC($bcc)
	    ->setEncoder(Swift_Encoding::get7BitEncoding())
	    ->setSubject($subject)
	    ->setBody($body, 'text/html')
	    ->addPart(strip_tags($body), 'text/plain')
	    ->attach(Swift_Attachment::fromPath($file_path))
	;
	$mailer->send($message);
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

$file = "http://crd4.thetrinket.in/sxlsx/Reward_Redemption_Process_Flow.pdf";
$dealer_code = $_GET['dealer_code'];
try
{
	//$stmt = $conn->prepare("SELECT * FROM users");
	//$stmt->execute(['1'=>'1']);
	$stmt = $conn->prepare("SELECT * FROM users where dealer_code= :dealer_code");
	$stmt->execute(['dealer_code'=>$dealer_code]);
	foreach($stmt as $row)
	{
		$user_email = $row['email'];
		$user_pass = randomPassword();
		$enc_password = password_hash($user_pass, PASSWORD_DEFAULT);
		$stmt = $conn->prepare("UPDATE users SET password=:password where dealer_code= :dealer_code");
		$stmt->execute(['password'=>$enc_password, 'dealer_code'=>$dealer_code]);
		$body = loginDetailsToDealer($user_email, $user_pass);
		sendSwiftMail($user_email,"Continental Reward Dhamaka",$body,$file);
		echo "Mail Sent to ".$user_email."<br>";
	}
	//$output['message'] = 'Updated';
}
catch(PDOException $e)
{
	$output['message'] = $e->getMessage();
}


?>