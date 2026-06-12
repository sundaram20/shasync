<?php include_once("../../config/auto_loader.php");
//checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTELS,'view');
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


if($_REQUEST['booking_status']	== 2){
	
	
	  $reservation_date = explode(' to ',$_REQUEST['reservation_date']); 
		 // echo 'Checkin Date : '. dateformat_date($reservation_date[0]).' | Checkout Date : '. dateformat_date($reservation_date[1]);
		   
		   if(strtotime ( '-45 day' ,strtotime($reservation_date[0])) < strtotime(date('d-m-Y'))){
		   
		   $payment_date = date('d-m-Y');
		   }else {
		   $payment_date = date('d-m-Y',(strtotime ( '-45 day' ,strtotime($reservation_date[0]) ) ));
		   
		   }
		   
		   
		    
	?>
    <div class="form-group col-sm-4"  >
                           <label for="payment_date" >Date</label>
                           <div class="input-group">
                            <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                            <input type="text" class="form-control" id="payment_date" name="payment_date" automcomplete="off" value="<?php echo  $payment_date; ?>" data-parsley-required>
                          </div>
                         </div>
    <?php 
	}
?><script>
  $("#payment_date").datepicker({
  	dateFormat : 'dd-mm-yy',
	minDate : new Date(),
	maxDate: new Date('<?php echo date('Y-m-d',strtotime($reservation_date[0] ) ); ?>')
	});
  </script>