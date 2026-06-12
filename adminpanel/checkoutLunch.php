<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTELS,'view');
if($_SESSION['editCart'] == ''){
	header("location:manageOrders.php");
	exit;	
}

//---------------------------------------------------------------------------------------------------------
$resLogo  =  selectColumn(TBL_SHOP,'image'," WHERE `id` = '".addslashes($_SESSION['shop'])."'");
?>
<?php include_once("includes/header.php")?>
<?php include_once("includes/left.php")?>
<?php $sqlGuestDetail = executeSQl("SELECT * FROM `".TBL_CUSTOMER."` WHERE type='1' and id_customer= '".addslashes($_POST['id_guest'])."'"); 
		 $rowGuestDetail = $db->fetch_object2($sqlGuestDetail); ?>
<?php $resHotelDetail = selectSql(TBL_HOTELS,"where status='1' AND id='".addslashes($_POST['hotel_id'])."' ",' ORDER BY `name`'); 
		  $resultHotelDetail = $db->fetch_object2($resHotelDetail); ?>
<?php $resContact = selectSql(TBL_CUSTOMER,"where type='2' and id_customer='".addslashes($_POST['id_contacts'])."'",''); 
		  $resultContact = $db->fetch_object2($resContact); 
	 $resCompany = selectSql(TBL_COMPANY,"where id_company='".addslashes($_POST['id_company'])."'",''); 
		  $resultCompany = fetch_object($resCompany);
	$resCompanyArea = selectSql(TBL_AREAS,"where id='".addslashes($resultCompany->area)."'",''); 
		  $resultCompanyArea = fetch_object($resCompanyArea);	  
		  ?>
<?php 

$order_reference = $_SESSION['editCart']['orderReference'];
if($_POST['Save']){

if($err == 0){//No error
		if(($_POST['Save'] == 'Add') && empty($_POST['eId'])){//add

/*$lastRecordRes = executeSql("SELECT AUTO_INCREMENT as maxId FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = '".TBL_ORDERS."' and TABLE_SCHEMA='".$DB_NAME."'");
			$lastRecordRow = $db->fetch_object2($lastRecordRes);
			$newId = sprintf("%'03d",$lastRecordRow->maxId);
			$order_reference = 'RES/'.$resultHotelDetail->hotel_code.'/'.$newId;*/
			
$lastRecordRes = executeSql("SELECT AUTO_INCREMENT as maxId FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = '".TBL_ORDERS."' and TABLE_SCHEMA='".$DB_NAME."'");
			$lastRecordRow = $db->fetch_object2($lastRecordRes);
			$newId = sprintf("%'03d",$lastRecordRow->maxId);
			
			
			
$query4  = "SELECT * FROM fs_orders where id_hotel ='".$_REQUEST['hotel_id']."' and `id_shop` = '".addslashes($_SESSION['shop'])."' order by id_order desc ";
$result4 = executeSql($query4,$link);
$query4count = mysqli_num_rows($result4);


if($query4count>0) {
	   
$query4data = mysqli_fetch_array($result4);
$fs_orders_id = explode('/',$query4data['reference']);
$fs_orders_id		=	$fs_orders_id[1];

$reference_increment_start  =  selectColumn(TBL_HOTELS,'reference_increment_start'," WHERE `id` = '".addslashes($_REQUEST['hotel_id'])."'");

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
				  `id_hotel`='".addslashes($_POST['hotel_id'])."',
				  `id_customer`='".addslashes($_POST['id_guest'])."',
				  `id_company`='".addslashes($_POST['id_company'])."',
				  `id_company_person`='".addslashes($_POST['id_contacts'])."',
				  `id_cart`='0',
				  `id_currency`='1',
				  `id_area`='".addslashes($resultCompany->deals_in)."',
				  `id_executive`='".addslashes($resultCompanyArea->user_id)."',							  
				  `payment_status`='".addslashes($_POST['payment_status'])."',
				  `booking_status`='".addslashes($_POST['booking_status'])."',
				  `conversion_rate`='1.000000',
				  `discount_type`='".addslashes($_SESSION['editCart']['discountType'])."',
				  `discount_var`='".addslashes($_SESSION['editCart']['discountVar'])."',
				  `tarrif_price`='".addslashes($_SESSION['editCart']['totalPriceTarrif'])."',
				  `food_price`='".addslashes($_SESSION['editCart']['totalPriceFood'])."',
				  `extra_price`='".addslashes($_SESSION['editCart']['totalPriceExtra'])."',
				  `total_discounts`='".addslashes($_SESSION['editCart']['discountPrice'])."',
				  `subtotal`='".addslashes($_SESSION['editCart']['totalPrice'])."',
				  `total_tax`='".addslashes($_SESSION['editCart']['taxablePrice'])."',
				  `lunch_type`='".addslashes($_REQUEST['meals_type'])."',
  				  `lunch_tax_percentage`='".addslashes($_SESSION['editCart']['lunch_tax_percentage'])."',
				 `total_price`='".addslashes(round((($_SESSION['editCart']['totalPrice']-$_SESSION['editCart']['discountPrice'])+$_SESSION['editCart']['taxablePrice']),0,PHP_ROUND_HALF_UP))."',
				  `balance`='".addslashes($_SESSION['editCart']['totalPrice']+$_SESSION['editCart']['taxablePrice']-$_SESSION['editCart']['discountPrice']-$_REQUEST['amount_received'])."',
				  `amount_received`='".addslashes($_REQUEST['amount_received'])."',
				  `total_products`='".addslashes($_SESSION['editCart']['totalRoom'])."',
				  `total_adults`='".addslashes($_SESSION['editCart']['totalAdult'])."',
				  `total_infants`='".addslashes($_SESSION['editCart']['totalInfant'])."',
				  `total_child`='".addslashes($_SESSION['editCart']['totalChild'])."',
				  `invoice_number`='',
				  `invoice_date`='".addslashes(currenDateTime())."',
				  `payment_date`='".addslashes(date('Y-m-d',strtotime($_POST['payment_date'])))."',
				  `checkin`='".addslashes(date('Y-m-d',strtotime($_POST['checkin'])))."',
				  `checkout`='".addslashes(date('Y-m-d',strtotime($_POST['checkin'])))."',
				  `no_of_days`='".addslashes($_SESSION['editCart']['noOfDays'])."',
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
				  `series_id`='".addslashes($_SESSION['editCart']['series'])."',
				  `operator_id`='".addslashes($_SESSION['editCart']['operator'])."',
				  `type`='L',
				  `date_created`='".currenDateTime()."',
				  `last_modified`='".currenDateTime()."',
				  `last_modified_by`='".$_SESSION['userId']."'";
				  executeSql($insertOrder);
	$orderId = mysqli_insert_id();
	
	
	
	/*====================================Amendment count Query Insert Start=======================================*/

$AmendmentSQL = "INSERT INTO  `".TBL_AMENDMENT_COUNT."` SET 	
 				  `id_order`='".addslashes($orderId)."',			 
 				  `id_shop` = '".addslashes($_SESSION['shop'])."',				  
				  `id_hotel`='".addslashes($_POST['hotel_id'])."',
				  `id_customer`='".addslashes($_POST['id_guest'])."',
				  `id_company`='".addslashes($_POST['id_company'])."',
				  `id_company_person`='".addslashes($_POST['id_contacts'])."',				
				  `id_executive`='".addslashes($resultCompanyArea->user_id)."',							  
				  `payment_status`='".addslashes($_POST['payment_status'])."',
				  `booking_status`='".addslashes($_POST['booking_status'])."',

				  `discount_type`='".addslashes($_SESSION['editCart']['discountType'])."',
				  `discount_var`='".addslashes($_SESSION['editCart']['discountVar'])."',
				  `tarrif_price`='".addslashes($_SESSION['editCart']['totalPriceTarrif'])."',
				  `food_price`='".addslashes($_SESSION['editCart']['totalPriceFood'])."',
				  `extra_price`='".addslashes($_SESSION['editCart']['totalPriceExtra'])."',
				  `total_discounts`='".addslashes($_SESSION['editCart']['discountPrice'])."',
				  `subtotal`='".addslashes($_SESSION['editCart']['totalPrice'])."',
				  `total_tax`='".addslashes($_SESSION['editCart']['taxablePrice'])."',
				  `lunch_type`='".addslashes($_REQUEST['meals_type'])."',
  				  `lunch_tax_percentage`='".addslashes($_SESSION['editCart']['lunch_tax_percentage'])."',
				 `total_price`='".addslashes(round((($_SESSION['editCart']['totalPrice']-$_SESSION['editCart']['discountPrice'])+$_SESSION['editCart']['taxablePrice']),0,PHP_ROUND_HALF_UP))."',
				    `balance`='".addslashes($_SESSION['editCart']['totalPrice']+$_SESSION['editCart']['taxablePrice']-$_SESSION['editCart']['discountPrice']-$_REQUEST['amount_received'])."',
				  `amount_received`='".addslashes($_REQUEST['amount_received'])."',
				  `total_products`='".addslashes($_SESSION['editCart']['totalRoom'])."',
				  `total_adults`='".addslashes($_SESSION['editCart']['totalAdult'])."',
				  `total_infants`='".addslashes($_SESSION['editCart']['totalInfant'])."',
				  `total_child`='".addslashes($_SESSION['editCart']['totalChild'])."',

				  
				  `payment_date`='".addslashes(date('Y-m-d',strtotime($_POST['payment_date'])))."',
				  `checkin`='".addslashes(date('Y-m-d',strtotime($_POST['checkin'])))."',
				  `checkout`='".addslashes(date('Y-m-d',strtotime($_POST['checkin'])))."',
				  `no_of_days`='".addslashes($_SESSION['editCart']['noOfDays'])."',
				  `amendment_remarks_id`='".addslashes($_REQUEST['amendment_remarks_id'])."',
				  `segment_id`='".addslashes($_POST['segment'])."',
				  `series_id`='".addslashes($_SESSION['editCart']['series'])."',
				  `operator_id`='".addslashes($_SESSION['editCart']['operator'])."',
				  `cancellation_reason_id`='".addslashes($_POST['CancellationReason_status'])."',
				  `type`='L',
				  `date_created`='".currenDateTime()."',
				  `last_modified`='".currenDateTime()."',
				  `last_modified_by`='".$_SESSION['userId']."'
				 ";
				  				  
executeSql($AmendmentSQL);

			  
}else if(($_POST['Save'] == 'Edit') && !empty($_POST['eId'])){//update


$updateOrder = "UPDATE `".TBL_ORDERS."` SET 
				  `code`=code+01,
				  `id_lang`='1',
				  `id_currency`='1',
				  `id_hotel`='".addslashes($_POST['hotel_id'])."',
				  `id_customer`='".addslashes($_POST['id_guest'])."',
				  `id_company`='".addslashes($_POST['id_company'])."',
				  `id_company_person`='".addslashes($_POST['id_contacts'])."',		
				  `id_rate`='".addslashes($_SESSION['editCart']['rate_id'])."',  
				  `payment_status`='".addslashes($_POST['payment_status'])."',
				  `booking_status`='".addslashes($_POST['booking_status'])."',
				  `conversion_rate`='1.000000',
				  `discount_type`='".addslashes($_SESSION['editCart']['discountType'])."',
				  `discount_var`='".addslashes($_SESSION['editCart']['discountVar'])."',
				  `tarrif_price`='".addslashes($_SESSION['editCart']['totalPriceTarrif'])."',
				  `food_price`='".addslashes($_SESSION['editCart']['totalPriceFood'])."',
				  `extra_price`='".addslashes($_SESSION['editCart']['totalPriceExtra'])."',
				  `total_discounts`='".addslashes($_SESSION['editCart']['discountPrice'])."',
				  `subtotal`='".addslashes($_SESSION['editCart']['totalPrice'])."',
				  `total_tax`='".addslashes($_SESSION['editCart']['taxablePrice'])."',
 				  `lunch_type`='".addslashes($_REQUEST['meals_type'])."',
  				  `lunch_tax_percentage`='".addslashes($_SESSION['editCart']['lunch_tax_percentage'])."',
				  
				  `total_price`='".addslashes(round((($_SESSION['editCart']['totalPrice']-$_SESSION['editCart']['discountPrice'])+$_SESSION['editCart']['taxablePrice']),0,PHP_ROUND_HALF_UP))."',
				   `balance`='".addslashes($_SESSION['editCart']['totalPrice']+$_SESSION['editCart']['taxablePrice']-$_SESSION['editCart']['discountPrice']-$_REQUEST['amount_received'])."',
				  `amount_received`='".addslashes($_REQUEST['amount_received'])."',
				  `total_products`='".addslashes($_SESSION['editCart']['totalRoom'])."',
				  `total_adults`='".addslashes($_SESSION['editCart']['totalAdult'])."',
				  `total_infants`='".addslashes($_SESSION['editCart']['totalInfant'])."',
				  `total_child`='".addslashes($_SESSION['editCart']['totalChild'])."',
				  `payment_date`='".addslashes(date('Y-m-d',strtotime($_POST['payment_date'])))."',
				  `checkin`='".addslashes(date('Y-m-d',strtotime($_POST['checkin'])))."',
				  `checkout`='".addslashes(date('Y-m-d',strtotime($_POST['checkin'])))."',
				  `no_of_days`='".addslashes($_SESSION['editCart']['noOfDays'])."',
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
				  `amendment_remarks_id`='".addslashes($_REQUEST['amendment_remarks_id'])."',
				  `valid`='1',
				  `segment_id`='".addslashes($_POST['segment'])."',
				  `series_id`='".addslashes($_POST['series'])."',
				  `operator_id`='".addslashes($_POST['operator'])."',
				  `last_modified`='".currenDateTime()."',
				  `last_modified_by`='".$_SESSION['userId']."' 
				  where id_order='".addslashes(encryptor('decrypt',$_POST['eId']))."'";
		executeSql($updateOrder);
		
		
/*====================================Amendment count Query Insert Start=======================================*/

$AmendmentSQL = "INSERT INTO  `".TBL_AMENDMENT_COUNT."` SET 	
 				  `id_order`='".addslashes(encryptor('decrypt',$_POST['eId']))."',			 
 				  `id_shop` = '".addslashes($_SESSION['shop'])."',				  
				  `id_hotel`='".addslashes($_POST['hotel_id'])."',
				  `id_customer`='".addslashes($_POST['id_guest'])."',
				  `id_company`='".addslashes($_POST['id_company'])."',
				  `id_company_person`='".addslashes($_POST['id_contacts'])."',				
				  `id_executive`='".addslashes($resultCompanyArea->user_id)."',							  
				  `payment_status`='".addslashes($_POST['payment_status'])."',
				  `booking_status`='".addslashes($_POST['booking_status'])."',

				  `discount_type`='".addslashes($_SESSION['editCart']['discountType'])."',
				  `discount_var`='".addslashes($_SESSION['editCart']['discountVar'])."',
				  `tarrif_price`='".addslashes($_SESSION['editCart']['totalPriceTarrif'])."',
				  `food_price`='".addslashes($_SESSION['editCart']['totalPriceFood'])."',
				  `extra_price`='".addslashes($_SESSION['editCart']['totalPriceExtra'])."',
				  `total_discounts`='".addslashes($_SESSION['editCart']['discountPrice'])."',
				  `subtotal`='".addslashes($_SESSION['editCart']['totalPrice'])."',
				  `total_tax`='".addslashes($_SESSION['editCart']['taxablePrice'])."',
				  `lunch_type`='".addslashes($_REQUEST['meals_type'])."',
  				  `lunch_tax_percentage`='".addslashes($_SESSION['editCart']['lunch_tax_percentage'])."',
				 `total_price`='".addslashes(round((($_SESSION['editCart']['totalPrice']-$_SESSION['editCart']['discountPrice'])+$_SESSION['editCart']['taxablePrice']),0,PHP_ROUND_HALF_UP))."',
				    `balance`='".addslashes($_SESSION['editCart']['totalPrice']+$_SESSION['editCart']['taxablePrice']-$_SESSION['editCart']['discountPrice']-$_REQUEST['amount_received'])."',
				  `amount_received`='".addslashes($_REQUEST['amount_received'])."',
				  `total_products`='".addslashes($_SESSION['editCart']['totalRoom'])."',
				  `total_adults`='".addslashes($_SESSION['editCart']['totalAdult'])."',
				  `total_infants`='".addslashes($_SESSION['editCart']['totalInfant'])."',
				  `total_child`='".addslashes($_SESSION['editCart']['totalChild'])."',

				  
				  `payment_date`='".addslashes(date('Y-m-d',strtotime($_POST['payment_date'])))."',
				  `checkin`='".addslashes(date('Y-m-d',strtotime($_POST['checkin'])))."',
				  `checkout`='".addslashes(date('Y-m-d',strtotime($_POST['checkin'])))."',
				  `no_of_days`='".addslashes($_SESSION['editCart']['noOfDays'])."',
				  `amendment_remarks_id`='".addslashes($_REQUEST['amendment_remarks_id'])."',
				  `segment_id`='".addslashes($_POST['segment'])."',
				  `series_id`='".addslashes($_SESSION['editCart']['series'])."',
				  `operator_id`='".addslashes($_SESSION['editCart']['operator'])."',
				  `cancellation_reason_id`='".addslashes($_POST['CancellationReason_status'])."',
				  `type`='L',
				  `date_created`='".currenDateTime()."',
				  `last_modified`='".currenDateTime()."',
				  `last_modified_by`='".$_SESSION['userId']."'
				 ";
				  				  
executeSql($AmendmentSQL);


		}
	}
}
	  

?>

   
				
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> Lunch Booking Manager <small>Lunch Book Now</small> </h1>
    <ol class="breadcrumb">
      <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Lunch Booking </li>
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
          <h3 class="box-title">  <i class="fa fa-hotel"></i> Lunch Booking Process Updated Successfully </h3>
        </div>
      <!-- /.box-header -->
	   <!-- /.first section -->
	 
		<!-- /.eighth section end-->
		
		
		<?php //echo $content;
		
		//$_SESSION['editCart']['content'] = $content;
		 ?>
		
        <!-- this row will not appear when printing -->
		 <br/>
		<?php if(!empty($_POST['eId'])){ ?>
        <div class="row no-print">		
		<div class="col-sm-10">
            <a href="mail-template/sendOrderMail.php?id=<?=$_POST['eId']?>" target="_blank" class="btn btn-primary pull-right" style="margin-right: 5px;">
            <i class="fa fa-download"></i> Send Mail
          </a>
          </div>	
          <div class="col-sm-2">
            <a href="pdf-template/generateOrderPdf.php?id=<?=$_POST['eId']?>" target="_blank" class="btn btn-primary pull-right" style="margin-right: 5px;">
            <i class="fa fa-download"></i> Generate PDF
          </a>
          </div>	
        </div>
		<?php }else{ ?>
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
		<?php } ?>
        <br/>        <br/>

      </section> 
    </div>
    <!-- /.row -->
  </section>
  <!-- /.content -->
</div>
<?php 
include_once("includes/footer.php");
unset($_SESSION['editCart']);
?>
<script>


</script>
