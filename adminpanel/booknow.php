<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTELS,'view');



///////unset session for differnt type of bookings /////////////////
if($_SESSION['bookCart']['type'] !=$_GET['type'] ){
	unset($_SESSION['bookCart']);	
}
//---------------------------------------------------------------------------------------------------------
?>
<?php include_once("includes/header.php")?>
<?php include_once("includes/left.php")?>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> Hotel Booking Manager <small>Book Now</small> </h1>
    <ol class="breadcrumb">
      <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Book Now</li>
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
        <h3 class="box-title"> BOOKING INFORMATION </h3>
		<a href="manageOrders.php" class="btn btn-success pull-right" >View Bookings</a>
      </div>
      <!-- /.box-header -->
      <div class="panel-body padding-0">
        <div class="row">
          <div class="col-sm-12">
            <div class="row box-border margin-right-10">
              <form method="post" action="" id="availabiltyForm" data-parsley-validate autocomplete="off">
                <div class="container">
                  <div class="form-group col-sm-3">
                    <label for="hotel_id" >Hotel</label>
                    <?php 
				$categoryDropDown = '<select name="hotel_id" id="hotel_id" class="form-control select2" onChange="getRoom(this.value,0); ajaxAddRoommsgUpdate();" data-parsley-required data-parsley-errors-container="#hotelError">
									 					  <option value="">Select Hotel</option>';
											  $resCat = selectSql(TBL_HOTELS,"where `id_shop` = '".addslashes($_SESSION['shop'])."'".$_SESSION['HotelPerHotel']." ",' ORDER BY `name`');
											  if(num_rows($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($_SESSION['bookCart']['hotel_id'] == $resultCat->id){
													   $selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
												}
											  }
											 	echo $categoryDropDown .= '</select>';
									
									 ?>
                    <span id="hotelError"></span> </div>
                  <div class="form-group col-sm-2">
                    <label for="room_id">Rooms<br />
                    </label>
                    <select class="form-control select2" name="room_id" id="room_id" data-parsley-required data-parsley-errors-container="#roomError">
                      <?php if($_SESSION['bookCart']['hotel_id']){
					$resRoom = executeSql("SELECT rt.name, ahr.hotel_id, ahr.inventory, ahr.room_id from `".TBL_ASSIGN_HOTEL_ROOM."` ahr left join `".TBL_ROOM_TYPE."` rt on ahr.room_id = rt.id where ahr.status='1' and rt.status='1' and ahr.hotel_id='".addslashes($_SESSION['bookCart']['hotel_id']	)."'");
					$roomType ='<option value="0">All Rooms</option>';
					 while($rowRoom = mysqli_fetch_object($resRoom)){	
						$roomType .= '<option value="'.$rowRoom->room_id.'">'.$rowRoom->name.'</option>';
						}
						echo $roomType;
				}else { ?>
                      <option value="" selected="">Please select hotel</option>
                      <?php } ?>
                    </select>
                    <span id="roomError"></span> </div>
                  <div class="form-group col-sm-3">
                    <label for="reservation_date">Checkin Date - Checkout Date </label>
                    <div class="input-group">
                      <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                      <input type="text" class="form-control pull-right dateRange" id="reservation_date" name="reservation_date" data-parsley-required value="<?php if($_SESSION['bookCart']['reservation_date'] !='' ){echo $_SESSION['bookCart']['reservation_date'];} ?>" data-parsley-errors-container="#reservation_dateError"  automcomplete="off">
                    </div>
                    <!-- /.input group -->
                    <span id="reservation_dateError"></span> </div>
                  <div class="form-group col-sm-1">
                    <button id="search_availabilty" name="search_availabilty" type="button" class="btn btn-primary" style="margin-top:25px;" onClick="ajaxCheckAvailability();"> <i class="fa fa-search"></i> Search </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
          <section class="content">
            <!-- /.row -->
            <div class="row">
              <div class="col-xs-12">
                <div class="box">
                  <div class="box-header">
                    <h3 class="box-title">Room Availability</h3>
                    <div class="box-tools">
                      <ul class="pagination pagination-sm no-margin pull-right">
                        <li><a href="#">&laquo;</a></li>
                        <li><a href="#">&raquo;</a></li>
                      </ul>
                    </div>
                  </div>
                  <!-- /.box-header -->
                  <div class="box-body table-responsive no-padding text-center loading" >
                    <button type="button" class="btn btn-default btn-lrg ajax" title="Ajax Request"> <i class="fa fa-spin fa-refresh"></i>&nbsp; Loading... </button>
                  </div>
                  <div class="box-body table-responsive no-padding" id="availabilty">
                    <table class="table table-hover">
                      <tr>
                        <th>Room Type</th>
                        <?php 
				  $checkinDate = date('Y-m-d');
				  echo $availableData = '<th>'.date('d M, Y', strtotime($checkinDate)).'</th>';
				  for($i =0; $i < 6; $i++){
						$checkinDate = date('d M, Y', strtotime('+1 day', strtotime($checkinDate)));
						echo $availableData = '<th>'.$checkinDate.'</th>';
					} ?>
                      </tr>
                      <tr align="center">
                        <td colspan="8" >No Data Available. Please try different Search.</td>
                      </tr>
                    </table>
                  </div>
                  <!-- /.box-body -->
                </div>
                <!-- /.box -->
              </div>
            </div>
          </section>
        </div>
      </div>
    </div>
    <!-- /.row -->
  </section>
  <section class="content">
    <!-- Default box -->
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">Create Booking</h3>
        <button type="button" class="btn btn-box-tool pull-right" data-widget="collapse" data-toggle="tooltip" title="Collapse"> <i class="fa fa-minus"></i></button>
        <div id="roomLimitMsg" align="center" style=" color:#d73925; <?php if($_SESSION['bookCart']['roomLimitMsg']==''){echo 'display:none;';} ?>"> <?php echo $_SESSION['roomLimitMsg'];?> </div>
        <div id="roomLimitMsgRoomType" align="center" style=" color:#f39c12; <?php if($_SESSION['bookCart']['roomLimitMsgRoomType']==''){echo 'display:none;';} ?>"> <?php echo $_SESSION['bookCart']['roomLimitMsgRoomType'];?> </div>
      </div>
      <div class="box-body table-responsive no-padding" >
        <form method="post" action="booknow-step2.php?type=<?php echo $_GET['type']; ?>" id="addRoomForm" data-parsley-validate autocomplete="off">
			<input type="hidden" name="book_type" id="book_type" value="<?php echo $_GET['type']; ?>" />
		<?php if($_GET['type']=='S' || $_SESSION['bookCart']['type'] == 'S'){ ?>
			<div class="form-group col-sm-4">
							 <label for="segment" >Series Name</label>  
								<?php 
								$seriesDropDown = '<select name="series" id="series" class="form-control select2" data-parsley-errors-container="#seriesError" onchange="getSeriesOperator();">
									 	 <option value="">Select Series</option>';
											  $resCat = selectSql(TBL_SERIES_MASTER,"where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."'",' ORDER BY `id`');
											  if(num_rows($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($_SESSION['bookCart']['series'] == $resultCat->id){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$seriesDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
												}
											  }
											 	echo $seriesDropDown .= '</select>';
									
									 ?>	
									   <span id="seriesError"></span>
							</div>
							
			<div class="form-group col-sm-4">
							 <label for="operator" >Operator Name</label>  
								<?php 
								$operatorDropDown = '<select name="operator" id="operator" class="form-control select2" data-parsley-errors-container="#operatorError" onchange="getSeriesOperator();">
									 	 <option value="">Select Operator</option>';
											  $resCat = selectSql(TBL_OPERATOR_MASTER,"where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."' ",' ORDER BY `id`');
											  if(num_rows($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($_SESSION['bookCart']['operator'] == $resultCat->id){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$operatorDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
												}
											  }
											 	echo $operatorDropDown .= '</select>';
									
									 ?>	
									   <span id="operatorError"></span>
							</div>
		
		<?php } ?>
		<div class="clearfix"></div>
          <div class="form-group col-sm-2">
            <label for="id_company">Source</label>
            <select class="form-control" name="id_company" id="id_company" onChange="ajaxAddRoommsgUpdate(); getContact(this.value,''); getRateLetter(this.value,'');  getcreditvalue(this.value,'');" data-parsley-errors-container="#companyError" data-parsley-required >
              <option value="">Select Source</option>
              <?php $resCat = executeSql("SELECT id_company,name from `".TBL_COMPANY."` where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."' ORDER BY `id_company`");
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($_SESSION['bookCart']['id_company'] == $resultCat->id_company){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$companyData .= '<option '.$selected.' value="'.$resultCat->id_company.'">'.ucfirst($resultCat->name).'</option>';
												}
											  }
											 echo $companyData;;
											  ?>
            </select>
			
            <span id="companyError"></span> </div>
			
			
          <div class="form-group col-sm-3" >
            <label for="rate_id" >Rate Letter</label>
            <select class="form-control" name="rate_id" id="rate_id" onChange="ajaxAddRoommsgUpdate();" data-parsley-required data-parsley-errors-container="#rate_idError">
              <option value="" >Select Source</option>
            </select>
            <span id="rate_idError"></span> </div>
          <div class="form-group col-sm-4">
            <label for="id_guest" >Guest Name</label>
            <div class="input-group" id="showGuest">
              <select class="form-control " name="id_guest" id="id_guest" data-parsley-errors-container="#guestError">
                <option value="">Select Guest</option>
                <?php 
									$resCat = selectSql(TBL_CUSTOMER,"where status='1' and type='1' and `id_shop` = '".addslashes($_SESSION['shop'])."' ",' ORDER BY `first_name`');
												  if(num_rows($resCat)){
													while($resultCat = $db->fetch_object2($resCat)){
														if($_SESSION['bookCart']['id_guest'] == $resultCat->id_customer){
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
            <span id="guestError"></span> </div>
			
			
          <div class="form-group col-sm-2" >
            <label for="id_contacts" >Booker Name</label>
			 <div class="input-group" id="showContact">
            <select class="form-control select2" name="id_contacts" id="id_contacts" data-parsley-errors-container="#contactError">
              <option value="">Select User</option>
            </select>
			 <div class="input-group-addon contact_open"> <i class="fa fa-plus"></i> </div>
            </div>
            <span id="contactError"></span> </div>
          <div class="box-tools pull-left" style="margin-top:25px;">
            <button class="btn btn-danger" type="button" id="view"> <i class="fa fa-eye fa-lg"></i> View</button>
          </div>
		  <div style="color:red; float:left; margin-left:15px;" id="getcredit_value"></div>
          <table class="table table-hover" id="showRoom">
            <tr>
              <th>Room Type</th>
              <th>Plan</th>
              <th>Room Quantity</th>
              <th>Adults/Room</th>
              <th>Child/Room (0 - 5 yrs)</th>
              <th>Child/Room (5 - 12 yrs)</th>
              <th>Avg. Rate*Night</th>
              <th><button class="btn btn-danger btn-sm" type="button" id="view" onClick="ajaxAddRoommsgUpdate();"> <i class="fa fa-close fa-lg"></i> </button></th>
            </tr>
            <tr id="addRoommsg" align="center" <?php if($_SESSION['bookCart']['dataValue'] != ''){ echo 'style="display:none;"';}  ?>>
              <td colspan="7"><strong>Please Add Room.</strong></td>
            </tr>
            <?php if($_SESSION['bookCart']['dataValue'] != ''){
				$i = 0;				
				foreach($_SESSION['bookCart']['dataValue'] as $uniqueCode =>$dataCode){
	/*  dateValue|'.$_REQUEST['hotel_id'].'|'.$_REQUEST['room_id'].'|'.$_REQUEST['rate_id'].'|'.$_REQUEST['rate_plan_id'].'|'.$_REQUEST['rate_assign_id'].'|'.$type */
				$dataValue = explode('|',$dataCode);								
				$resRoom = executeSql("SELECT rt.name as room_name, rp.name as rate_name, rd.* from `".TBL_RATE_DETAILS."` rd left join `".TBL_ROOM_TYPE."` rt on rd.room_id = rt.id left join `".TBL_RATE_PLAN."` rp on rd.rate_plan_id = rp.id where rd.status='1' and rt.status='1' and rd.rate_assign_id='".addslashes($dataValue['5'])."'  and rd.rate_plan_id='".addslashes($dataValue['4'])."' and rd.rate_id='".addslashes($dataValue['3'])."' and room_id='".addslashes($dataValue['2'])."' order by rd.room_id");	
				if(num_rows($resRoom) >0){
					$rowRoom = $db->fetch_object2($resRoom);
					$availableData = '<tr id="'.$uniqueCode.'" class="ajaxAddRoom">';
$availableData .=' <td><strong>'.$rowRoom->room_name.'</strong></td>';

$availableData .=' <td><strong><select class="form-control " name="rate_plan_id[]" id="rate_plan_id|'.$uniqueCode.'"  data-parsley-required  onchange="getRate($(this).attr(\'id\'));" >
											  <option value="">Rate Plan</option>';
	  $resCat = selectSql(TBL_RATE_PLAN," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ",'  order by display_order');
											 
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($dataValue['4'] == $resultCat->id){
													   $selected3 = 'selected="selected"';
													}else{
														$selected3 = '';
													}
													
													
													
													$availableData .= '<option '.$selected3.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
													
												}
											 	 $availableData .= '</select></strong></td></strong></td>';
				
$availableData .=' <td> <select class="form-control input-sm" name="room_quantity[]" id="room_quantity|'.$uniqueCode.'" data-parsley-required onchange="getRate($(this).attr(\'id\'));" >';


for ($i=1; $i<=30; $i++)
    				{
        
            $availableData .='<option value="'.$i.'"';
			 if($_SESSION['bookCart']['room_quantity'][$uniqueCode] == $i){
			 $availableData .='selected="selected"';
			 }
			 
			 $availableData .='>'.$i.'</option>';
       
    }		
				
$availableData .='</select></td>
				
		<input type="hidden" name="uniqueCode[]" value="'.$uniqueCode.'" id="uniqueCode|'.$uniqueCode.'">
		<input type="hidden" name="dataValue[]" value="'.$dataCode.'" id="dataValue|'.$uniqueCode.'">
		  <td>  <select class="form-control input-sm" name="adult_no[]" id="adult_no|'.$uniqueCode.'" data-parsley-required  onchange="getRate($(this).attr(\'id\'));">';
				if($_SESSION['bookCart']['adult_no'][$uniqueCode] == '1' ){$selectedAdultNo1 =  'selected="selected"';}else{$selectedAdultNo1 =''; }
				if($_SESSION['bookCart']['adult_no'][$uniqueCode] == '2' ){$selectedAdultNo2 =  'selected="selected"';}else{$selectedAdultNo2 =''; }
				if($_SESSION['bookCart']['adult_no'][$uniqueCode] == '3' ){$selectedAdultNo3 =  'selected="selected"';}else{$selectedAdultNo3 =''; }
				if($_SESSION['bookCart']['adult_no'][$uniqueCode] == '4' ){$selectedAdultNo4 =  'selected="selected"';}else{$selectedAdultNo4 =''; }
				if($_SESSION['bookCart']['adult_no'][$uniqueCode] == '5' ){$selectedAdultNo5 =  'selected="selected"';}else{$selectedAdultNo5 =''; }
				
for ($i=1; $i<=100; $i++)
    				{
        
            $availableData .='<option value="'.$i.'"';
			 if($_SESSION['bookCart']['room_quantity'][$uniqueCode] == $i){
			 $availableData .='selected="selected"';
			 }
			 
			 $availableData .='>'.$i.'</option>';
       
    }
                $availableData .='</select></td>
				
				<td> <select class="form-control input-sm" name="infant_no[]" id="infant_no|'.$uniqueCode.'" data-parsley-required onchange="getRate($(this).attr(\'id\'));">';
				if($_SESSION['bookCart']['infant_no'][$uniqueCode] == '0' ){$selectedChildNo1 =  'selected="selected"';}else{$selectedChildNo1 =''; }
				if($_SESSION['bookCart']['infant_no'][$uniqueCode] == '1' ){$selectedChildNo2 =  'selected="selected"';}else{$selectedChildNo2 =''; }
				if($_SESSION['bookCart']['infant_no'][$uniqueCode] == '2' ){$selectedChildNo3 =  'selected="selected"';}else{$selectedChildNo3 =''; }
$availableData .=' <option value="0" '.$selectedChildNo1.'>0</option>
				   <option value="1" '.$selectedChildNo2.'>1</option>
				   <option value="2" '.$selectedChildNo3.'>2</option>
                </select></td>
				
				  <td> <select class="form-control input-sm" name="child_no[]" id="child_no|'.$uniqueCode.'" data-parsley-required onchange="getRate($(this).attr(\'id\'));">';
				if($_SESSION['bookCart']['child_no'][$uniqueCode] == '0' ){$selectedChildNo1 =  'selected="selected"';}else{$selectedChildNo1 =''; }
				if($_SESSION['bookCart']['child_no'][$uniqueCode] == '1' ){$selectedChildNo2 =  'selected="selected"';}else{$selectedChildNo2 =''; }
				if($_SESSION['bookCart']['child_no'][$uniqueCode] == '2' ){$selectedChildNo3 =  'selected="selected"';}else{$selectedChildNo3 =''; }
$availableData .=' <option value="0" '.$selectedChildNo1.'>0</option>
				   <option value="1" '.$selectedChildNo2.'>1</option>
				   <option value="2" '.$selectedChildNo3.'>2</option>
                </select></td>';
			 
$availableData .='<td id="price_'.$uniqueCode.'"><strong><i class="fa fa-inr"></i> '.$_SESSION['bookCart']['room_price'][$uniqueCode]*$_SESSION['bookCart']['noOfDays'].'</strong>&nbsp;&nbsp;<span class="pricePopUp_open" onclick="pricePopUp(this.id);" id="pricePopUp_'.$uniqueCode.'" ><i class="fa fa-pencil"></i></span> </td>  
				  <td> <a class="btn btn-danger btn-sm" href="javascript:void(0);" id="'.$uniqueCode.'" onclick="ajaxRoomRemove($(this).attr(\'id\'));");">
				  <i class="fa fa-trash-o fa-lg"></i> </a></td>              
                </tr>';
					}
			  	$i++;
				echo $availableData;
				}
				
				
				}?>
          </table>
        </form>
      </div>
      <!-- /.box-body -->
      <div class="box-footer " >
        <button id="createBooking" name="createBooking" type="button" class="btn btn-primary pull-right" <?php if($_SESSION['bookCart']['dataValue'] != ''){ echo 'style="visibility:visible;"';}  ?> style="visibility:hidden;" > Next <i class="fa fa-angle-double-right" aria-hidden="true"></i></button>
      </div>
      <!-- /.box-footer-->
    </div>
    <!-- /.box -->
  </section>
  <!-- /.content -->
</div>
<div id="eventsPopup" class="well" style="min-width:20em; display:none;"> <a href="#" class="eventsPopup_close" style="float:right;padding:0 0.4em;"><i class="fa fa-times text-red"></i></a>
  <div class="error-content">
    <h4><i class="fa fa-warning text-red"></i> No Events on this Date.</h4>
  </div>
</div>
<div id="guest" class="well">
  <form id="guestpopupform" data-parsley-validate autocomplete="off" method="post"  >
   
    
    <div class="form-group">
      <label class="title">Title</label>
       <select name="title"  class="form-control input-sm" data-parsley-required >
                                  <option value="">-Select-</option>
                                  <option value="Dr.">Dr.</option>
                                  <option value="Miss.">Miss.</option>
                                  <option value="Mr.">Mr.</option>
                                  <option value="Mrs.">Mrs.</option>
                                  <option value="Ms.">Ms.</option>
                                  <option value="Pr.">Pr.</option>
                                  <option value="Prof.">Prof.</option>
                                  <option value="Rev.">Rev.</option>
                                </select>
    </div>
    
                                
                                
    <div class="form-group">
      <label for="first_name">First Name</label>
      <input type="text" class="form-control input-sm" placeholder="Enter first name" id="first_name" name="first_name" value="" data-parsley-required >
    </div>
    <div class="form-group">
      <label for="last_name">Last Name</label>
      <input type="text" class="form-control input-sm" placeholder="Enter last name" id="last_name" name="last_name" value="" data-parsley-required>
    </div>
    <div class="form-group">
      <label for="email" >Email Id</label>
      <input type="email" name="email" id="email" class="form-control" placeholder="Enter Email Id" data-parsley-type="email" automcomplete="off">
    </div>
    <div class="form-group">
      <label for="mobile" >Mobile No.</label>
      <input type="phone" name="mobile" id="mobile" class="form-control" placeholder="Enter mobile number"  data-parsley-type="digits" data-parsley-length="[10, 10]" automcomplete="off">
    </div>
    <div class="form-group">
    <label for="id_country" >Country</label>
                  <select class="form-control" name="id_country" id="id_country" data-parsley-required>
                    <option value="">Select Country</option>
                    <?php 
						$resCat = selectSql(TBL_COUNTRY_LANG,"where id_lang='1' ",' ORDER BY `name`');
									  
										while($resultCat = $db->fetch_object2($resCat)){
											
													
											$countryDropDown .= '<option  value="'.$resultCat->id_country.'">'.ucfirst($resultCat->name).'</option>';
									  }
												  echo $countryDropDown;
									
									 ?>
                  </select>
    </div>
        <div class="form-group">
      <label class="user_type">Guest type</label>
       <select name="user_type"  class="form-control input-sm"  >
                                  <option value="">-Select-</option>
                                  <option value="VIP">VIP</option>
                                  <option value="CIP">CIP</option>
                                
                                </select>
    </div>
    
    
    <input  type="button" class="btn btn-default" onClick="saveGuestPopupform();" value="Save">
    <button class="guest_close btn btn-default">Close</button>
  </form>
</div>


<div id="contact" class="well">
  <form id="contactpopupform" data-parsley-validate autocomplete="off" method="post">
  <div class="form-group">
      <label class="title">Title</label>
       <select name="title"  class="form-control input-sm" data-parsley-required >
                                  <option value="">-Select-</option>
                                  <option value="Dr.">Dr.</option>
                                  <option value="Miss.">Miss.</option>
                                  <option value="Mr.">Mr.</option>
                                  <option value="Mrs.">Mrs.</option>
                                  <option value="Ms.">Ms.</option>
                                  <option value="Pr.">Pr.</option>
                                  <option value="Prof.">Prof.</option>
                                  <option value="Rev.">Rev.</option>
                                </select>
    </div>
    
    <div class="form-group">
      <label for="first_name">First Name</label>
      <input type="text" class="form-control input-sm" placeholder="Enter first name" id="first_name" name="first_name" value="" data-parsley-required >
    </div>
    <div class="form-group">
      <label for="last_name">Last Name</label>
      <input type="text" class="form-control input-sm" placeholder="Enter last name" id="last_name" name="last_name" value="" data-parsley-required ">
    </div>
    <div class="form-group">
      <label for="email" >Email Id</label>
      <input type="email" name="email" id="email" class="form-control" placeholder="Enter Email Id" data-parsley-type="email" automcomplete="off">
    </div>
    <div class="form-group">
      <label for="mobile" >Mobile No.</label>
      <input type="phone" name="mobile" id="mobile" class="form-control" placeholder="Enter mobile number"  data-parsley-type="digits" data-parsley-length="[10, 10]" automcomplete="off">
    </div>
    <input  type="button" class="btn btn-default" onClick="savecontactPopupform();" value="Save">
    <button class="contact_close btn btn-default">Close</button>
  </form>
</div>

<div id="pricePopUp" class="well" style="display:none;">
  <form id="pricePopUpform" data-parsley-validate autocomplete="off" method="post"  >
  	 <input type="hidden" id="uniqueCode" name="uniqueCode" >
	 
    <div class="form-group">
      <label for="tarrif">Tarrif Price</label>
      <input type="text" class="form-control input-sm" placeholder="Enter tarrif price" id="tarrif" name="tarrif" value="" data-parsley-required data-parsley-type="digits">
    </div>
    <div class="form-group">
      <label for="meal">Meal Price</label>
      <input type="text" class="form-control input-sm" placeholder="Enter meal price" id="meal" name="meal" value="" data-parsley-type="digits">
    </div>
   <div class="form-group">
      <label for="extra">Extra Price</label>
      <input type="text" class="form-control input-sm" placeholder="Enter extra price" id="extra" name="extra" value="" data-parsley-type="digits">
    </div>  
    <input  type="button" class="btn btn-default" onClick="savepricePopUpform();" value="Save">
    <button class="pricePopUp_close btn btn-default">Close</button>
  </form>
</div>


<div id="planDetail" class="well" style="display:none; min-width:55em;"> <a href="#" class="planDetail_close" style="float:right;padding:0 0.4em;"><i class="fa fa-times text-red"></i></a>
  <div id="ajaxPlanData"></div>
</div>

<script>
<?php if($_SESSION['bookCart']['hotel_id'] != ''){ ?>
//window.onbeforeunload = function() { return "Your work will be lost."; };

window.onload = function() { ajaxCheckAvailability(); 
							getContact(<?php echo $_SESSION['bookCart']['id_company'] ?>,<?php echo $_SESSION['bookCart']['id_contacts'] ?>);
							getRateLetter(<?php echo $_SESSION['bookCart']['id_company'] ?>,<?php echo $_SESSION['bookCart']['rate_id'] ?>); };
							getcredit(<?php echo $_SESSION['bookCart']['id_company'] ?>,<?php echo $_SESSION['bookCart']['company_credibility'] ?>); };
							
<?php } ?>
</script>
<?php include_once("includes/footer.php")?>
<script>




function getcreditvalue(id_company,rate_id){
 var form1=$("#availabiltyForm");	
 
 var dataString = $("#availabiltyForm").serialize();	
	if(form1.parsley().validate()){
		$.ajax({
		   type: "POST",
		   url: 'ajax/ajaxgetcreditvalue.php',
		   data: dataString+'&id_company='+id_company+'&rate_id='+rate_id, 
		   success: function (result) {					
				$( "#getcredit_value" ).html(result);								
			}
		})
	}
}


//////////////////////check availabilty -book-now.php///////////////////////////////////////////////// 

function ajaxCheckAvailability() {
          //alert('test');
  		  var form=$("#availabiltyForm");		  
		  form.parsley().validate();		  
  		  $('.loading').show(); 
		  $.ajax({
			   type: "POST",
			   url: 'ajax/ajaxcheckAvailability.php',
			   data: form.serialize(), 
			   success: function (result) {
					$('#availabilty').html(result)
				},
			  complete: function(){
				$('.loading').hide();
			  }
		})
	return false;
 }
/////////////////////////////////show events on date -book-now.php/////////////////////////////////////////////
function getEvents(dated){
//$('#eventsPopup').popup('show');
 $('#eventsPopup').popup({
            //pagecontainer: '.container',
        	transition: 'all 0.3s',
            autoopen: true,            
        });
}



////////////////////////////////////////


function getRateLetter(id_company,rate_id){
 var form1=$("#availabiltyForm");	
 var dataString = $("#availabiltyForm").serialize();	
	if(form1.parsley().validate()){
		$.ajax({
		   type: "POST",
		   url: 'ajax/ajaxGetRateLetter.php',
		   data: dataString+'&id_company='+id_company+'&rate_id='+rate_id, 
		   success: function (result) {					
				$( "#rate_id" ).html(result);								
			}
		})
	}
}
/////////////////////////////////show plan Details on date -book-now.php/////////////////////////////////////////////


$("#view").click(function (){
 var form1=$("#availabiltyForm");	
 var form2=$("#addRoomForm");
 var dataString = $("#availabiltyForm, #addRoomForm").serialize();	
	if(form1.parsley().validate() && form2.parsley().validate()){
		$.ajax({
		   type: "POST",
		   url: 'ajax/ajaxGetPlanDetails.php',
		   data: dataString, 
		   success: function (result) {					
				$( "#ajaxPlanData" ).html(result);
				$('#planDetail').popup({
        			 transition: 'all 0.3s',
           			 autoopen: true,            
        		});
				 //$("#hotelId").val('1').attr('selected','selected');					
			}
		})
	}
})


////////////////////////////////////////////////////////////////////////////////


function ajaxAddRoom(rate_id,rate_assign_id,room_id,rate_plan_id,type){
   var form1=$("#availabiltyForm");	
   var form2=$("#addRoomForm");
   var dataString = $("#availabiltyForm, #addRoomForm").serialize();
	if(form1.parsley().validate() && form2.parsley().validate()){
		$.ajax({
		   type: "POST",
		   url: 'ajax/ajaxaddRoom.php',
		   data: dataString+'rate_id='+rate_id+'&rate_assign_id='+rate_assign_id+'&room_id='+room_id+'&rate_plan_id='+rate_plan_id+'&type='+type, 
		   success: function (result) {					
				resultArray = result.split('|||');
					if(resultArray['0']!=''){
						$('#roomLimitMsg').css('display', 'block');
						$('#roomLimitMsg').html(resultArray['0']);
					}
					$('#showRoom').append(resultArray['1']);
					$('#addRoommsg').css('display', 'none');
					$('#createBooking').css('visibility', 'visible');					
			}
		})
	}


}

//////////////////////////////save price popup form common//////////////////////////////////////////////////////////


function pricePopUp(id){
	var Id = id.split('_');
	var uniqueId= Id[1];
	$('#uniqueCode').val(uniqueId);
	
}
function savepricePopUpform(){
	var uniqueCode = $("#uniqueCode").val();
	var dataValue = $('#dataValue'+'\\|'+uniqueCode).val();		
	//alert(dataValue);
	var form=$("#pricePopUpform");
	if(form.parsley().validate()){
	$('.loading').show(); 
	$.ajax({
	   type: "POST",
	   url: 'ajax/ajaxSavePrice.php',
	   data: form.serialize()+'&dataValue='+dataValue, 
	   success: function (result) {
	    $('#pricePopUp').popup('hide');
		$("#pricePopUpform")[0].reset();
		 alert('Price has been updated.');
		 $('#price_'+uniqueCode).html(result);		
		},
	  complete: function(){
		$('.loading').hide();
	  }
	});
	return false;
	}
}





</script>
