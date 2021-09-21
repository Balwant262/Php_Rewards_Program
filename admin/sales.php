<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Order History
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Orders</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        
        <?php
        if(isset($_SESSION['error'])){
          echo "
            <div class='alert alert-danger alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-warning'></i> Error!</h4>
              ".$_SESSION['error']."
            </div>
          ";
          unset($_SESSION['error']);
        }
        if(isset($_SESSION['success'])){
          echo "
            <div class='alert alert-success alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-check'></i> Success!</h4>
              ".$_SESSION['success']."
            </div>
          ";
          unset($_SESSION['success']);
        }
      ?>
        
        
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">
              <div class="pull-right">
                <form method="POST" class="form-inline" action="sales_print.php">
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" class="form-control pull-right col-sm-8" id="reservation" name="date_range">
                  </div>
                  <button type="submit" class="btn btn-success btn-sm btn-flat" name="print"><span class="glyphicon glyphicon-print"></span> Print</button>
                </form>
              </div>
            </div>
            <div class="box-body">
              <table id="example1" class="table table-bordered">
                <thead>
                  <th class="hidden"></th>
                  <th>Date</th>
                  <th>Order ID</th>
                  <th>Dealer Name</th>
                  <th>Product Name</th>
                  <th>Quantity</th>
                  <th>Points</th>
                  <th>Conformation</th>
                  <th>Bill</th>
                  <th>Tracking/ID</th>
                  <th>Order Status</th>
                  <th>Status</th>
                  <th>Action</th>
                </thead>
                <tbody>
                  <?php
                    $conn = $pdo->open();

                    try
                    {
                      
                        $stmt = $conn->prepare("SELECT details.*,products.name,products.model_no,products.price,sales.delivery_address,users.dealer_name,users.id user_id,users.email,users.firstname FROM details LEFT JOIN products ON products.id=details.product_id LEFT JOIN sales ON sales.id=details.sales_id LEFT JOIN users ON users.id=sales.user_id WHERE 1");
                        $stmt->execute();
                        $total = 0;
                        foreach($stmt as $details)
                        {
                          if($details['status'] != "Order Cancelled")
                          	$subtotal = $details['price']*$details['quantity'];
                          else
                          	$subtotal = 0;
                          	
                          $total += $subtotal;
                          
                          $path1 = $details['bill_document'];
                          $path=str_replace(" ", '%20', $path1);

                          $path2 = $details['order_confirmation'];
                          $path2=str_replace(" ", '%20', $path2);
                          
                          $bill = (!empty($details['bill_document'])) ? '<a href=../images/'.$path.'>Download</a>' : '';
                          $conform = (!empty($details['order_confirmation'])) ? '<a href=../images/'.$path2.'>Download</a>' : '';
                        
                       
                        
                        echo "
                          <tr>
                            <td class='hidden'></td>
                            <td>".date('M d, Y', strtotime($details['created']))."</td>
                            <td>".$details['order_id']."</td>
                            <td>".$details['dealer_name']."</td>
                            <td>".$details['name']."  </td>
                            <td>".$details['quantity']."</td>
                            <td>".$details['price']."</td>
                            <td>".$conform."</td>
                            <td>".$bill."</td>
                            <td>".$details['tracking_id']."</td>
                            <td>".$details['status']."<button type='button' class='btn btn-success btn-sm btn-flat showdetails' data-id=".$details['id']."><i class='fa fa-search'></i> Show All</button>
                                                            
                            </td>
                            <td><button type='button' class='btn btn-info btn-sm btn-flat orderstatus' data-status='".$details['status']."'  data-product_code='".$details['model_no']."' data-order_id='".$details['order_id']."' data-name='".$details['name']."' data-email='".$details['email']."' data-dealer_name='".$details['dealer_name']."' data-id=".$details['id']."><i class='fa fa-plus'></i> Add</button>
                                
                            </td>
                            <td>
                                <button type='button' class='btn btn-warning btn-sm btn-flat changeproduct' data-user_id='".$details['user_id']."' data-user_email='".$details['email']."' data-dealer_name='".$details['firstname']."' data-points_c='".$details['price']."' data-order_id='".$details['order_id']."' data-name='".$details['name']."' data-id='".$details['id']."'><i class='fa fa-pencil'></i>Change Product</button>                            
                                <button type='button' class='btn btn-danger btn-sm btn-flat cancelorder' data-user_id='".$details['user_id']."' data-points_c='".$details['price']."' data-order_id='".$details['order_id']."' data-name='".$details['name']."' data-id='".$details['id']."'><i class='fa fa-times'></i> Cancel</button></td>
                          </tr>
                        ";
                      }
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
        </div>
      </div>
    </section>
     
  </div>
  	<?php include 'includes/footer.php'; ?>
    <?php include '../includes/profile_modal.php'; ?>

</div>
<!-- ./wrapper -->

<?php include 'includes/scripts.php'; ?>
<!-- Date Picker -->
<script>
$(function(){
  //Date picker
  $('#datepicker_add').datepicker({
    autoclose: true,
    format: 'yyyy-mm-dd'
  })
  $('#datepicker_edit').datepicker({
    autoclose: true,
    format: 'yyyy-mm-dd'
  })

  //Timepicker
  $('.timepicker').timepicker({
    showInputs: false
  })

  //Date range picker
  $('#reservation').daterangepicker()
  //Date range picker with time picker
  $('#reservationtime').daterangepicker({ timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A' })
  //Date range as a button
  $('#daterange-btn').daterangepicker(
    {
      ranges   : {
        'Today'       : [moment(), moment()],
        'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
        'This Month'  : [moment().startOf('month'), moment().endOf('month')],
        'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
      },
      startDate: moment().subtract(29, 'days'),
      endDate  : moment()
    },
    function (start, end) {
      $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
    }
  )
  
});
</script>
<script>
$(function(){
  $(document).on('click', '.transact', function(e){
    e.preventDefault();
    $('#transaction').modal('show');
    var id = $(this).data('id');
    $.ajax({
      type: 'POST',
      url: 'transact.php',
      data: {id:id},
      dataType: 'json',
      success:function(response){
        $('#date').html(response.date);
        $('#transid').html(response.transaction);
        $('#detail').prepend(response.list);
        $('#total').html(response.total);
      }
    });
  });
  
    $(document).on('click', '.showdetails', function(e){
    e.preventDefault();
    $('#detail_status tr').remove();
    $('#orderstatusdetails').modal('show');
    var id = $(this).data('id');
    $.ajax({
      type: 'POST',
      url: 'transstatus.php',
      data: {id:id},
      dataType: 'json',
      success:function(response){
        
        $('#detail_status').prepend(response.list);
      }
    });
  });
  
  
  
  $(document).on('click', '.orderstatus', function(e){
    e.preventDefault();
    $('#orderstatus').modal('show');
    var id = $(this).data('id');
    var dealer_name = $(this).data('dealer_name');
    var name = $(this).data('name');
    var email = $(this).data('email');
    var order_id = $(this).data('order_id');
    var product_code = $(this).data('product_code');
    var status = $(this).data('status');
    $('#order_status').val(status);
    
    $('.orderid').val(id);
    $('.name').val(name);
    $('.email').val(email);
    $('.dealer_name').val(dealer_name);
    $('.order_id').val(order_id);
    $('.product_code').val(product_code);
    
  });
  
  
    $(document).on('click', '.cancelorder', function(e){
    e.preventDefault();
    $('#cancelorder').modal('show');
    var id = $(this).data('id');
    var order_id = $(this).data('order_id');
    var name = $(this).data('name');
    var points_c = $(this).data('points_c');
    var user_id = $(this).data('user_id');
    var user_email = $(this).data('user_email');
    var dealer_name = $(this).data('dealer_name');
    

    $('.pname_c').text(order_id);
    $('.name_c').text(name);
    $('.orderid_c').val(id);
    $('.points_c').val(points_c);
    $('.user_id_c').val(user_id);
   
    $('.user_email_c').val(user_email);
    $('.user_name_c').val(dealer_name);
  });
  
  

$('#order_status').on('change', function (e) {
    var optionSelected = $("option:selected", this);
    var valueSelected = this.value;
    if(valueSelected == 'Product Dispatched'){
        $('#lbl_doc').text("Attach Bill");
        $('#lbl_comment').text("Tracking ID/POD Number");
        $("#status_doc").prop("required", true);
        
    }else{
        $('#lbl_doc').text("Select Document");
        $('#lbl_comment').text("Enter Comment");
        $("#status_doc").prop("required", false);
        
    }
});

$('#product_id').on('change', function (e) {
    
    var valueSelected = $("#product_id option:selected").text();
    $('.new_product_name').val(valueSelected);
});

$(document).on('click', '.changeproduct', function(e){
    e.preventDefault();
    $('#changeproduct').modal('show');
    var id = $(this).data('id');
    var order_id = $(this).data('order_id');
    var name = $(this).data('name');
    var points_c = $(this).data('points_c');
    var user_id = $(this).data('user_id');
    var user_email = $(this).data('user_email');
    var dealer_name = $(this).data('dealer_name');

    $('.pname_c').text(order_id);
    $('.name_c').text(name);
    $('.name_c_h').val(name);
    $('.pname_c_h').val(order_id);
    $('.orderid_c').val(id);
    $('.points_c').val(points_c);
    $('.user_id_c').val(user_id);
    $('.user_email_c').val(user_email);
    $('.user_name_c').val(dealer_name);
    getProducts();
  });

function getProducts(){
  $.ajax({
    type: 'POST',
    url: 'products_all.php',
    dataType: 'json',
    success:function(response){
        
      $('#product_id').append(response);
      
    }
  });
}

  $("#transaction").on("hidden.bs.modal", function () {
      $('.prepend_items').remove();
  });
});
</script>
</body>
</html>
