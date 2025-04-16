<?php 
ob_start();
session_start();
include '../_init.php';
include ("config.php");
// Redirect, If user is not logged in
if (!is_loggedin()) {
  redirect(root_url() . '/index.php?redirect_to=' . url());
}

// Redirect, If User has not Read Permission
if (user_group_id() != 1 && !has_permission('access', 'read_product')) {
	redirect(root_url() . '/'.ADMINDIRNAME.'/dashboard.php');
}

// Set Document Title
$document->setTitle(trans('title_product'));

// Add Script
$document->addScript('../assets/tinymce/tinymce.min.js');
$document->addScript('../assets/itsolution24/angular/controllers/ProductController.js');

// Include Header and Footer
include("header.php"); 
include ("left_sidebar.php"); 

?>
<!-- Content Wrapper Start -->
<div class="content-wrapper" ng-controller="ProductController">
<script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
<script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
  	<!-- Content Header Start -->
	<section class="content-header">
		<h1>
			<?php echo trans('text_products'); ?>
			<small>
				<?php echo store('name'); ?>	
			</small>
		</h1>
		<ol class="breadcrumb">
			<li>
				<a href="dashboard.php">
					<i class="fa fa-dashboard"></i> 
					<?php echo trans('text_dashboard'); ?>
				</a>
			</li>
			<li>
				<?php if (isset($request->get['location']) && $request->get['location']=='trash'): ?>
					<a href="product.php"><?php echo trans('text_products'); ?></a>	
				<?php else: ?>
					<?php echo trans('text_products'); ?>	
				<?php endif; ?>
			</li>
			<?php if (isset($request->get['location']) && $request->get['location']=='trash'): ?>
				<li class="active">
					<?php echo trans('text_trash'); ?>	
				</li>
			<?php endif; ?>
		</ol>
	</section>
  	<!-- Content Header End -->

	<!-- Content Start -->
	<section class="content">

		<?php if(DEMO) : ?>
	    <div class="box">
	      <div class="box-body">
	        <div class="alert alert-info mb-0">
	          <p><span class="fa fa-fw fa-info-circle"></span> <?php echo $demo_text; ?></p>
	        </div>
	      </div>
	    </div>
	    <?php endif; ?>
	    
    	<?php if (user_group_id() == 1 || has_permission('access', 'create_product')) : ?>
	    <div class="box box-info<?php echo create_box_state(); ?>">
	       

	        <?php if (isset($error_message)): ?>
	        	<div class="alert alert-danger">
					<p>
						<span class="fa fa-warning"></span> 
						<?php echo $error_message; ?>
					</p>
	        	</div>
	        <?php elseif (isset($success_message)): ?>
	          <div class="alert alert-success">
				<p>
					<span class="fa fa-check"></span> 
					<?php echo $success_message; ?>
				</p>
	          </div>
	        <?php endif; ?>

	        <!-- Include Product Form -->
	        <?php include('../_inc/template/product_create_form.php'); ?>

	    </div>
	    <?php endif; ?>

	    <div class="row">
		    <form action="product_bulk_action.php" method="post" enctype="multipart/form-data" id="product-list-form">
			    <div class="col-xs-12">
			        <div class="box box-success">
				        <div class="box-header">
				            <h3 class="box-title">
				            	<?php echo sprintf(trans('text_view_all'), trans('text_product')); ?>	
				            </h3>

				            <!--Box Tools End-->
				            <div class="box-tools pull-right">

				               <!-- Filter Product Supplier Wise -->
				               <?php include('../_inc/template/partials/product_filter.php'); ?>

					            <!-- Trash Box -->
				                <div class="btn-group">
					                <a type="button" class="btn btn-danger" href="product.php?location=trash">
					                  	<span class="fa fa-trash"></span> 
					                  	<?php echo trans('button_trash'); ?> 
					                  	<i class="badge badge-warning" id="total-trash">
					                  		<?php echo total_trash_product(); ?>
					                  	</i>
					                </a>
				                </div>

				                <!-- Bulk Action -->
			                	<?php if (user_group_id() == 1 || has_permission('access', 'product_bulk_action')) : ?>
				                <div class="btn-group">
					                <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
					                	<?php echo trans('button_bulk'); ?>
					                    <span class="caret"></span>
					                </button>
					                <ul class="dropdown-menu" role="menu">
					                	<?php if (user_group_id() == 1 || has_permission('access', 'delete_all_product')) : ?>
						                    <li>
						                    	<a id="delete-all" href="#" data-form="#product-list-form" data-loading-text="Deleting...">
						                    		<?php echo trans('button_delete_all'); ?>
						                    	</a>
						                    </li>
						                    <?php if(isset($request->get['location']) && $request->get['location'] == 'trash') : ?>
						                    <li>
						                    	<a id="restore-all" href="#" data-form="#product-list-form" data-datatable="product-product-list" data-loading-text="Restoring...">
						                      		<?php echo trans('button_restore_all'); ?>
						                    	</a>
						                    </li>
					                    <?php endif;?>
					                    <?php endif; ?>
					                 </ul>
				                </div>
					            <?php endif; ?>

				            </div>
				            <!--  Box Tools End-->

				        </div>
						<div class="box-body" style="display:none;" >
							<div class="table-responsive">
								<?php
									$print_columns = '2,3,4,5,6,7';
									if (user_group_id() != 1) {
										if (! has_permission('access', 'show_purchase_price')) {
											$print_columns = str_replace('6,', '', $print_columns);
										}
									}
									$hide_colums = "";
									if (user_group_id() != 1) {
										if (! has_permission('access', 'product_bulk_action')) {
											$hide_colums .= "0,";
										}
										if (! has_permission('access', 'show_purchase_price')) {
											$hide_colums .= "6,";
										}
										if (! has_permission('access', 'read_product')) {
											$hide_colums .= "8,";
										}
										if (! has_permission('access', 'update_product')) {
											$hide_colums .= "9,";
										}
										if (! has_permission('access', 'create_purchase_invoice')) {
											$hide_colums .= "10,";
										}
										if (! has_permission('access', 'print_barcode')) {
											$hide_colums .= "11,";
										}
										if (! has_permission('access', 'delete_product')) {
											$hide_colums .= "12,";
										}
									}
								?>  
								<table id="product-product-list" class="table table-bordered table-striped table-hover" data-hide-colums="<?php echo $hide_colums; ?>" data-print-columns="<?php echo $print_columns;?>">
								    <thead>
								        <tr class="bg-gray">
								            <th class="w-5 product-head text-center">
								            	<input type="checkbox" onclick="$('input[name*=\'select\']').prop('checked', this.checked);">
								            </th>
								            <th class="w-5">
								            	<?php echo sprintf(trans('label_image'),null); ?>
								            </th>
								            <th class="w-10">
								            	<?php echo sprintf(trans('label_pcode'),null); ?>
								            </th>
								            <th class="w-20">
								            	<?php echo sprintf(trans('label_name'),trans('text_product')); ?>
								            </th>
								            <th class="w-15">
								            	<?php echo trans('label_supplier'); ?>
								            </th>
								            <th class="w-10">
								            	<?php echo trans('label_stock'); ?>
								            </th>
								            <th class="w-5">
								            	<?php echo trans('label_purchase_price'); ?>
								            </th>                        
								            <th class="w-5">
								            	<?php echo trans('label_selling_price'); ?>
								            </th>
								            <th class="w-5">
								            	<?php echo trans('label_view'); ?>
								            </th>
								            <th class="w-5">
								            	<?php echo trans('label_edit'); ?>
								            </th>
								            <th class="w-5">
								            	<?php echo trans('label_purchase'); ?>
								            </th>
								            <th class="w-5">
								            	<?php echo trans('label_print_barcode'); ?>
								            </th>
								            <th class="w-5">
								            	<?php echo trans('label_delete'); ?>
								            </th>
								        </tr>
								    </thead>
								    <tfoot>
										<tr class="bg-gray">
											<th class="w-5 product-head text-center">
								            	<input type="checkbox" onclick="$('input[name*=\'select\']').prop('checked', this.checked);">
								            </th>
								            <th class="w-5">
								            	<?php echo sprintf(trans('label_image'),null); ?>
								            </th>
								            <th class="w-10">
								            	<?php echo sprintf(trans('label_pcode'),null); ?>
								            </th>
								            <th class="w-20">
								            	<?php echo sprintf(trans('label_name'),trans('text_product')); ?>
								            </th>
								            <th class="w-15">
								            	<?php echo trans('label_supplier'); ?>
								            </th>
								            <th class="w-10">
								            	<?php echo trans('label_stock'); ?>
								            </th>
								            <th class="w-5">
								            	<?php echo trans('label_purchase_price'); ?>
								            </th>                        
								            <th class="w-5">
								            	<?php echo trans('label_selling_price'); ?>
								            </th>
								            <th class="w-5">
								            	<?php echo trans('label_view'); ?>
								            </th>
								            <th class="w-5">
								            	<?php echo trans('label_edit'); ?>
								            </th>
								            <th class="w-5">
								            	<?php echo trans('label_purchase'); ?>
								            </th>
								            <th class="w-5">
								            	<?php echo trans('label_print_barcode'); ?>
								            </th>
								            <th class="w-5">
								            	<?php echo trans('label_delete'); ?>
								            </th>
										</tr>
								    </tfoot>
								</table>
								</div>
								</div>
									<div class="box-body">
							<div class="table-responsive">
								<br>
								<!-- Button trigger modal -->


								<br>
								<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>Image</th>
                <th>Product ID</th>
                <th>Product Code</th>
                <th>Product Name</th>
                <th>Supplier Name</th>
                <th>Stock</th>
                <th>Purchase Price</th>
                <th>Selling Price</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            
                                         <?php
                                         

                                         
                                         
if(store('name') == "Robbymart- Ola"){
    $name = store('name');
    $ola = "SELECT store_id FROM stores WHERE name = '$name' ";
    $store_id = $link->query($ola);
    if ($store_id->num_rows > 0) {
  // output data of each row
  while($rows = $store_id->fetch_assoc()) {
     $id = $rows['store_id'];
     $_SESSION['storeid'] = $id;
  }
    }   
  }elseif(store('name') == "RobbyMart T-Point"){
        $name = store('name');
    $point = "SELECT store_id FROM stores WHERE name = '$name' ";
    $store_id = $link->query($point);
    if ($store_id->num_rows > 0) {
  // output data of each row
  while($rows = $store_id->fetch_assoc()) {
      $id = $rows['store_id'];
      
      $_SESSION['storeid'] = $id;
  }
    }
        }
else{
            
    $name = store('name');
    $palace = "SELECT store_id FROM stores WHERE name = '$name' ";
    $store_id = $link->query($palace);
    if ($store_id->num_rows > 0) {
  // output data of each row
  while($rows = $store_id->fetch_assoc()) {
      $id = $rows['store_id'];
  }
    }
        }
        
		if (isset($_POST['quantityupdate'])) {
			$pquantity = $_POST['quantity'];
		    $product_id = $_POST['product_ID'];
			
		   $sql = "UPDATE product_to_store SET quantity_in_stock='$pquantity'  WHERE store_id='$id' AND product_id ='$product_id' ";
		   mysqli_query($link, $sql);  
		   echo "<button class='btn btn-success'> Quantity updated successfully </button>";
		} else {
		  echo "Error updating record 2: " . mysqli_error($link);
		}                                    
  if (isset($_POST['ddupdate'])) {
       $purchaseprice = $_POST['purchaseprice'];
       $sellprice = $_POST['sellprice'];
       $product_id = $_POST['product_ID'];
       $sup_id = $_POST['sup_id'];
      
       
      $sql = "UPDATE product_to_store SET sell_price='$sellprice', purchase_price='$purchaseprice', sup_id = '$sup_id'  WHERE store_id='$id' AND product_id ='$product_id' ";
     
if (mysqli_query($link, $sql)) {
    $product_id = $_POST['product_ID'];
     $productname = $_POST['productname'];
     $sqlproduct = "UPDATE products SET p_name='$productname' WHERE p_id ='$product_id' ";
     mysqli_query($link, $sqlproduct);
  echo "<button class='btn btn-success'> Product details updated successfully </button>";
} else {
  echo "Error updating record 1: " . mysqli_error($link);
}
 

 
  }
$sql = "SELECT P.p_id, P.p_name,P.p_code, S.sup_name,P.p_image, P1.quantity_in_stock, S.sup_id, P1.purchase_price, sell_price FROM products as P 
INNER JOIN product_to_store as P1 ON P.p_id = P1.product_id
INNER JOIN suppliers as S ON S.sup_id = P1.sup_id

  WHERE P1.store_id = '$id'
";

$result = $link->query($sql);
 $Totalpurchase = 0;
 $Totalprice = 0;
 $Totalprofit = 0;
 $Totalrprofit = 0;
  $Totalssprofit = 0;
 $Totalsprofit = 0;
if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
     if(empty($row['p_image']))
{ 
$images =  '<img src="http://34.232.46.231/modernpos/admin/icon.png"  height="50px" width="50px">';
}
else
{
$images = '<img src="http://34.232.46.231/modernpos/storage/products/'.$row['p_image'].'"  height="50px" width="50px">';
}
 
   
  echo "<tr>";
   echo "<td>" .$images ."</td>";
    echo "<td>" . $row['p_id'] ."</td>";
    echo "<td>" . $row['p_code']. "</td>";
    echo "<td>" . $row['p_name']. "</td>";
    echo "<td>" . $row['sup_name']. "</td>";
    echo "<td>" . number_format((float)$row['quantity_in_stock'], 2, '.', ''). "</td>";
    echo "<td>" . number_format((float)$row['purchase_price'], 2, '.', ''). "</td>";
    echo "<td>" . number_format((float)$row['sell_price'], 2, '.', ''). "</td>";
    echo "<td>" .'
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter'.$row['p_id'].'">Edit</button>
	
<!-- Modal -->
<div class="modal fade" id="exampleModalCenter'.$row['p_id'].'" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Update Product details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
         <div class="row" style="background-color:#f0f4f4; height:450px;" >
		  <form action="product.php" method="post">
           
            <div class="col-md-6">
			<label>Quantity</label>
			<br>
             <input type="number" name="quantity" required>
             </div>
			  <div class="col-md-6">
             <input type="hidden" name="product_ID" value="'.$row['p_id'].'" required>
             </div>
             <br>
             <br>
            <div class="col-md-12">
          
             <button name="quantityupdate" class="btn btn-primary">
                 Update
             </button>
         </div>
        
 </form>
<br> 
<br>
<br> 
<br>
 <form action="product.php" method="post">
            <div class="col-md-6">
             <label>Product Name</label>
             <br>
             <input type="text" name="productname" value="'.$row['p_name'].'" required>
            </div>
            <div class="col-md-6">
             <label>Supplier</label>
             <br>
             <select name="sup_id" class="form-control" style="width:45px;" required>
             <option value="'.$row['sup_id'].'">'.$row['sup_name'].' </option>
             <option value="2">Philip Kisabit</option>
             <option value="3">Bermi Kanyonge</option>
             <option value="4">Eva Soita</option>
             <option value="5">joreck big enterprises</option>
             <option value="7">Grace Ireri</option>
             <option value="26">Tiffany</option>
             <option value="29">Chepkemoi Mutai</option>
             <option value="30">YVONNE CHEMUTAI</option>
             <option value="31">CHRISTINE MUENI</option>
             <option value="34">Caroline chepngeno</option>
             
             </select>
             </div>
             <div class="col-md-6">
             <label>Purchase Price</label><br>
             <input type="text" name="purchaseprice" value="'.$row['purchase_price'].'" required>
             </div>
             <hr>
             <div class="col-md-6">
              <label> Selling Price</label><br>
             <input type="text" name="sellprice" value="'.$row['sell_price'].'" required>
             </div>
            <div class="col-md-6">
             <input type="hidden" name="product_ID" value="'.$row['p_id'].'" required>
             </div>
             <br>
             <br>
            <div class="col-md-12">
             <br>
            <br>
             <button name="ddupdate" class="btn btn-primary">
                 Update
             </button>
         </div>
        
 </form>
     </div>
      </div>
    </div>
  </div>
</div>'. "</td>"; 
    echo "</tr>";
    
  }
} else {
  echo "0 results";
}
$link->close();
?>
 
        </tbody>
        <tfoot>
             <tr>
               
                <th colspan="2">Total</th>
                <th><?php echo number_format($Totalpurchase, 2, '.', ','); ?></th>
                <th><?php echo number_format($Totalprice, 2, '.', ','); ?></th>
                <th><?php echo number_format($Totalprofit, 2, '.', ','); ?></th>
                <th><?php echo number_format($Totalrprofit, 2, '.', ','); ?></th>
                <th><?php echo number_format($Totalssprofit, 2, '.', ','); ?></th>
                <th><?php echo number_format($Totalsprofit, 2, '.', ','); ?></th>
                
            </tr> 
          
        </tfoot>
    </table>
							</div>
						</div>
			        </div>
			    </div>
			</form>
	    </div>

	</section>
  	<!-- Content end -->
      <script>
     $(document).ready(function() {
    $('#example').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    } );
} );
    </script>
   
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
 <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
 <script src="https://code.jquery.com/jquery-3.5.1.js"></script>

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script> 
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
  </section>
  <!-- Content End -->
</div>
<!--  Content Wrapper End -->

<script type="text/javascript">
$(document).ready(function() {
	storeApp.intiTinymce();
});
</script>

<?php include ("footer.php"); ?>
