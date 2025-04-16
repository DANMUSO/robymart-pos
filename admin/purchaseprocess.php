              <?php
                   include ("config.php");
                  $date = date("Y-m-d");
                  $invoiceid = $_POST['invoiceid'];
                  $product_id = $_POST['productid'];
                  $sup_id = $_POST['supplierid'];
                  $units = $_POST['productunit'];
                  $return_by = $_POST['returnby'];
                  $return_date = $date;
                  $note = $_POST['returnnote'];
                  $storeid =  $_POST['storeid'];
                   $invoiceno = "SELECT PT.quantity_in_stock FROM product_to_store PT WHERE product_id ='$product_id' AND sup_id = '$sup_id' ";
                               $in_no = $link->query($invoiceno);
                                if ($in_no->num_rows > 0 ) {
                                   while ($row = $in_no->fetch_assoc()) {
                                     $quantity =  $row['quantity_in_stock'];
                                     
                                   }
                                }
                    $purchase = "SELECT PI.total_sell FROM purchase_item PI WHERE item_id ='$product_id' AND invoice_id = '$invoiceid' ";
                               $purchasereturn = $link->query($purchase);
                                if ($purchasereturn->num_rows > 0 ) {
                                   while ($row = $purchasereturn->fetch_assoc()) {
                                     $pquantity =  $row['total_sell'];
                                     
                                   }
                                }
                                if($quantity < $units){
                                    $response['exceed'] = "Entered value exceed the available quantity!"; 
                                    echo json_encode($response);
                                }else{
                                    $totalquantity = $quantity - $units;
                             $updatestock = "UPDATE product_to_store SET quantity_in_stock ='$totalquantity'  WHERE product_id ='$product_id' AND sup_id = '$sup_id' ";
                            mysqli_query($link, $updatestock);
                                     $sql = "INSERT INTO purchase_returns (invoiceno, p_id, sup_id, units,store_id, returned_by, date, note) VALUES('$invoiceid','$product_id','$sup_id', '$units', $storeid ,'$return_by', '$return_date', '$note')";
                            mysqli_query($link, $sql);
                             $response['success'] = "Success!"; 
                                    echo json_encode($response);
                                }
                 
                            
                 
                      
                  
              ?>