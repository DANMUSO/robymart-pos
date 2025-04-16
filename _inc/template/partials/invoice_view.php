      <?php
    $link = mysqli_connect("127.0.0.1", "admin", "@12345PoS", "robymartpos");


// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
             $receipts = "SELECT S.item_price, S.item_total, S3.username, S2.created_at, S.item_quantity, S.item_name, S.invoice_id 
FROM selling_item S 
INNER JOIN selling_info S2 ON S.invoice_id = S2.invoice_id
INNER JOIN users S3 ON S3.id = S2.created_by
  WHERE S.invoice_id = '$invoice_id'";
  $result = $link->query($receipts);
  $Total = 0;
  $created_at = 0;
  $username = 0;
if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
 $created_at = $row['created_at'];
  $username = $row['username'];
   $Total = round($row['item_total'] , 2) + $Total;
  }
}


             ?>
             
              <div class="row">
        
      <div class="col-md-12">
        <div class="box box-info">
        	<div class='box-body'>  
        	      <div class="row">

				<div class="col-sm-6 top-left">
					<img src="http://34.232.46.231/modernpos/assets/itsolution24/img/logo-favicons/4_logo.png" style="width:30%; height:70px;">
				</div>

				<div class="col-sm-6 top-right">
						<h3 class="marginright">INVOICE- <?php echo $invoice_id;?></h3>
						<span class="marginright"><?php echo $created_at;?></span>
			    </div>

			</div>
        		<div class="row">
			    <div class="col-md-4">
			        <h2><?php echo store('name'); ?></h2>
			        <p>1164-00502, Karen</p>
					<p>Phone:  254759194946</p>
					<p>Email: robbymart24@gmail.com</p>
			    </div>
			     <div class="col-md-4">
			         <h2>Cash Invoice</h2>
			        	<p>For Deliveries, Call - 254714982656</p>
					<p>M-Pesa Till No <?php 
					if( store('name') == "Robbymart- Ola"){
					    echo "9365641";
					}else{
					    echo "9375223";
					}
					
					?>  </p>
			    </div>
			     <div class="col-md-4">
			         <h2>Payment details</h2>
			        	<p>Date: <b><?php echo $created_at;?></b></p>
					<p>Total Amount: <b>  KES <?php echo number_format($Total, 2, '.', ',') ;?></b></p>
					<p>Served By:<b><?php echo $username;?></b></p>
			    </div>
			</div>
					<div class="row">
				<table class="table table-striped">
			      <thead>
			        <tr>
			           <tr>
    <th>SL </th>
    <th>Name</th>
    <th>Qty</th>
     <th></th>
    <th>Price</th>
    <th>Amount</th>
			        </tr>
			      </thead>
			      <tbody>
			      
    <?php
             $receipts = "SELECT S.item_price, S.item_total, S2.payment_status, S2.created_at, S.item_quantity, S.item_name, S.invoice_id 
FROM selling_item S 
INNER JOIN selling_info S2 ON S.invoice_id = S2.invoice_id
  WHERE S.invoice_id = '$invoice_id'";
  $result = $link->query($receipts);
  $Total = 0;
  $counter = 1;
  $_SESSION['total'] = $Total;
             ?>
             
             
<?php
    while($row = $result->fetch_assoc()):
        $item_name = $row['item_name'];
        $item_quantity = $row['item_quantity'];
        $item_price =  number_format($row['item_price'], 2, '.', ',') ;
        $item_total =  number_format($row['item_total'], 2, '.', ',');
        $Total = round($row['item_total'] , 2) + $Total;
?>
    <tr>
        <td class="count"><?php echo $counter++; ?></td>
        <td><?php echo "$item_name"; ?></td>
        <td><?php echo "$item_quantity"; ?></td>
        <td></td>
        <td><?php echo "$item_price"; ?></td>
        <td><?php echo "$item_total"; ?></td>
    </tr>
   
<?php endwhile; ?>
 
             <tr><td colspan="6"><br></td></tr>
                  <tr class="lead marginbottom"><td colspan="5">Total Amount :</td><td> KES <?php echo number_format($Total, 2, '.', ',') ;?></td></tr>
                   <tr class="lead marginbottom"><td colspan="5">Amount Paid:</td><td> KES <?php echo number_format($Total, 2, '.', ',') ;?></td></tr>
			       </tbody>
			    </table>

			</div>
			
			<div class="row">
			<div class="col-xs-6 margintop">
				<p class="lead marginbottom">THANK YOU!</p>

			</div>
			</div>
			<a href='http://34.232.46.231/modernpos/admin/view_invoice.php?invoice_id=<?php echo "$invoice_id"; ?>' target="_blank">PRINT NOW</a>
		  <hr>
		  <center><i><a href="https://qloudpointsolutions.com/" target="_blank">Powered by Qloud Point Solutions Ltd</a></i></center>
    		  </div> 
        </div>
      </div>
    </div>
