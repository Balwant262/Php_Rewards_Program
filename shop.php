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
	        		<div class="callout" id="callout" style="display:none">
	        			<button type="button" class="close"><span aria-hidden="true">&times;</span></button>
	        			<span class="message"></span>
	        		</div>
		            <h1 class="page-header">All Products</h1>
		       		<?php
		       			
		       			$conn = $pdo->open();

		       			try{
		       			 	$inc = 4;	
						    $stmt = $conn->prepare("SELECT * FROM products where is_active=1 order by price");
						    $stmt->execute(['1' => 1]);
						    foreach ($stmt as $row) {
						    	$image = (!empty($row['photo'])) ? $row['photo'] : 'images/noimage.jpg';
						    	$inc = ($inc == 4) ? 1 : $inc + 1;
	       						if($inc == 1) echo "<div class='row'>";
	       						echo "
	       							<div class='col-sm-3'>
	       								<div class='box box-solid'>
		       								<div class='box-body prod-body'>
		       									<img src='".$image."' width='100%' height='230px' class='thumbnail'>
		       									<h5><a href='product.php?product=".$row['slug']."'>".$row['name']."</a></h5>
		       								</div>
		       								<div class='box-footer'>
		       									<b>CRD Points: ".number_format($row['price'], 0)."</b>
		       									<form class='form-inline productForm' id='productForm_".$row['id']."'>
							            			<div class='form-group'>
								            			<div class='input-group col-sm-5'>
								            				
								            				<span class='input-group-btn'>
								            					<button type='button' id='minus_".$row['id']."' class='btn btn-default btn-flat btn-sm btn-minusquant'><i class='fa fa-minus'></i></button>
								            				</span>
												          	<input type='text' name='quantity' id='quantity_".$row['id']."' class='form-control input-sm btn-quant' value='1'>
												            <span class='input-group-btn'>
												                <button type='button' id='add_".$row['id']."' class='btn btn-default btn-flat btn-sm btn-addquant'><i class='fa fa-plus'></i>
												                </button>
												            </span>
												            <input type='hidden' value='".$row['id']."' name='id'>
												        </div>
								            			<button type='submit' class='btn btn-primary btn-sm btn-flat'><i class='fa fa-shopping-cart'></i> Add to Cart</button>
								            		</div>
						            		</form>
		       								</div>
	       								</div>
	       							</div>
	       						";
	       						if($inc == 4) echo "</div>";
						    }
						    if($inc == 1) echo "<div class='col-sm-3'></div><div class='col-sm-3'></div><div class='col-sm-3'></div></div>"; 
							if($inc == 2) echo "<div class='col-sm-3'></div><div class='col-sm-3'></div></div>";
							if($inc == 3) echo "<div class='col-sm-3'></div></div>";
						}
						catch(PDOException $e){
							echo "There is some problem in connection: " . $e->getMessage();
						}

						$pdo->close();

		       		?> 
	        	</div>
	        	<!--<div class="col-sm-3">
	        		<?php include 'includes/sidebar.php'; ?>
	        	</div>-->
	        </div>
	      </section>
	     
	    </div>
	  </div>
  
  	<?php include 'includes/footer.php'; ?>
</div>

<?php include 'includes/scripts.php'; ?>
<script>
$(function(){
	$('.btn-addquant').click(function(e){
		e.preventDefault();
		var btn_id = $(this).attr('id');
		var btn = btn_id.split('_');
		var i = btn[1];
		//alert(i);
		var quantity = $('#quantity_'+i).val();
		quantity++;
		$('#quantity_'+i).val(quantity);
	});
	$('.btn-minusquant').click(function(e){
		e.preventDefault();
		var btn_id = $(this).attr('id');
		var btn = btn_id.split('_');
		var i = btn[1];
		var quantity = $('#quantity_'+i).val();
		if(quantity > 1){
			quantity--;
		}
		$('#quantity_'+i).val(quantity);
	});

});
</script>
</body>
</html>