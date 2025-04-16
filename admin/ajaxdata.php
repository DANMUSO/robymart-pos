<?php 
include('config.php');
if (isset($_POST['country_id'])) {
    
    $id = $_POST['country_id'];
    echo $id;
	$query = "SELECT item_name, item_id FROM selling_item where invoice_id= '$id' ";
	$result = $link->query($query);
	if ($result->num_rows > 0 ) {
			echo '<option value="">Select Product Name</option>';
		 while ($row = $result->fetch_assoc()) {
		 	echo '<option value='.$row['item_id'].'>'.$row['item_name'].'</option>';
		 }
	}else{

		echo '<option>No Product Name Found!</option>';
	}

}elseif (isset($_POST['state_id'])) {
	 
     $pid = $_POST['state_id'];
	$query = "SELECT sup_id FROM selling_item where item_id ='$pid' GROUP BY sup_id";
	$result = $link->query($query);
	if ($result->num_rows > 0 ) {
		 while ($row = $result->fetch_assoc()) {
		     $supid = $row['sup_id'];
		     $sql = "SELECT sup_id, sup_name FROM suppliers where sup_id ='$supid' ";
        	$results = $link->query($sql);
        	if ($results->num_rows > 0 ) {
        		 while ($row = $results->fetch_assoc()) {
        		     
        		     
        		 	echo '<option value='.$row['sup_id'].'>'.$row['sup_name'].'</option>';
        		 }
        	}
		     
		 }
	}else{

		echo '<option>No Supplier Found!</option>';
	}

}


?>