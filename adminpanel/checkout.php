<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTELS,'view');
if(empty($_SESSION['bookCart']['dataValue']) || $_SESSION['bookCart']['hotel_id']==''){
	header("location:booknow.php?type=N");	
	exit;
}
//---------------------------------------------------------------------------------------------------------
$resLogo  =  selectColumn(TBL_SHOP,'image'," WHERE `id` = '".addslashes($_SESSION['shop'])."'");
?>
<?php include_once("includes/header.php")?>
<?php include_once("includes/left.php")?>

<!--getting all the details of hotel, guest and customer-->
<?php $sqlGuestDetail = executeSQl("SELECT * FROM `".TBL_CUSTOMER."` WHERE type='1' and id_customer= '".addslashes($_SESSION['bookCart']['id_guest'])."'"); 
		 $rowGuestDetail = $db->fetch_object2($sqlGuestDetail); ?>
<?php $resHotelDetail = selectSql(TBL_HOTELS,"where id='".addslashes($_SESSION['bookCart']['hotel_id'])."' ",' ORDER BY `name`'); 
		  $resultHotelDetail = $db->fetch_object2($resHotelDetail); ?>
<?php $resContact = selectSql(TBL_CUSTOMER,"where type='2' and id_customer='".addslashes($_SESSION['bookCart']['id_contacts'])."'",''); 
		  $resultContact = $db->fetch_object2($resContact);
	 $resCompany = selectSql(TBL_COMPANY,"where id_company='".addslashes($_SESSION['bookCart']['id_company'])."'",''); 
		  $resultCompany = fetch_object($resCompany);
		  
		$resCompanyArea = selectSql(TBL_AREAS,"where id='".addslashes($resultCompany->area)."'",''); 
			  $resultCompanyArea = fetch_object($resCompanyArea);

		   ?>
<!--getting all the details of hotel, guest and customer end-->
<?php 
$reservationDate = explode(' to ',$_SESSION['bookCart']['reservation_date']);
$startDate = $reservationDate['0'];
			$lastRecordRes = executeSql("SELECT AUTO_INCREMENT as maxId FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = '".TBL_ORDERS."' and TABLE_SCHEMA='".$DB_NAME."'");
			$lastRecordRow = $db->fetch_object2($lastRecordRes);
			$newId = sprintf("%'03d",$lastRecordRow->maxId);
			
			
			
$query4 = "SELECT * FROM fs_orders where id_hotel ='".$_SESSION['bookCart']['hotel_id']."' order by reference desc ";
$result4 = executeSql($query4,$link);
$query4count = mysqli_num_rows($result4);


if($query4count>0) {
	   
$query4data = mysqli_fetch_array($result4);
$fs_orders_id = explode('/',$query4data['reference']);
$fs_orders_id		=	$fs_orders_id[1];

$reference_increment_start  =  selectColumn(TBL_HOTELS,'reference_increment_start'," WHERE `id` = '".addslashes($_SESSION['bookCart']['hotel_id'])."'");

		if($fs_orders_id>=$reference_increment_start){
		
			$fs_orders_idnew 	= $fs_orders_id+1;	
			}else{
		
			$fs_orders_idnew 	= $reference_increment_start;
				}


}else{

$fs_orders_idnew = 1;	
}

$newId = str_pad($fs_orders_idnew, 3, '0', STR_PAD_LEFT);	
			
			
			$order_reference = $resultHotelDetail->hotel_code.'/'.$newId;
$insertOrder = "INSERT INTO `".TBL_ORDERS."` SET 
				  `reference`='".addslashes($order_reference)."',
				  `code`='00',
				  `id_shop_group`='1',
				  `id_shop` = '".addslashes($_SESSION['shop'])."',
				  `id_lang`='1',
				  `id_hotel`='".addslashes($_SESSION['bookCart']['hotel_id'])."',
				  `id_rate`='".addslashes($_SESSION['bookCart']['rate_id'])."',
				  `id_customer`='".addslashes($_SESSION['bookCart']['id_guest'])."',
				  `id_company`='".addslashes($_SESSION['bookCart']['id_company'])."',
				  `id_company_person`='".addslashes($_SESSION['bookCart']['id_contacts'])."',
				  `id_cart`='0',
				  `id_currency`='1',
				  `id_area`='".addslashes($resultCompany->deals_in)."',
				  `id_executive`='".addslashes($resultCompanyArea->user_id)."',				  
				  `payment_status`='".addslashes($_POST['payment_status'])."',
				  `booking_status`='".addslashes($_POST['booking_status'])."',
				  `conversion_rate`='1.000000',
				  `discount_type`='".addslashes($_SESSION['bookCart']['discountType'])."',
				  `discount_var`='".addslashes($_SESSION['bookCart']['discountVar'])."',
				  `tarrif_price`='".addslashes($_SESSION['bookCart']['totalPriceTarrif'])."',
				  `food_price`='".addslashes($_SESSION['bookCart']['totalPriceFood'])."',
				  `extra_price`='".addslashes($_SESSION['bookCart']['totalPriceExtra'])."',
				  `total_discounts`='".addslashes($_SESSION['bookCart']['discountPrice'])."',
				  `subtotal`='".addslashes($_SESSION['bookCart']['totalPrice'])."',
				  `total_tax`='".addslashes($_SESSION['bookCart']['taxablePrice'])."',
				  `total_price`='".addslashes(round((($_SESSION['bookCart']['totalPrice']-$_SESSION['bookCart']['discountPrice'])+$_SESSION['bookCart']['taxablePrice']),0,PHP_ROUND_HALF_UP))."',
				  `balance`='".addslashes($_SESSION['bookCart']['finalPrice']-$_REQUEST['amount_received'])."',
				  `amount_received`='".addslashes($_REQUEST['amount_received'])."',
				  `total_products`='".addslashes($_SESSION['bookCart']['totalRoom'])."',
				  `total_adults`='".addslashes($_SESSION['bookCart']['totalAdult'])."',
				  `total_infants`='".addslashes($_SESSION['bookCart']['totalInfant'])."',
				  `total_child`='".addslashes($_SESSION['bookCart']['totalChild'])."',
				  `invoice_number`='',
				  `invoice_date`='".addslashes(currenDateTime())."',
				  `payment_date`='".addslashes(date('Y-m-d',strtotime($_POST['payment_date'])))."',
				  `checkin`='".addslashes(date('Y-m-d',strtotime($reservationDate[0])))."',
				  `checkout`='".addslashes(date('Y-m-d',strtotime($reservationDate[1])))."',
				  `no_of_days`='".addslashes($_SESSION['bookCart']['noOfDays'])."',
				  `payment_reference`='".addslashes($_REQUEST['payment_reference'])."',
				  `other_reference`='".addslashes($_POST['other_reference'])."',
				  `payment_remarks`='".addslashes($_POST['payment_remarks'])."',
				  `arrival_time`='".addslashes($_POST['arrival_time'])."',
				  `arrival_from`='".addslashes($_POST['arrival_from'])."',
				  `departing_to`='".addslashes($_POST['departing_to'])."',
				  `pickup`='".addslashes($_POST['pickup'])."',
				  `pickup_details`='".addslashes($_POST['pickup_details'])."',
				  `travel`='".addslashes($_POST['travel'])."',
				  `booking_hrough`='".addslashes($_POST['booking_hrough'])."',
				  `requests`='".addslashes($_POST['requests'])."',
				  `valid`='1',
				  `segment_id`='".addslashes($_POST['segment'])."',
				  `series_id`='".addslashes($_SESSION['bookCart']['series'])."',
				    `amendment_remarks_id`='".addslashes($_REQUEST['amendment_remarks_id'])."',
				  `operator_id`='".addslashes($_SESSION['bookCart']['operator'])."',
				  `type`='".addslashes($_SESSION['bookCart']['type'])."',
				  `date_created`='".currenDateTime()."',
				  `last_modified`='".currenDateTime()."',
				  `last_modified_by`='".$_SESSION['userId']."'";

if(executeSql($insertOrder)){
	$orderId = mysqli_insert_id();
}
do{ $ii=0;
foreach($_SESSION['bookCart']['dataValue'] as $uniqueCode =>$dataCode){
		$dataValue = explode('|',$dataCode);
		 $insertOrderDetail = "INSERT INTO `".TBL_ORDER_DETAIL."` SET 
						  `id_order`='".addslashes($orderId)."',
						  `id_shop`='1',
						  `hotel_id`='".addslashes($dataValue['1'])."',
						  `room_id`='".addslashes($dataValue['2'])."',
						  `rate_id`='".addslashes($dataValue['3'])."',
						  `rate_plan_id`='".addslashes($_SESSION['bookCart']['rate_plan_id'][$ii])."',
						  `rate_assign_id`='".addslashes($dataValue['5'])."',
						  `dated`='".addslashes(date('Y-m-d',strtotime($startDate)))."',
						  `type`='".addslashes($dataValue['6'])."',
						  `room_quantity`='".addslashes($_SESSION['bookCart']['room_quantity'][$uniqueCode])."',						 
						  `adults`='".addslashes($_SESSION['bookCart']['adult_no'][$uniqueCode])."',
						  `infants`='".addslashes($_SESSION['bookCart']['infant_no'][$uniqueCode])."',
						  `child`='".addslashes($_SESSION['bookCart']['child_no'][$uniqueCode])."',
						  `tarrif_price`='".addslashes($_SESSION['bookCart']['tarrif_price'][$uniqueCode]*$_SESSION['bookCart']['room_quantity'][$uniqueCode])."',
						  `food_price`='".addslashes($_SESSION['bookCart']['inclusion_food'][$uniqueCode])."',
						  `extra_price`='".addslashes($_SESSION['bookCart']['pkg_extra'][$uniqueCode])."',

						  `total_price`='".addslashes($_SESSION['bookCart']['room_price'][$uniqueCode])."',
						  `original_product_price`='".addslashes($_SESSION['bookCart']['tarrif_price'][$uniqueCode])."',
						  `unique_code`='".addslashes($uniqueCode)."'";
		executeSql($insertOrderDetail);
		
	if($_POST['booking_status'] ==1 || $_POST['booking_status'] ==2){	
		$updateInventory = executeSql("UPDATE  `".TBL_INVENTORY."`  SET 
								crs_available = crs_available-'".$_SESSION['bookCart']['room_quantity'][$uniqueCode]."',
								blocked_hotel = blocked_hotel+'".$_SESSION['bookCart']['room_quantity'][$uniqueCode]."',
								online_allocation=online_allocation-'".$_SESSION['bookCart']['room_quantity'][$uniqueCode]."' 
								where  `hotel_id`='".addslashes($dataValue['1'])."' and 
						  		`room_id`='".addslashes($dataValue['2'])."' and 
								allocation_date = '".addslashes(date("Y-m-d", strtotime($startDate)))."'");
		}
	$ii++;}
	
$startDate = date ("Y-m-d", strtotime("+1 day", strtotime($startDate)));

}
while (strtotime($startDate) < strtotime($reservationDate[1]));
			  
//executeSql($query);
?>

   
				
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> Hotel Booking Manager <small>Book Now</small> </h1>
    <ol class="breadcrumb">
      <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <!--<li class="active">Booking Details</li>-->
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="box box-default">
      <div class="form-group has-error" align="center">
        <?php if($_SESSION['errorMsg']){?>
        <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
        <?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
        <p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
        <?php unset($_SESSION['successMsg']);}?>
      </div>
      <div class="box-header with-border">
          <h3 class="box-title">  <i class="fa fa-hotel"></i> Booking Process Successful </h3>
        </div>
      <!-- /.box-header -->
	   <!-- /.first section -->
	  <?php $content = '<section class="invoice" id="invoice">
						<div class="row invoice-info">
						   <div class="col-sm-8 invoice-col">
							<address>
						   <img src="'.$SITE_URL.'/uploaded_files/shop/'.$resLogo.'" class="img-responsive" alt="logo" title="logo" />
							</address>
						  </div>
						  <div class="col-sm-4 invoice-col" > 
							<address><strong>WH '.$resultHotelDetail->name.'</strong><br>'.
								$resultHotelDetail->address.'<br>'.
								$resultHotelDetail->city.' - '.$resultHotelDetail->pincode.'<br>'.
								selectColumn(TBL_STATE,'name'," WHERE `id_state` = '".addslashes($resultHotelDetail->state)."'").', India <br>
								<strong>Phone :</strong> '.$resultHotelDetail->phone1.'<br>
								<strong>Email :</strong> '.$resultHotelDetail->email.'<br>
								<strong>Website :</strong> https://welcomheritagehotels.in
							</address>
						  </div>
						</div>
        <!-- info row -->
       <hr>'; ?>
	 <!-- /.first section end -->	
	<!-- /.second section -->
	<?php $content .=  '<div class="row invoice-info">          
          <div class="col-sm-6 invoice-col"> <b>Booking '.selectColumn(TBL_HTL_BOOKING_STATUS,'name'," WHERE `id` = '".addslashes($_POST['booking_status'])."'");
		  if($_POST['payment_date'] !=''){
		  $content .= ' - '.dateformat_date($_POST['payment_date']);
		  }
		  
		$content .='</b> <br><br>         
          </div>
		   <div class="col-sm-6 invoice-col" style="text-align:right"> <b>Reservation ID# '.$order_reference.'</b><br><br>       
            
          </div>
          <!-- /.col -->	
		  <div class="col-sm-12 invoice-col"> Dear ';
		 if($resultCompany->id_default_group == '0'){	
		  	$content .= $rowGuestDetail->ape.' '.$rowGuestDetail->first_name.' '.$rowGuestDetail->last_name; 
			}else{
			$content .= $resultContact->ape.' '.$resultContact->first_name.' '.$resultContact->last_name;
			}
		  
		  $content .= ',<br><br>       
          </div>
		   <div class="col-sm-12 invoice-col">
		  Greetings from WelcomHeritage!!!<br><br>       
			We are delighted to provide you with the room reservation status and summary of your booking mentioned below:<br><br>       
			</div>
        </div>'; 
		
		 ?>
	  
	<!-- /.second section end-->	
	
	<!-- /.third section -->
		
     <?php $content .='<div class="row invoice-info">
          <div class="col-sm-7 invoice-col" ><b>Guest Details</b>
            <address>
            <strong>'.$rowGuestDetail->first_name.' '.$rowGuestDetail->last_name.'</strong><br>'.
            $rowGuestDetail->address.', '.$rowGuestDetail->city.'<br>'.
            selectColumn(TBL_STATE,'name'," WHERE `id_state` = '".addslashes($rowGuestDetail->id_state)."'").', '.selectColumn(TBL_COUNTRY_LANG,'name'," WHERE `id_country` = '".addslashes($rowGuestDetail->id_country)."'").' '.$rowGuestDetail->postcode.'<br>
            <strong>Phone</strong>: '.$rowGuestDetail->mobile.'<br>
            <strong>Email</strong>: '.$rowGuestDetail->email.'
            </address>
          </div>
        </div>';
		
	?>
	<!-- /.third section end -->	
	<!-- /.fourth section -->	
    <?php $content .='<div class="row invoice-info">
          <div class="col-sm-2 invoice-col"> <b>Checkin Date</b>
            <address>'.dateformat_date($reservationDate[0]).'</address>
          </div>
		   <div class="col-sm-2 invoice-col"> <b>Checkout Date</b>
            <address>'.dateformat_date($reservationDate[1]).'</address>
          </div>
          <div class="col-sm-2 invoice-col"> <b>Total Rooms</b>
            <address>'.$_SESSION['bookCart']['totalRoom'].'</address>
          </div>
          <div class="col-sm-2 invoice-col"> <b>Total Pax</b>
            <address>
            <b><i class="fa fa-male" aria-hidden="true">&nbsp;</i> x </b> '.$_SESSION['bookCart']['totalAdult'].' |
			<b><i class="fa fa-child" aria-hidden="true">&nbsp;</i> x </b> '.$_SESSION['bookCart']['totalChild'].' |
			<b><i class="fa fa-universal-access" aria-hidden="true">&nbsp;</i> x </b> '.$_SESSION['bookCart']['totalInfant'].' <br>
            </address>
          </div>
		  <div class="col-sm-1 invoice-col"> <b>Currency</b>
            <address style="text-align:center;"><i class="fa fa-inr">&nbsp;</i></address>
          </div>
		  <div class="col-sm-1 invoice-col"> <b>Nights</b>
            <address style="text-align:center;">'. $_SESSION['bookCart']['noOfDays'].'</address>
          </div>
		   <div class="col-sm-2 invoice-col"> <b>Payment Status</b>
            <address>'.selectColumn(TBL_ORDER_STATE,'name'," WHERE `id_order_state` = '".addslashes($_POST['payment_status'])."'").'</address>
          </div>
        </div>';
		
	?>
      <!-- /.fourth section  end-->	
		<!-- /.fifth section -->
		
		
       <?php $content .='<div class="row">
          <div class="col-xs-12 table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>S. No.</th>             
				  <th>Room Type</th>
				  <th>Room </th>
				  <th>Pax / Room</th>
				  <th>Rate / Night	</th>
				  <th>Food / Night</th>
				  <th>Room Total</th>
                </tr>
              </thead>
              <tbody>';
			$counter = '1';
			$i=0;
			foreach($_SESSION['bookCart']['dataValue'] as $uniqueCode =>$dataCode){	
			$dataValue = explode('|',$dataCode);	
			$resRoom = executeSql("SELECT rt.name as room_name, rp.name as rate_name, rd.* from `".TBL_RATE_DETAILS."` rd left join `".TBL_ROOM_TYPE."` rt on rd.room_id = rt.id left join `".TBL_RATE_PLAN."` rp on rd.rate_plan_id = rp.id where rd.status='1' and rt.status='1' and rd.rate_assign_id='".addslashes($dataValue['5'])."'  and rd.rate_plan_id='".addslashes($dataValue['4'])."' and rd.rate_id='".addslashes($dataValue['3'])."' and room_id='".addslashes($dataValue['2'])."' order by rd.room_id");	
			if(num_rows($resRoom) >0){
					$rowRoom = $db->fetch_object2($resRoom);
			
				$priceValue = 0;
				$content .=' <tr>
				  <td>'.$counter.'</td>
				  <td>'.$rowRoom->room_name.'</td>
				  <td>'.$_SESSION['bookCart']['room_quantity'][$uniqueCode].'</td>
				  <td><i class="fa fa-male" aria-hidden="true">&nbsp;</i> x '.$_SESSION['bookCart']['adult_no'][$uniqueCode].' | <i class="fa fa-child" aria-hidden="true">&nbsp;</i> x '.$_SESSION['bookCart']['child_no'][$uniqueCode].' | <i class="fa fa-universal-access" aria-hidden="true">&nbsp;</i> x ' .$_SESSION['bookCart']['infant_no'][$uniqueCode].'</td>
				  <td><i class="fa fa-inr">&nbsp;</i> '.$_SESSION['bookCart']['room_price'][$uniqueCode].'</td>
				   <td><i class="fa fa-inr">&nbsp;</i> '.$_SESSION['bookCart']['inclusion_food'][$uniqueCode].'<br>('.selectColumn(TBL_RATE_PLAN,'remarks'," WHERE `id` = '".$_SESSION['bookCart']['rate_plan_id'][$i]."'").')</td>
				    
					
				  <td><i class="fa fa-inr">&nbsp;</i>  '.$_SESSION['bookCart']['room_price'][$uniqueCode]*$_SESSION['bookCart']['noOfDays'].'</td>
				</tr>';
				$counter++;
				$i++;
				}
			}
      $content .='</tbody>
            </table>
          </div>
        </div>';
		?>
		
		<!-- /.fifth section end -->
		
        <!-- /.six section -->
       <?php $content .= '<div class="row">
          <div class="col-sm-7 text-muted well well-sm no-shadow" >
            <p><strong>SPECIAL INSTRUCTIONS:</strong> <br>'.$resultHotelDetail->special_notes.'</p>
			  <p>As per the government of India norms, you are requested to carry a valid photo id with complete address and produce it at the time of check in. For Indian nationals: driver\'s license, passport, voter id or Aadhar card only. Pan card will not be considered as a valid address proof & for international travelers: passport.</p>
          </div>
          <div class="col-sm-5">
            <div class="table-responsive">
              <table class="table">
                <tr>
                  <th style="width:50%">Subtotal:</th>
                  <td><i class="fa fa-inr">&nbsp;</i> '.$_SESSION['bookCart']['totalPrice'].'</td>
                </tr>';				
			if(!empty($_SESSION['bookCart']['discountPrice'])){ 
			$content .= '<tr>
                 		 <th>Discount:</th>
                  		  <td ><i class="fa fa-inr">&nbsp;</i>'.$_SESSION['bookCart']['discountPrice'].' </td>
						</tr>';
				}
				
       $content .= '<tr>
                  <th>Tax </th>
                  <td><i class="fa fa-inr">&nbsp;</i> '.$_SESSION['bookCart']['taxablePrice'].'</td>
                </tr>
                <tr>
                  <th>Total:</th>
                  <td ><i class="fa fa-inr">&nbsp;</i> '.round((($_SESSION['bookCart']['totalPrice']-$_SESSION['bookCart']['discountPrice'])+$_SESSION['bookCart']['taxablePrice']),0,PHP_ROUND_HALF_UP).'</td>
                </tr>
				 <tr>
                  <th>Amount Paid:</th>
                  <td ><i class="fa fa-inr">&nbsp;</i> '.$_REQUEST['amount_received'].'</td>
                </tr>
				 <tr>
                  <th>Balance:</th>
                  <td ><i class="fa fa-inr">&nbsp;</i> '.($_SESSION['bookCart']['finalPrice']-$_REQUEST['amount_received']).'</td>
                </tr>
              </table>
            </div>
          </div>
        </div>';
		?>
       <!-- /.six section end  -->
	   
	    <!-- /.seventh section -->
		<?php $content .='<div class="row">
          <div class="col-sm-12 text-muted  no-shadow" >
            <p><strong>Getting There:</strong> <br> Get Directions to the Hotel by clicking <a target="_blank" href="https://www.google.co.in/maps/place/'.urlencode($resultHotelDetail->name).'/@'.$resultHotelDetail->latitude.','.$resultHotelDetail->longitude.'
" >here</a>.</p>
          </div>
		</div>';
		?>
		 <!-- /.seventh section end-->
		 <!-- /.eighth section -->
	<?php $content .='<div class="row">
          <!-- accepted payments column -->
          <div class="col-sm-12 text-muted  no-shadow" >
            <p><strong><u>Cancellation Policy</u></strong> <br></p>
             
			  <ul>
			  	<li>Full retention will be charged for cancellation received 10 days prior to arrival</li>
				<li>In the unlikely event of our inability to provide the above accommodation, we will arrange alternate accommodation of similar standards and provide transport to the guest at our cost.</li>
				<li>No cancellation charges if cancelled more than 15 days prior to arrival</li>
				<li>One day pro-rata cancellation charges will be forfeited/levied for cancellation received 11-14 days prior to arrival</li>
				<li>The above cancellation policy is not valid during long weekends, Christmas, New Year and during certain other times of the year wherein rooms will be confirmed subject to non refundable advance</li>
			  </ul>
          </div>
		</div>';
		?>
		<!-- /.eighth section end-->
		
		
		<?php //echo $content;
		$_SESSION['bookCart']['content'] = $content;
		 ?>
<br/><br/>		
        <!-- this row will not appear when printing -->
        <div class="row no-print">		
		
		<div class="col-sm-10">
            <a href="mail-template/sendOrderMail.php?id=<?=encryptor('encrypt',$orderId)?>" target="_blank" class="btn btn-primary pull-right" style="margin-right: 5px;">
            <i class="fa fa-download"></i> Send Mail
          </a>
          </div>	
          <div class="col-sm-2">
            <a href="pdf-template/generateOrderPdf.php?id=<?=encryptor('encrypt',$orderId)?>" target="_blank" class="btn btn-primary pull-right" style="margin-right: 5px;">
            <i class="fa fa-download"></i> Generate PDF
          </a>
          </div>		  
		
        </div>
        
        <br/><br/>
      </section> 
    </div>
    <!-- /.row -->
  </section>
  <!-- /.content -->
</div>
<?php 
include_once("includes/footer.php");
unset($_SESSION['bookCart']);
?>
<script>


</script>
