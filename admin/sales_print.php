<?php
	include 'includes/session.php';

	function generateRow($from, $to, $conn){
		$contents = '';
	 	$stmt = $conn->prepare("SELECT details.*, users.id AS user_id, users.dealer_name, products.model_no, products.name `product_name`, products.price, sales.sales_date FROM details LEFT JOIN products ON products.id=details.product_id LEFT JOIN sales ON details.sales_id = sales.id LEFT JOIN users ON sales.user_id = users.id");
		$stmt->execute(['1'=>'1']);
		
		$total = 0;
		foreach($stmt as $row)
		{
			$amount = $row['price']*$row['quantity'];
			$total += $amount;
			
			$contents .= '
			<tr>
				<td>'.date('M d, Y', strtotime($row['sales_date'])).'</td>
				<td>ORDER'.$row['sales_id'].'</td>
				<td>'.$row['dealer_name'].'</td>
				<td>'.$row['model_no'].'</td>
				<td>'.$row['product_name'].'</td>
				<td>'.$row['quantity'].'</td>
				<td>'.$amount.'</td>
			</tr>
			';
		}

		$contents .= '
			<tr>
				<td colspan="6" align="right"><b>Total</b></td>
				<td align="right"><b>'.$total.'</b></td>
			</tr>
		';
		return $contents;
	}

	if(isset($_POST['print'])){
		$ex = explode(' - ', $_POST['date_range']);
		$from = date('Y-m-d', strtotime($ex[0]));
		$to = date('Y-m-d', strtotime($ex[1]));
		$from_title = date('M d, Y', strtotime($ex[0]));
		$to_title = date('M d, Y', strtotime($ex[1]));

		$conn = $pdo->open();

		require_once('../tcpdf/tcpdf.php');  
	    $pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);  
	    $pdf->SetCreator(PDF_CREATOR);  
	    $pdf->SetTitle('Sales Report: '.$from_title.' - '.$to_title);  
	    $pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);  
	    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));  
	    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));  
	    $pdf->SetDefaultMonospacedFont('helvetica');  
	    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);  
	    $pdf->SetMargins(PDF_MARGIN_LEFT, '10', PDF_MARGIN_RIGHT);  
	    $pdf->setPrintHeader(false);  
	    $pdf->setPrintFooter(false);  
	    $pdf->SetAutoPageBreak(TRUE, 10);  
	    $pdf->SetFont('helvetica', '', 11);  
	    $pdf->AddPage();  
	    $content = '';  
	    $content .= '
	      	<h2 align="center">Continental Rewards Dhamaka</h2>
	      	<h4 align="center">SALES REPORT</h4>
	      	<h4 align="center">'.$from_title." - ".$to_title.'</h4>
	      	<table border="1" cellspacing="0" cellpadding="3">  
	           <tr>  
	           		<th><b>Date</b></th>
	           		<th><b>Order ID</b></th>
	                <th><b>Dealer Name</b></th>
	                <th><b>Model No.</b></th>
					<th><b>Product Name</b></th>
					<th><b>Quantity</b></th>
					<th><b>CRD Points</b></th>
	           </tr>  
	      ';  
	    $content .= generateRow($from, $to, $conn);  
	    $content .= '</table>';  
	    $pdf->writeHTML($content);  
	    $pdf->Output('orders.pdf', 'I');

	    $pdo->close();

	}
	else{
		$_SESSION['error'] = 'Need date range to provide sales print';
		header('location: sales.php');
	}
?>