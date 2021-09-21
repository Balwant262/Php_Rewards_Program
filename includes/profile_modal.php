<!-- Transaction History -->
<div class="modal fade" id="transaction">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><b>Order Details</b></h4>
            </div>
            <div class="modal-body">
              <p>
                Date: <span id="date"></span>
                <span class="pull-right">Transaction#: <span id="transid"></span></span> 
              </p>
              <table class="table table-bordered">
                <thead>
                  <th>Product</th>
                  <th>Points</th>
                  <th>Quantity</th>
                  <th>Subtotal</th>
                  <th>Status</th>
                </thead>
                <tbody id="detail">
                  <tr>
                    <td colspan="4" align="right"><b>Total</b></td>
                    <td><span id="total"></span></td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="orderstatusdetails">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><b>Order Status Details</b></h4>
            </div>
            <div class="modal-body">
             
              <table class="table table-bordered">
                <thead>
                  <th>Sr.No</th>
                  <th>Date</th>
                  <th>Status</th>
                  <th>Comment</th>
                  <th>File</th>
                </thead>
                <tbody id="detail_status">
                  
                </tbody>
              </table>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="orderstatus">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><b>Order Status Details</b></h4>
            </div>
            <form class="form-horizontal" method="POST" action="order_status_update.php" enctype="multipart/form-data">
            <div class="modal-body">
              
                <input type="hidden" class="orderid" name="id">
                <input type="hidden" class="name" name="product_name">
                <input type="hidden" class="email" name="email">
                <input type="hidden" class="dealer_name" name="dealer_name">
                <input type="hidden" class="order_id" name="order_id">
                <input type="hidden" class="product_code" name="product_code">
                
                <div class="row">
                    <div class="col-sm-3"><label>Select Status</label></div>
                    <div class="col-sm-9">
                        <select name="order_status" id="order_status" class="form-control" required>
                        <option value="">--Select Status--</option>
                        <option value="Pending for Confirmation">Pending for Confirmation</option>
                        <option value="Order Confirmed">Order Confirmed</option>
                        <option value="Product Dispatched">Product Dispatched</option>
                        <option value="Product Delivered">Product Delivered</option>
                        <option value="Order Cancelled">Order Cancelled</option>
                        <option value="Delivery Confirmed">Delivery Confirmed</option>
                        
                    </select>
                        </div>
                </div>
                <br>
                
                <div class="row">
                    <div class="col-sm-3 "><label id="lbl_comment">Enter Comment</label></div>
                    <div class="col-sm-9">
                        <input type="text" name="status_comment" class="form-control" required>
                        </div>
                </div>
                <br>
                
                <div class="row">
                    <div class="col-sm-3"><label id="lbl_doc">Select Document</label></div>
                    <div class="col-sm-9">
                        <input type="file" name="status_doc" class="form-control" id="status_doc">
                        </div>
                </div>
                
                
                
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
              <button type="submit" class="btn btn-info btn-flat" name="edit"><i class="fa fa-plus"></i> Save</button>
              
            </div>
                </form>
        </div>
    </div>
</div>

<!-- Edit Profile -->
<div class="modal fade" id="edit">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><b>Update Account</b></h4>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" method="POST" action="profile_edit.php" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="firstname" class="col-sm-3 control-label">First Name</label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="firstname" name="firstname" value="<?php echo $user['firstname']; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="lastname" class="col-sm-3 control-label">Last Name</label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="lastname" name="lastname" value="<?php echo $user['lastname']; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="email" class="col-sm-3 control-label">Email</label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="password" class="col-sm-3 control-label">Password</label>

                    <div class="col-sm-9">
                      <input type="password" class="form-control" id="password" name="password" value="<?php echo $user['password']; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="contact" class="col-sm-3 control-label">Contact Info</label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="contact" name="contact" value="<?php echo $user['contact_info']; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="address" class="col-sm-3 control-label">Address</label>

                    <div class="col-sm-9">
                      <textarea class="form-control" id="address" name="address" readonly><?php echo $user['address']; ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="photo" class="col-sm-3 control-label">Photo</label>

                    <div class="col-sm-9">
                      <input type="file" id="photo" name="photo">
                    </div>
                </div>
                <hr>
                
                <div class="form-group">
                    <label for="curr_password" class="col-sm-3 control-label">Current Password</label>

                    <div class="col-sm-9">
                      <input type="password" class="form-control" id="curr_password" name="curr_password" placeholder="input current password to save changes" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
              <button type="submit" class="btn btn-success btn-flat" name="edit"><i class="fa fa-check-square-o"></i> Update</button>
              </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="cancelorder">
    <div class="modal-dialog">
        <form class="form-horizontal" method="POST" action="cancel_order.php">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><b>Cancel...</b></h4>
            </div>
            <div class="modal-body">
              
                <input type="hidden" class="orderid_c" name="id">
                <input type="hidden" class="points_c" name="points">
                <input type="hidden" class="user_id_c" name="user_id">
                
                <div class="text-center">
                    <p>CANCEL ORDER ?</p>
                    <h2 class="bold pname_c"></h2>
                    <h5 class="bold name_c"></h5>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
              <button type="submit" class="btn btn-danger btn-flat" name="edit"><i class="fa fa-trash"></i> Cancel</button>
              
            </div>
        </div>
        </form>
    </div>
</div>




<div class="modal fade" id="conformorder">
    <div class="modal-dialog">
        <form class="form-horizontal" method="POST" action="conform_order.php">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><b>Confirmation...</b></h4>
            </div>
            <div class="modal-body">
                <input type="hidden" class="orderid" name="id">
                <input type="hidden" class="orderid_dname" name="orderid_dname">
                <input type="hidden" class="orderid_dcode" name="orderid_dcode">
                <input type="hidden" class="order_id_txt" name="order_id_txt">
                <input type="hidden" class="order_product_name" name="order_product_name">
                
                <div class="text-center">
                    <p>CONFIRMATION ORDER ?</p>
                    <h2 class="bold pname_c"></h2>
                    <h5 class="bold name_c"></h5>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
              <button type="submit" class="btn btn-success btn-flat" name="edit"><i class="fa fa-check"></i> Yes</button>
              
            </div>
        </div>
        </form>
    </div>
</div>



<div class="modal fade" id="changeproduct">
    <div class="modal-dialog">
        <form class="form-horizontal" method="POST" action="change_product_order.php">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><b>Change Product</b></h4>
            </div>
            <div class="modal-body">
              
                <input type="hidden" class="orderid_c" name="id">
                <input type="hidden" class="points_c" name="points">
                <input type="hidden" class="user_id_c" name="user_id">
                <input type="hidden" class="user_email_c" name="user_email">
                <input type="hidden" class="user_name_c" name="user_name">
                <input type="hidden" class="name_c_h" name="old_product_name">
                <input type="hidden" class="new_product_name" name="new_product_name">
                <input type="hidden" class="pname_c_h" name="order_txt_id">
                
                <div class="text-center">
                    <p>Change Product ?</p>
                    <h2 class="bold pname_c"></h2>
                    <h5 class="bold name_c"></h5>
                    
                    <h5 class="bold">Select New Product</h5>
                    
                    <select class="form-control select2" id="product_id" name="product_id" style="width: 100%;" required>
                      <option value="" selected>- Select -</option>
                    </select>
                    
                    
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
              <button type="submit" class="btn btn-warning btn-flat" name="edit"><i class="fa fa-trash"></i> Change Product</button>
              
            </div>
        </div>
        </form>
    </div>
</div>