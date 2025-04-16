

<!DOCTYPE html>
<html>
<head>
<title>Page Title</title>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

</head>
<body>

    <div class="modal-body">
                                                  <div class="row">
                                                      <div class="col-md-12">
                                                          <center><h5>Recent Payments</h5></center>
                                    					  <table class="table table-bordered table-striped">
                                    		                <thead>
                                    		                <tr>
                                    		                    <th>Customer Name</th>
                                    		                    <th>Amount</th>
                                    		                    <th>Transaction Code</th>
                                    		                    <th>Action</th>
                                    		                </tr>
                                    		                </thead>
                                    						<tbody id="clientdata">
                                    						</tbody>
                                    		            </table>
                                                      </div>
                                                  </div>
                                                  <form class="form-inline" id="cpayment">
                                                      <br>
                                                      <br>
                                                      <div class="form-group mx-sm-3 mb-2">
                                                          
                                                        <label for="inputPassword2" class="sr-only">Payable Amount  </label>
                                                        <input type="text" style="color:red;" id="tillno" name="tillno" value="7413330">
                                                          <input type="text" style="color:red;" id="amount" name="amount" value="1">
                                                      </div>
                                                      <br>
                                                      <br>
                                                      <button  class="btn btn-primary mb-2" style="width:150px; height:40px;"><h6>Check Payment</h6></button>
                                                    </form>
									        <a href='#' onclick='confirm(5)'>Confirm</a>
                                              </div>

<script>
    $('#cpayment').on('submit', (function (e) {
    
      e.preventDefault();
      var formData = new FormData(this);
        
      $.ajax({
        type: 'POST',
        url: "https://robbymart.co/modernpos/admin/ROBY/confirm_payment.php",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function (data) {
        $("#clientdata").empty();
        console.log(data);
        
        var json=JSON.parse(data);
        console.log(json.error);
         if (json.error == "development") {

           
           $.each(JSON.parse(data), function (key, value) {
							$('#clientdata').append("<tr style='background-color'>\
										<td colspan='4'>"+'No Transaction found!'+"</td>\
										</tr>");
						})

          }else{
         $.each(JSON.parse(data), function (key, value) {
							$('#clientdata').append("<tr>\
										<td>"+value.customeName+"</td>\
										<td>"+value.amount+"</td>\
										<td>"+value.transactionId+"</td>\
										<td>"+"<a href='#' onclick='confirm("+value.id+")'>Confirm</a>"+"</td>\
										</tr>");
						})
          }
        }

      });
    }));
    
    function confirm(id){
        console.log(id);
              $.ajax({
           
        type: 'POST',
        url: "https://robbymart.co/modernpos/admin/ROBY/verified_payment.php",
        data: { id : id},
        
        success: function (data) {
       
        }

      });
    }
</script>

</body>
</html>