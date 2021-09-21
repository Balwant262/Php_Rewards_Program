<?php include 'includes/session.php'; ?>
<?php
	if(!isset($_SESSION['user'])){
		header('location: login.php');
	}
?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">

	<?php include 'includes/navbar.php'; ?>
	 
	  <div class="content-wrapper">
	    <div class="container">

	      <!-- Main content -->
	      <section class="content">
	        <div class="row">
	        	<div class="col-sm-12">
	        		<?php
	        			if(isset($_SESSION['error'])){
	        				echo "
	        					<div class='callout callout-danger'>
	        						".$_SESSION['error']."
	        					</div>
	        				";
	        				unset($_SESSION['error']);
	        			}

	        			if(isset($_SESSION['success'])){
	        				echo "
	        					<div class='callout callout-success'>
	        						".$_SESSION['success']."
	        					</div>
	        				";
	        				unset($_SESSION['success']);
	        			}
	        		?>
	        		<div class="box box-solid">
	        			<div class="box-body">
	        				<div class="col-sm-3">
	        					<img src="<?php echo (!empty($user['photo'])) ? 'images/'.$user['photo'] : 'images/profile.jpg'; ?>" width="100%">
	        				</div>
	        				<div class="col-sm-9">
	        					<div class="row">
	        						<div class="col-sm-12">
	        							<table>
	        								<tr>
	        									<td colspan="3">
	        										<span class="pull-right">
			        									<a href="#edit" class="btn btn-primary btn-flat btn-sm" data-toggle="modal"><i class="fa fa-edit"></i> Edit</a>
			        								</span>
	        									</td>
	        								</tr>
	        								<tr>
	        									<th>Dealer Code: </th>
	        									<td>&nbsp;&nbsp;</td>
	        									<td><?php echo $user['dealer_code']; ?></td>
	        								</tr>
	        								<tr>
	        									<th>Dealer Name: </th>
	        									<td>&nbsp;&nbsp;</td>
	        									<td><?php echo $user['dealer_name']; ?></td>
	        								</tr>
	        								<tr>
	        									<th>Name: </th>
	        									<td>&nbsp;&nbsp;</td>
	        									<td>
		        									<?php echo $user['firstname'].' '.$user['lastname']; ?>
		        								</td>
	        								</tr>
	        								<tr>
	        									<th>Email: </th>
	        									<td>&nbsp;&nbsp;</td>
	        									<td><?php echo $user['email']; ?></td>
	        								</tr>
	        								<tr>
	        									<th>Contact No.: </th>
	        									<td>&nbsp;&nbsp;</td>
	        									<td><?php echo $user['contact_info']; ?></td>
	        								</tr>
	        								<tr>
	        									<th>Address: </th>
	        									<td>&nbsp;&nbsp;</td>
	        									<td><?php echo $user['address']; ?></td>
	        								</tr>
	        								<tr>
	        									<th>Member Since: </th>
	        									<td>&nbsp;&nbsp;</td>
	        									<td><?php echo date('M d, Y', strtotime($user['created_on'])); ?></td>
	        								</tr>
	        							</table>
	        						</div>
	        					</div>
	        				</div>
	        			</div>
	        		</div>
	        		<div class="box box-solid">
	        			<div class="box-header with-border">
	        				<h4 class="box-title"><i class="fa fa-calendar"></i> <b>Order History</b></h4>
	        			</div>
	        			<div class="box-body">
	        		<table class="table table-bordered" id="example1">
	        					<thead>
	        						<th class="hidden"></th>
	        						<th>Date</th>
                                                                <th>Order ID</th>
                                                                <th>Dealer Name</th>
                                                                <th>Product Name</th>
                                                                <th>Points</th>
                                                                <th>Quantity</th>
                                                                <th>Total Points</th>
                                                                 
                                                                <th>Bill</th>
                                                                <th>Tracking/ID</th>
                                                                <th>Order Status</th>
                                                                <th>Confirm Order</th>
	        					</thead>
	        					<tbody>
	        					<?php
                    $conn = $pdo->open();

                    try
                    {
                      
                        $stmt = $conn->prepare("SELECT details.*,products.name,products.model_no,products.price,sales.delivery_address,users.dealer_name,users.id user_id,users.email FROM details LEFT JOIN products ON products.id=details.product_id LEFT JOIN sales ON sales.id=details.sales_id LEFT JOIN users ON users.id=sales.user_id WHERE sales.user_id=".$user['id']);
                        $stmt->execute();
                        $total = 0;
                        foreach($stmt as $details)
                        {
                          $subtotal = $details['price']*$details['quantity'];
                          
                          $total += $subtotal;
                          
                        if($details['status'] == 'Delivery Confirmed'){
                              $bill = (!empty($details['bill_document'])) ? '<a href=../images/'.$details['bill_document'].'>Download</a>' : '';
                              
                              $btn = "<button type='button' class='btn btn-success btn-sm btn-flat conformorder' 
                            data-user_id='".$details['user_id']."' data-points_c='".$details['price']."' "
                                . "data-order_id='".$details['order_id']."' data-name='".$details['name']."' "
                                . "data-dealer_name='".$user['dealer_name']."' data-code='".$user['dealer_code']."' data-id='".$details['id']."'>
                                    <i class='fa fa-check'></i> Delivery Confirmation</button>";
                              
                          }  
                          
                          else{
                              $bill = '';
                              $btn= '';
                          }
                        
                        echo "
                          <tr>
                            <td class='hidden'></td>
                            <td>".date('M d, Y', strtotime($details['created']))."</td>
                            <td>".$details['order_id']."</td>
                            <td>".$details['dealer_name']."</td>
                            <td>".$details['name']."</td>
                                
                            <td>".$details['price']."</td>
                                <td>".$details['quantity']."</td>
                            
                            <td>".$details['price']*$details['quantity']."</td>
                           
                            <td>".$bill."</td>
                            <td>".$details['tracking_id']."</td>
                            <td>".$details['status']."</td>
                            <td>".$btn."</td></tr>";
                        
                        
                      }
                      echo "
				<tr>
					<td colspan='6' align='right'><b>Total</b></td>
					<td><b>".$total."</b></td>
				<tr>
			";
                    }
                    catch(PDOException $e){
                      echo $e->getMessage();
                    }

                    $pdo->close();
                  ?>
	        					</tbody>
	        				</table>		
	        			</div>
	        		</div>
                            
                            
                            <div class="box box-solid">
	        			<div class="box-header with-border">
	        				<h4 class="box-title"><i class="fa fa-info-circle"></i> <b>Please note the status descriptions as below:</b></h4>
                                                
	        			</div>
                                <div class="box-body">
                                    <table class="table table-bordered" id="example1">
	        			<thead>
                                            <tr>
                                                <th style="width: 20%">Status</th>
	        			    <th>Description</th>
                                            </tr>
                                            </thead>
	        			
                                            <tr><td>Pending for Confirmation</td><td>Received Order is sent for Approval</td></tr>
                                            <tr><td>Order Confirmed</td><td>Order Verified and Approved by Continental</td></tr>
                                            <tr><td>Product Dispatched</td><td>Product Dispatched</td></tr>
                                            <tr><td>Product Delivered</td><td>Delivery Confirmed by Courier Service Company</td></tr>
                                            <tr><td>Order Canceled</td><td>Order canceled by End User</td></tr>
                                            <tr><td>Delivery Confirmed</td><td>Delivery confirmation by End User</td></tr>
                                            
                                        </table>	
                                </div>
                                </div>
                            
	        	</div>
	        	<!--<div class="col-sm-3">
	        		<?php include 'includes/sidebar.php'; ?>
	        	</div>-->
	        </div>
	      </section>
	     
	    </div>
	  </div>
  
  	<?php include 'includes/footer.php'; ?>
  	<?php include 'includes/profile_modal.php'; ?>
    
</div>

<?php include 'includes/scripts.php'; ?>
<script>
$(function(){
        
$(document).on('click', '.conformorder', function(e){
    e.preventDefault();
    $('#conformorder').modal('show');
    var id = $(this).data('id');
    var dealer_name = $(this).data('dealer_name');
    var code = $(this).data('code');
    var order_id = $(this).data('order_id');
    var name = $(this).data('name');
    var points_c = $(this).data('points_c');
    var user_id = $(this).data('user_id');

    $('.pname_c').text(order_id);
    $('.name_c').text(name);
    $('.orderid').val(id);
    $('.orderid_dname').val(dealer_name);
    $('.orderid_dcode').val(code);
    $('.order_id_txt').val(order_id);
    $('.order_product_name').val(name);

  });     

	$("#transaction").on("hidden.bs.modal", function () {
	    $('.prepend_items').remove();
	});
});
</script>
</body>
</html>