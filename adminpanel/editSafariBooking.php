<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_SAFARI_BOOKING,'view');

/////////////////////////////////////////////////////////////////////////////////////
//---------------------------------------------------------------------------------------------------------
if($_POST['Save']){
	
	$err = 0;
	if(empty($_POST['safari_time'])){
		$err++;
		$err_safari_time = '<font style="color:red;font-weight:normal;" ><br>Please enter safari time.</font>';
	}
	if(empty($_POST['safari_date'])){
		$err++;
		$err_safari_date = '<font style="color:red;font-weight:normal;" ><br>Please enter safari date.</font>';
	}
	if(empty($_POST['id_guest'])){
		$err++;
		$err_id_guest = '<font style="color:red;font-weight:normal;" ><br>Please select guest.</font>';
	}
	$reservationDate = explode(' to ',$_POST['reservation_date']);
	$checkin = date ("Y-m-d", strtotime($reservationDate[0]));
	$checkout = date ("Y-m-d", strtotime($reservationDate[1]));
	
	if($err == 0){//No error
		if(($_POST['Save'] == 'Add') && empty($_POST['id'])){//add
			checkUserLevelPermission($_SESSION['userLevel'],TBL_SAFARI_BOOKING,'add');
			
			$lastRecordRes = executeSql("SELECT AUTO_INCREMENT as maxId FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = '".TBL_SAFARI_BOOKING."' and TABLE_SCHEMA='".$DB_NAME."'");
			$lastRecordRow = $db->fetch_object2($lastRecordRes);
			$newId = sprintf("%'03d", ($lastRecordRow->maxId));
			$safariCode = 'Safari'.$newId;
			
			$addSql = "   	INSERT INTO `".TBL_SAFARI_BOOKING."` SET 
							`safari_code` = '".$safariCode."',
							`sub_code` = '01',
							`id_shop_group` = '1',
							`id_shop` = '".addslashes($_SESSION['shop'])."',		
							`id_company` = '".addslashes($_REQUEST['id_company'])."',
							`id_contacts` = '".addslashes($_REQUEST['id_contacts'])."',	
							`id_guest` = '".addslashes($_POST['id_guest'])."',		
							`checkin` = '".addslashes($checkin)."',		
							`checkout` = '".addslashes($checkout)."',							
							`safari_date` = '".addslashes(date('Y-m-d',strtotime($_POST['safari_date'])))."',
							`safari_time` = '".addslashes(date('H:i:s',strtotime($_POST['safari_time'])))."',
							`pax` = '".addslashes($_POST['pax'])."',
							`mode` = '".addslashes($_POST['mode'])."',
							`total_amount` = '".addslashes($_POST['total_amount'])."',
							`advance_amount` = '".addslashes($_POST['advance_amount'])."',
							`payment_status` = '".addslashes($_POST['payment_status'])."',
							`payment_date` = '".addslashes(date('Y-m-d',strtotime($_POST['payment_date'])))."',
							`booking_type` = '".addslashes($_POST['booking_type'])."',
							`booking_status` = '".addslashes($_POST['booking_status'])."',
							`comments` = '".addslashes($_POST['comments'])."'";
			
			$addSql .= "	,`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`last_modified_by` = '".$_SESSION['userId']."'
							,`status` = '1'";
			if(executeSql($addSql)){
				$lastInsertId = $db->insert_id();
				
				$countGuest = count($_POST['name']);	
				
				for($i=0;$i<$countGuest;$i++){
					$addSqlDetails = "   	INSERT INTO `".TBL_SAFARI_DETAILS."` SET 
										`safari_id` = '".$lastInsertId ."',
										`name` = '".addslashes($_POST['name'][$i])."',
										`age` = '".addslashes($_POST['age'][$i])."',	
										`gender` = '".addslashes($_POST['gender'][$i])."',
										`nationality` = '".addslashes($_POST['id_country'][$i])."',
										`id_number` = '".addslashes($_POST['id_number'][$i])."',
										`id_option` = '".addslashes($_POST['id_option'][$i])."',
										`status` = '1',
										`date_created` = '".currenDateTime()."',
										`last_modified` = '".currenDateTime()."',
										`last_modified_by` = '".$_SESSION['userId']."'";
						executeSql($addSqlDetails);
					}
			
				$_SESSION['successMsg'] = 'New Safari Booking details has been added sucessfully.';
				header("location:manageSafariBooking.php?page=".$_REQUEST['page']);
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = 'New Safari Booking details has not been saved. Please make corrections below.';
			}
		}else if(($_POST['Save'] == 'Edit') && !empty($_POST['id'])){//update
		
			checkUserLevelPermission($_SESSION['userLevel'],TBL_SAFARI_BOOKING,'update');
			
			$lastRecordRes = executeSql("Select sub_code as maxId from  `".TBL_SAFARI_BOOKING."` where `id_safari` = '".addslashes($_POST['id'])."'");
			$lastRecordRow = $db->fetch_object2($lastRecordRes);
			$subCode = sprintf("%'02d", ($lastRecordRow->maxId+1));
				
			$editSql = "   	UPDATE `".TBL_SAFARI_BOOKING."` SET 
							`sub_code` = '".$subCode."',
							`id_shop_group` = '1',
							`id_shop` = '".addslashes($_SESSION['shop'])."',		
							`id_company` = '".addslashes($_REQUEST['id_company'])."',
							`id_contacts` = '".addslashes($_REQUEST['id_contacts'])."',	
							`id_guest` = '".addslashes($_POST['id_guest'])."',	
							`checkin` = '".addslashes($checkin)."',		
							`checkout` = '".addslashes($checkout)."',								
							`safari_date` = '".addslashes(date('Y-m-d',strtotime($_POST['safari_date'])))."',
							`safari_time` = '".addslashes(date('H:i:s',strtotime($_POST['safari_time'])))."',
							`pax` = '".addslashes($_POST['pax'])."',
							`mode` = '".addslashes($_POST['mode'])."',
							`total_amount` = '".addslashes($_POST['total_amount'])."',
							`advance_amount` = '".addslashes($_POST['advance_amount'])."',
							`payment_status` = '".addslashes($_POST['payment_status'])."',
							`payment_date` = '".addslashes(date('Y-m-d',strtotime($_POST['payment_date'])))."',
							`booking_type` = '".addslashes($_POST['booking_type'])."',
							`booking_status` = '".addslashes($_POST['booking_status'])."',
							`comments` = '".addslashes($_POST['comments'])."'";
			
			$editSql .= "	,`last_modified` = '".currenDateTime()."'
							,`status` = '1'
							,`last_modified_by` = '".$_SESSION['userId']."'
							WHERE `id_safari` = '".addslashes(encryptor('decrypt',$_POST[id]))."'";								
			if(executeSql($editSql)){	
										
					executeSql("DELETE FROM `".TBL_SAFARI_DETAILS."` where `safari_id` = '".addslashes(encryptor('decrypt',$_POST[id]))."'");
					$countGuest = count($_POST['name']);	
				
				for($i=0;$i<$countGuest;$i++){	
					$addSqlDetails = "   INSERT INTO `".TBL_SAFARI_DETAILS."` SET 
										`safari_id` = '".addslashes($_REQUEST['id'])."',
										`name` = '".addslashes($_POST['name'][$i])."',
										`age` = '".addslashes($_POST['age'][$i])."',	
										`gender` = '".addslashes($_POST['gender'][$i])."',
										`nationality` = '".addslashes($_POST['id_country'][$i])."',
										`id_number` = '".addslashes($_POST['id_number'][$i])."',
										`id_option` = '".addslashes($_POST['id_option'][$i])."',
										`status` = '1',
										`date_created` = '".currenDateTime()."',
										`last_modified` = '".currenDateTime()."',
										`last_modified_by` = '".$_SESSION['userId']."'";
						executeSql($addSqlDetails);
					}		
				$_SESSION['successMsg'] = 'Safari Booking details has been updated sucessfully.';
				header("location:manageSafariBooking.php?page=".$_REQUEST['page']);
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = 'Safari Booking details has not been saved.Please make corrections below.';
			}
		}
	}else{//Error
		$err++;
		$_SESSION['errorMsg'] = 'Safari Booking details has not been saved. Please make corrections.';
	}
}
// ----------cate---------
if(!empty($_REQUEST['id']) && $_REQUEST['action']=='edit'){
	$sql = "  SELECT * FROM `".TBL_SAFARI_BOOKING."`
								WHERE `id_safari` = '".addslashes(encryptor('decrypt',$_REQUEST[id]))."' AND `id_shop` = '".addslashes($_SESSION['shop'])."'";
	$db->query($sql);
	if($db->num_rows() > 0){
		$row = $db->fetch_object();
	}	
						
}	

?>
<?php include_once("includes/header.php")?>
<?php include_once("includes/left.php")?>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> Hotel Booking Manager <small>Manage Safari Booking</small> </h1>
    <ol class="breadcrumb">
      <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Manage Safari Booking</li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="row">
      <!-- left column -->
      <div class="col-md-12">
        <!-- general form elements -->
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title"><?php echo $_REQUEST['id']==''?'Add':'Edit'?> Safari Booking</h3>
          </div>
          <!-- /.box-header -->
          <!-- form start -->
          <form name="form1"  method="post" enctype="multipart/form-data" role="form" data-parsley-validate  autocomplete="off">
            <input type="hidden" value="<?php echo $_REQUEST['id'];?>" name="id" />
            <div class="form-group has-error" align="center">
              <?php if($_SESSION['errorMsg']){?>
              <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
              <?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
              <p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
              <?php unset($_SESSION['successMsg']);}?>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="form-group col-sm-8">
                  <label for="id_guest" >Guest Name</label>
                  <div class="input-group" id="showGuest">
                    <select class="form-control select2" name="id_guest" id="id_guest" data-parsley-errors-container="#guestError" >
                      <option value="">Select Guest</option>
                      <?php 
									$resCat = selectSql(TBL_CUSTOMER,"where status='1' and type='1' and id_shop='".addslashes($_SESSION['shop'])."' ",' ORDER BY `first_name`');
												  if(num_rows($resCat)){
													while($resultCat = $db->fetch_object2($resCat)){
														if($_REQUEST['id_guest'] == $resultCat->id_customer){
															$selected = 'selected="selected"';
														}elseif($row->id_guest == $resultCat->id_customer){
														$selected = 'selected="selected"';
														}else{
															$selected = '';
														}
														$guestDropDown .= '<option '.$selected.' value="'.$resultCat->id_customer.'">Name : '.ucfirst($resultCat->first_name).' '.ucfirst($resultCat->last_name).' | Email : '.$resultCat->email.' | Mobile : '.$resultCat->mobile.'</option>';
													}
												  }
												  echo $guestDropDown;
									
									 ?>
                    </select>
                    <div class="input-group-addon guest_open"> <i class="fa fa-plus"></i> </div>
                  </div>
                  <span id="guestError"><?php echo $err_id_guest; ?></span> </div>
                <div class="form-group col-sm-4">
                  <label for="reservation_date">Checkin Date - Checkout Date </label>
                  <div class="input-group">
                    <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                    <input type="text" class="form-control pull-right dateRange" id="reservation_date" name="reservation_date" data-parsley-required value="<?php if($_SESSION['reservation_date'] !='' ){echo $_SESSION['reservation_date'];} ?>" data-parsley-errors-container="#reservation_dateError"  automcomplete="off">
                  </div>
                  <!-- /.input group -->
                  <span id="reservation_dateError"></span> </div>
              </div>
              <div class="row">
                <div class="form-group col-sm-4">
                  <label for="safari_date">Safari Date</label>
                  <div class="input-group">
                    <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                    <input type="text" class="form-control pickerdate" placeholder="Enter safari date" id="safari_date" name="safari_date" value="<?php if($_POST) echo $_POST['safari_date'];else if($row->safari_date)  echo date('d-m-Y',strtotime(stripslashes($row->safari_date))); else echo date('d-m-Y');?>">
                  </div>
                  <?php echo $err_safari_date;?> </div>
                <div class="form-group col-sm-4">
                  <label for="safari_time" >Safari Time</label>
                  <div class="input-group">
                    <div class="input-group-addon"> <i class="fa fa-clock-o"></i> </div>
                    <input type="text" class="form-control pickertime" id="safari_time" name="safari_time"  value="" data-parsley-required>
                  </div>
                  <?php echo $err_safari_time;?> </div>
                <div class="form-group col-sm-4">
                  <label for="pax">Pax</label>
                  <input type="text" class="form-control" placeholder="Enter pax" id="pax" name="pax" value="<?php if($_POST) echo $_POST['pax'];else echo stripslashes($row->pax);?>" data-parsley-required>
                  <?php echo $err_pax;?> </div>
              </div>
              <div class="row">
                <div class="form-group col-sm-4">
                  <label for="mode">Mode<font color="#FF0000">*</font></label>
                  <select class="form-control" name="mode" id="mode" data-parsley-errors-container="#modeError" data-parsley-required>
                    <option value="1" <?php if($_REQUEST['mode']=='1'){echo 'selected="selected"';}elseif($row->mode=='1'){echo 'selected="selected"';} ?>>Jeep</option>
                    <option value="2" <?php if($_REQUEST['mode']=='2'){echo 'selected="selected"';}elseif($row->mode=='2'){echo 'selected="selected"';} ?>>Canter</option>
                  </select>
                  <span id="modeError"><?php echo $err_mode;?></span> </div>
                <div class="form-group col-sm-4">
                  <label for="booking_type">Booking Type<font color="#FF0000">*</font></label>
                  <select class="form-control" name="booking_type" id="booking_type" data-parsley-errors-container="#booking_typeError" data-parsley-required>
                    <option value="1" <?php if($_REQUEST['booking_type']=='1'){echo 'selected="selected"';}elseif($row->booking_type=='1'){echo 'selected="selected"';} ?>>Sharing</option>
                    <option value="2" <?php if($_REQUEST['booking_type']=='2'){echo 'selected="selected"';}elseif($row->booking_type=='2'){echo 'selected="selected"';} ?>>Exclusive</option>
                  </select>
                  <span id="booking_typeError"><?php echo $err_booking_type;?></span> </div>
                <div class="form-group col-sm-4">
                  <label for="comments">Comments<font color="#FF0000">*</font></label>
                  <textarea class="form-control" name="comments" id="comments"  rows="1" placeholder="Enter comments" ><?php if($_POST) echo $_POST['comments'];else echo stripslashes($row->comments);?>
</textarea>
                  <?php echo $err_comments;?> </div>
              </div>
              <div class="row">
                <div class="form-group col-sm-4">
                  <label for="id_company" > Booker Company</label>
                  <select class="form-control select2" name="id_company" id="id_company" data-parsley-errors-container="#companyError" data-parsley-required onchange="getContact(this.value,'');">
                    <option value="">Select Company</option>
                    <?php 
									$resCat = selectSql(TBL_COMPANY,"where status='1'  and id_shop='".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`');
												  if(num_rows($resCat)){
													while($resultCat = $db->fetch_object2($resCat)){
														if($_REQUEST['id_company'] == $resultCat->id_company){
															$selected = 'selected="selected"';
														}elseif($row->id_company == $resultCat->id_company){
														$selected = 'selected="selected"';
														}else{
															$selected = '';
														}
														$companyDropDown .= '<option '.$selected.' value="'.$resultCat->id_company.'">'.ucfirst($resultCat->name).'</option>';
													}
												  }
												  echo $companyDropDown;
									
									 ?>
                  </select>
                  <span id="companyError"></span> </div>
                <div class="form-group col-sm-4">
                  <label for="id_contacts" >Booking By</label>
                  <select class="form-control select2" name="id_contacts" id="id_contacts" data-parsley-errors-container="#contactError" data-parsley-required >
                    <option value="">Please Select Company</option>
                  </select>
                  <span id="contactError"></span> </div>
                <div class="form-group col-sm-4">
                  <label for="booking_status" >Booking Status</label>
                  <select class="form-control select2" name="booking_status" id="booking_status" data-parsley-errors-container="#booking_statusError" data-parsley-required>
                    <option value="">Select Booking Status</option>
                    <?php 
									$resCat = selectSql(TBL_HTL_BOOKING_STATUS,"where safari_status='1' ",' ORDER BY `name`');
												  if(num_rows($resCat)){
													while($resultCat = $db->fetch_object2($resCat)){
														if($_REQUEST['booking_status'] == $resultCat->id){
															$selected = 'selected="selected"';
														}elseif($row->booking_status == $resultCat->id){
														$selected = 'selected="selected"';
														}else{
															$selected = '';
														}
														$bookStatusDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
													}
												  }
												  echo $bookStatusDropDown;
									
									 ?>
                  </select>
                  <span id="booking_statusError"></span> </div>
              </div>
              <div class="row">
                <div class="form-group col-sm-3">
                  <label for="total_amount">Total Amount</label>
                  <div class="input-group"><span class="input-group-addon"><i class="fa fa-inr"></i> </span>
                    <input type="text" class="form-control" placeholder="Enter total amount" id="total_amount" name="total_amount" value="<?php if($_POST) echo $_POST['total_amount'];else echo stripslashes($row->total_amount);?>" data-parsley-required data-parsley-type="digits">
                  </div>
                  <?php echo $err_total_amount;?> </div>
                <div class="form-group col-sm-3">
                  <label for="payment_status" >Payment Status</label>
                  <?php 
								$paymentStatusDropDown = '<select name="payment_status" id="payment_status" class="form-control select2" data-parsley-required data-parsley-errors-container="#paymentStatusError">
									 	 <option value="">Select Payment status</option>';
											  $resCat = selectSql(TBL_ORDER_STATE,"where id_lang='1' ",' ORDER BY `id_order_state`');
											  if(num_rows($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($_REQUEST['payment_status'] == $resultCat->id_order_state){
														$selected = 'selected="selected"';
													}else if($row->payment_status == $resultCat->id_order_state){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$paymentStatusDropDown .= '<option '.$selected.' value="'.$resultCat->id_order_state.'">'.ucfirst($resultCat->name).'</option>';
												}
											  }
											 	echo $paymentStatusDropDown .= '</select>';
									
									 ?>
                  <span id="paymentStatusError"></span> </div>
                <div class="form-group col-sm-3">
                  <label for="advance_amount">Amount Received</label>
                  <div class="input-group"><span class="input-group-addon"><i class="fa fa-inr"></i> </span>
                    <input type="text" class="form-control" placeholder="Enter Amount Received" id="advance_amount" name="advance_amount" value="<?php if($_POST) echo $_POST['advance_amount'];else echo stripslashes($row->advance_amount);?>" data-parsley-required data-parsley-type="digits">
                  </div>
                  <?php echo $err_advance_amount;?> </div>
                <div class="form-group col-sm-3">
                  <label for="payment_date">Payment Date</label>
                  <div class="input-group">
                    <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                    <input type="text" class="form-control pickerdate" placeholder="Enter payment date" id="payment_date" name="payment_date" value="<?php if($_POST) echo $_POST['payment_date'];else if($row->payment_date)  echo date('d-m-Y',strtotime($row->payment_date)); else echo date('d-m-Y');?>">
                  </div>
                  <?php echo $err_payment_date;?> </div>
              </div>
              <hr>
              <?php  $sqlDetail = executeSql("SELECT * FROM `".TBL_SAFARI_DETAILS."` WHERE safari_id='".addslashes($row->id_safari)."'");
			 			 
			  			 $counter = 1;
						 if(num_rows($sqlDetail) >0){
						 	 while($rowDetail = $db->fetch_object2($sqlDetail)){
			 
			   ?>
              <div class="row cloneMore" id="cloneDetail<?php echo $counter; ?>">
                <div class="form-group col-sm-2">
                  <label for="name">Name</label>
                  <input type="text" class="form-control" placeholder="Enter name" id="name" name="name[]" value="<?php if($_POST) echo $_POST['name'];else echo stripslashes($rowDetail->name);?>" data-parsley-required >
                  <?php echo $err_name;?> </div>
                <div class="form-group col-sm-1">
                  <label for="age">Age</label>
                  <input type="text" class="form-control" placeholder="Age" id="age" name="age[]" value="<?php if($_POST) echo $_POST['age'];else echo stripslashes($rowDetail->age);?>" data-parsley-required  data-parsley-type="digits">
                  <?php echo $err_age;?> </div>
                <div class="form-group col-sm-2">
                  <label for="gender" >Gender</label>
                  <select class="form-control" name="gender[]" id="gender" data-parsley-errors-container="#gender" data-parsley-required >
                    <option <?php if($_POST['gender'] =='1') echo 'selected="selected"'; elseif($rowDetail->gender==1)  echo 'selected="selected"';  ?> value="1">Male</option>
                    <option <?php if($_POST['gender'] =='2') echo 'selected="selected"'; elseif($rowDetail->gender==2)  echo 'selected="selected"';  ?> value="2">Female</option>
                  </select>
                  <span id="gender"></span> </div>
                <div class="form-group col-sm-2">
                  <label for="id_country" >Nationality</label>
                  <select class="form-control" name="id_country[]" id="id_country" data-parsley-required >
                    <option value="">Select Country</option>
                    <?php 
									$resCat = selectSql(TBL_COUNTRY_LANG,"where id_lang='1' ",' ORDER BY `name`');
												  if(num_rows($resCat)){
													while($resultCat = $db->fetch_object2($resCat)){
														if($_REQUEST['id_country'] == $resultCat->id_country){
															$selected = 'selected="selected"';
														}elseif($rowDetail->nationality == $resultCat->id_country){
														$selected = 'selected="selected"';
														}else{
															$selected = '';
														}
														$countryDropDown .= '<option '.$selected.' value="'.$resultCat->id_country.'">'.ucfirst($resultCat->name).'</option>';
													}
												  }
												  echo $countryDropDown;
									
									 ?>
                  </select>
                  <span id="countryError"></span> </div>
                <div class="form-group col-sm-2">
                  <label for="id_option" >Id Type</label>
                  <select class="form-control" name="id_option[]" id="id_option"  data-parsley-required >
                    <option  value="" >Select Type</option>
                    <option  <?php if($_POST['id_option'] =='Passport') echo 'selected="selected"'; elseif($rowDetail->id_option=='Passport')  echo 'selected="selected"';  ?> value="Passport">Passport</option>
                    <option <?php if($_POST['id_option'] =='Aadhar') echo 'selected="selected"'; elseif($rowDetail->id_option=='Aadhar')  echo 'selected="selected"';  ?> value="Aadhar">Aadhar </option>
                    <option <?php if($_POST['id_option'] =='PAN') echo 'selected="selected"'; elseif($rowDetail->id_option=='PAN')  echo 'selected="selected"';  ?> value="PAN">PAN </option>
                    <option <?php if($_POST['id_option'] =='DL') echo 'selected="selected"'; elseif($rowDetail->id_option=='DL')  echo 'selected="selected"';  ?> value="DL">DL </option>
                    <option <?php if($_POST['id_option'] =='Other') echo 'selected="selected"'; elseif($rowDetail->id_option=='Other')  echo 'selected="selected"';  ?> value="Other">Other </option>
                  </select>
                  <span id="id_optionError"></span> </div>
                <div class="form-group col-sm-2">
                  <label for="id_number">Id Number</label>
                  <input type="text" class="form-control" placeholder="Id number" id="id_number" name="id_number[]" value="<?php if($_POST) echo $_POST['id_number'];else echo stripslashes($rowDetail->id_number);?>" data-parsley-required >
                  <?php echo $err_id_number;?> </div>
				
                <div class="form-group col-sm-1" id="addMore"> 
				
				<?php if($counter==1){ ?>
				<a id="clone" class="btn btn-danger pull-left" href="javascript:void(0);"  style="margin-top:25px;"><i class="fa fa-plus-circle fa-lg"></i></a>
				<?php }else{ ?> 
					<a id="remove" class="btn btn-danger pull-left" href="javascript:void(0);" style="margin-top:25px;" onclick="$(this).parent('div').parent('div').remove();"><i class="fa fa-trash fa-lg"></i></a>
				<?php } ?>
				</div>
				
				
				
				
              </div>
             
              <?php $counter++;	} 
			  	
				}else { ?>
				<div class="row cloneMore" id="cloneDetail1">
                <div class="form-group col-sm-2">
                  <label for="name">Name</label>
                  <input type="text" class="form-control" placeholder="Enter name" id="name" name="name[]" value="<?php if($_POST) echo $_POST['name'];else echo stripslashes($rowDetail->name);?>" data-parsley-required >
                  <?php echo $err_name;?> </div>
                <div class="form-group col-sm-1">
                  <label for="age">Age</label>
                  <input type="text" class="form-control" placeholder="Age" id="age" name="age[]" value="<?php if($_POST) echo $_POST['age'];else echo stripslashes($rowDetail->age);?>" data-parsley-required  data-parsley-type="digits">
                  <?php echo $err_age;?> </div>
                <div class="form-group col-sm-2">
                  <label for="gender" >Gender</label>
                  <select class="form-control" name="gender[]" id="gender" data-parsley-errors-container="#gender" data-parsley-required >
                    <option <?php if($_POST['gender'] =='1') echo 'selected="selected"'; elseif($rowDetail->gender==1)  echo 'selected="selected"';  ?> value="1">Male</option>
                    <option <?php if($_POST['gender'] =='2') echo 'selected="selected"'; elseif($rowDetail->gender==2)  echo 'selected="selected"';  ?> value="2">Female</option>
                  </select>
                  <span id="gender"></span> </div>
                <div class="form-group col-sm-2">
                  <label for="id_country" >Nationality</label>
                  <select class="form-control" name="id_country[]" id="id_country" data-parsley-required >
                    <option value="">Select Country</option>
                    <?php 
									$resCat = selectSql(TBL_COUNTRY_LANG,"where id_lang='1' ",' ORDER BY `name`');
												  if(num_rows($resCat)){
													while($resultCat = $db->fetch_object2($resCat)){
														if($_REQUEST['id_country'] == $resultCat->id_country){
															$selected = 'selected="selected"';
														}elseif($rowDetail->nationality == $resultCat->id_country){
														$selected = 'selected="selected"';
														}else{
															$selected = '';
														}
														$countryDropDown .= '<option '.$selected.' value="'.$resultCat->id_country.'">'.ucfirst($resultCat->name).'</option>';
													}
												  }
												  echo $countryDropDown;
									
									 ?>
                  </select>
                  <span id="countryError"></span> </div>
                <div class="form-group col-sm-2">
                  <label for="id_option" >Id Type</label>
                  <select class="form-control" name="id_option[]" id="id_option"  data-parsley-required >
                    <option  value="" >Select Type</option>
                    <option  <?php if($_POST['id_option'] =='Passport') echo 'selected="selected"'; elseif($rowDetail->id_option=='Passport')  echo 'selected="selected"';  ?> value="Passport">Passport</option>
                    <option <?php if($_POST['id_option'] =='Aadhar') echo 'selected="selected"'; elseif($rowDetail->id_option=='Aadhar')  echo 'selected="selected"';  ?> value="Aadhar">Aadhar </option>
                    <option <?php if($_POST['id_option'] =='PAN') echo 'selected="selected"'; elseif($rowDetail->id_option=='PAN')  echo 'selected="selected"';  ?> value="PAN">PAN </option>
                    <option <?php if($_POST['id_option'] =='DL') echo 'selected="selected"'; elseif($rowDetail->id_option=='DL')  echo 'selected="selected"';  ?> value="DL">DL </option>
                    <option <?php if($_POST['id_option'] =='Other') echo 'selected="selected"'; elseif($rowDetail->id_option=='Other')  echo 'selected="selected"';  ?> value="Other">Other </option>
                  </select>
                  <span id="id_optionError"></span> </div>
                <div class="form-group col-sm-2">
                  <label for="id_number">Id Number</label>
                  <input type="text" class="form-control" placeholder="Id number" id="id_number" name="id_number[]" value="<?php if($_POST) echo $_POST['id_number'];else echo stripslashes($rowDetail->id_number);?>" data-parsley-required >
                  <?php echo $err_id_number;?> </div>
				
                <div class="form-group col-sm-1" id="addMore"> 
				<a id="clone" class="btn btn-danger pull-left" href="javascript:void(0);"  style="margin-top:25px;"><i class="fa fa-plus-circle fa-lg"></i></a>
				</div>
								
              </div>
				
				
			<?php } 	?>
			  
			  
			  
			  
              <hr>
              <?php if($row->date_created){?>
              <div class="row">
                <div class="form-group col-sm-4">
                  <label for="date_created">Date Created</label>
                  <input type="text" disabled="disabled" class="form-control" id="date_created"  value="<?php echo stripslashes(dateformat($row->date_created));?>">
                </div>
                <div class="form-group col-sm-4">
                  <label for="last_modified">Last Updated</label>
                  <input type="text" disabled="disabled" class="form-control" id="last_modified" value="<?php echo stripslashes(dateformat($row->last_modified));?>">
                </div>
                <div class="form-group col-sm-4">
                  <label for="last_modified_by">Last Updated By</label>
                  <?php $sqlUserDetail = $db->fetch_obj2(selectSql(TBL_USERS,"WHERE `id` = '".$row->last_modified_by."'",''));?>
                  <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="<?php echo stripslashes($sqlUserDetail->username);?>">
                </div>
              </div>
              <?php } ?>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
              <input type='submit' value='<?=($_REQUEST['id']==''?'Add':'Edit')?>' class="btn btn-primary" name="Save" >
              &nbsp;&nbsp;&nbsp;&nbsp;
              <input type='button' value='Cancel' class="btn btn-default" onclick='location.replace("manageSafariBooking.php?page=<?php echo $_GET['page']; ?>"); '>
            </div>
          </form>
        </div>
        <!-- /.box -->
      </div>
    </div>
    <!-- /.row -->
  </section>
  <!-- /.content -->
</div>
<div id="guest" class="well">
  <form id="guestpopupform" data-parsley-validate autocomplete="off" method="post"  >
    <div class="form-group">
      <label for="first_name">First Name</label>
      <input type="text" class="form-control input-sm" placeholder="Enter first name" id="first_name" name="first_name" value="" data-parsley-required data-parsley-type="alphanum">
    </div>
    <div class="form-group">
      <label for="last_name">Last Name</label>
      <input type="text" class="form-control input-sm" placeholder="Enter last name" id="last_name" name="last_name" value="" data-parsley-required data-parsley-type="alphanum">
    </div>
    <div class="form-group">
      <label for="email" >Email Id</label>
      <input type="email" name="email" id="email" class="form-control" placeholder="Enter Email Id" data-parsley-type="email" automcomplete="off">
    </div>
    <div class="form-group">
      <label for="mobile" >Mobile No.</label>
      <input type="phone" name="mobile" id="mobile" class="form-control" placeholder="Enter mobile number" data-parsley-required data-parsley-type="digits" data-parsley-length="[10, 10]" automcomplete="off">
    </div>
    <input  type="button" class="btn btn-default" onclick="saveGuestPopupform();" value="Save">
    <button class="guest_close btn btn-default">Close</button>
  </form>
</div>
<script>




  window.onload = function() { 
  
  getContact(<?php if($_REQUEST['id_company']){echo "'".$_REQUEST['id_company']."'";}elseif($row->id_company != ''){echo "'".$row->id_company."'";}else { echo "'"."'";} ?>,<?php if($_REQUEST['id_contacts']){echo "'".$_REQUEST['id_contacts']."'";}elseif($row->id_contacts != ''){echo "'".$row->id_contacts."'";}else { echo "'"."'";} ?>);
  
  document.getElementById("safari_time").value = '<?php if($_POST) echo $_POST['safari_time'];else if($row->safari_time)  echo date('h:i a',strtotime(stripslashes($row->safari_time))); else echo ''; ?>';	
  
  
   };
   

   
</script>
<?php include_once("includes/footer.php")?>
<script>
$("#clone").click(function(){
var cloneIndex = $(".cloneMore").length;
cloneIndex++;
$("#cloneDetail1").clone().attr("id", "cloneDetail" +cloneIndex).insertAfter( "#cloneDetail1" );
$("#cloneDetail"+cloneIndex+" #name").val('');
$("#cloneDetail"+cloneIndex+" #age").val('');
$("#cloneDetail"+cloneIndex+" #id_country").val('');
$("#cloneDetail"+cloneIndex+" #id_option").val('');
$("#cloneDetail"+cloneIndex+" #id_number").val('');

$("#cloneDetail"+cloneIndex+" #addMore").html('<a id="remove" class="btn btn-danger pull-left" href="javascript:void(0);" style="margin-top:25px;" onclick="$(this).parent(\'div\').parent(\'div\').remove();" ><i class="fa fa-trash fa-lg"></i></a>');

});



</script>
