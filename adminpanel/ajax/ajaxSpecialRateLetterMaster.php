<?php include_once("../../config/auto_loader.php");

checkUserLevelPermission($_SESSION['userLevel'],TBL_RATE,'update');
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//print_r($_REQUEST);
 $dateStart  =	selectColumn(TBL_RATE_SEASON,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'");
 $dateEnd    =	selectColumn(TBL_RATE_SEASON,'end_date'," WHERE `id` = '".$_REQUEST['seasonId']."'");
	?>
<script>
$(".datepickerspecial").datepicker({
	dateFormat : 'dd-mm-yy',
	minDate : new Date('<?php echo date('Y-m-d',strtotime($dateStart) ); ?>'),
	maxDate: new Date('<?php echo date('Y-m-d',strtotime($dateEnd) ); ?>')
});
 /* $( function() {	 

    $( ".datepickerspecial").datepicker({ dateFormat: 'dd-mm-yy',minDate: '5d',maxDate:'15d';});
    //$( ".pickerdate").datetimepicker({  minDate:new Date()});
    
  $( ".datepickercheckin").datepicker({ dateFormat: 'dd-mm-yy', minDate: '-<?php echo $DateNoDays; ?>d' });
 $( ".datepickercheckout").datepicker({ dateFormat: 'dd-mm-yy',minDate: '-<?php echo $DateNoDays; ?>d'});
  } );
*/


</script> 
<?php

 $hotelId = $_REQUEST['hotelId'];
//$room_id = implode(',',$_REQUEST['roomId'])	;
$season = $_REQUEST['seasonId'];
$id = encryptor('decrypt',$_REQUEST['id']);

//echo "SELECT * FROM `fs_event_calender` AS A Inner JOIN  fs_event_calender_detail AS B ON A.id=B.event_id WHERE B.id_hotel='".addslashes($hotelId)."' AND A.`id_shop`='".addslashes($_SESSION['shop'])."' AND A.`start`>='".$dateStart."' AND A.`end`<='".$dateEnd."'";	
$EventSql = executeSql("SELECT * FROM `fs_event_calender` AS A Inner JOIN  fs_event_calender_detail AS B ON A.id=B.event_id WHERE B.id_hotel='".addslashes($hotelId)."' AND A.`id_shop`='".addslashes($_SESSION['shop'])."' AND A.`start`>='".$dateStart."' AND A.`end`<='".$dateEnd."' ");											  
while($rowEvent = $db->fetch_object2($EventSql))
	{
	$EventArra[] = $rowEvent;
	}
foreach($EventArra as $resultEventList){
											  
	 $outputResultEventList .= '<option value="'.$resultEventList->id.'">'.ucfirst($resultEventList->name).'</option>';
			}
//print_r($outputResultEventList);
//echo "SELECT * FROM `".TBL_RATE."` as a join `".TBL_RATE_DETAILS_SPECIAL."` as b where a.b.hotel_id='".addslashes($hotelId)."' AND a.`id_shop` = '".addslashes($_SESSION['shop'])."'  AND a.id=b.rate_id ";

$DuplicateID	=	explode('|',$_REQUEST['rate_level_id']);
$resRoom = executeSql("SELECT rt.name, ahr.hotel_id,ahr.inventory, ahr.room_id,ahr.double_pax_price from `".TBL_ASSIGN_HOTEL_ROOM."` ahr left join `".TBL_ROOM_TYPE."` rt on ahr.room_id = rt.id where ahr.status='1' and rt.status='1' and ahr.hotel_id='".addslashes($hotelId)."'");


$resInclusion = executeSql("SELECT * from `".TBL_RATE_DETAILS_SPECIAL."` where hotel_id='".addslashes($hotelId)."' and rate_id='".addslashes(encryptor('decrypt',$_REQUEST['id']))."'");
$rowInclusion = $db->fetch_object2($resInclusion);



$GetDisplyWeekEnd	=	selectColumn(TBL_HOTELS,'excel_display_weekday'," WHERE `id` = '".addslashes($hotelId)."'");


		if($GetDisplyWeekEnd 	== 1){
			$WeekendDisplydouble_pax_price	=	'<td><input type="text" class="form-control  weekend_double_pax_price" id="weekend_double_pax_price[]" name="weekend_double_pax_price[]" value="" data-parsley-required automcomplete="off" data-parsley-type="number" style="width:60px;"></td> ';
			
			$WeekendDisplysingle_pax_price	=	'<td><input type="text" class="form-control  weekend_single_pax_price" id="weekend_single_pax_price[]" name="weekend_single_pax_price[]" value="" data-parsley-required automcomplete="off" data-parsley-type="number" style="width:60px;"></td> ';
			
			}else{
				$WeekendDisplydouble_pax_price	=	'';
				$WeekendDisplysingle_pax_price	=	'';
				}

if(num_rows($resInclusion)==0){
?>

<?php
}
//die;

if(addslashes(encryptor('decrypt',$_REQUEST['id']))!=''){
	
	
$editRowvalue = executeSql("SELECT * FROM `".TBL_RATE."` as a join `".TBL_RATE_DETAILS_SPECIAL."` as b where  a.`id` = '".addslashes(encryptor('decrypt',$_REQUEST['id']))."' AND hotel_id='".addslashes($hotelId)."' AND a.`id_shop` = '".addslashes($_SESSION['shop'])."'  AND a.id=b.rate_id ");


}
//////////////////////////////getting rate data on edit//////////////////////////////////////////////////////
$CountNumber_row	=	num_rows($editRowvalue); 
if(addslashes(encryptor('decrypt',$_REQUEST['id']))!='' && $CountNumber_row >0){
	
	$resCat_rooms22 = executeSql("SELECT rt.name, ahr.hotel_id,ahr.inventory, ahr.room_id,ahr.double_pax_price from `fs_assign_hotel_room` ahr left join `fs_room_type` rt on ahr.room_id = rt.id where ahr.status='1' and rt.status='1' and ahr.hotel_id=".addslashes($hotelId));											  
while($rowInclusion22 = $db->fetch_object2($resCat_rooms22))
	{
	$y[] = $rowInclusion22;
	}
foreach($y as $rest){
											  
	$outputasd .= '<option value="'.$rest->room_id.'">'.ucfirst($rest->name).'</option>';
			}
			
			
			
$resCat_2 = selectSql(TBL_RATE_PLAN," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ",'  order by display_order');
											 
			while($resultCat_2 = $db->fetch_object2($resCat_2)){
			$Add_rateplan[] = $resultCat_2;
			}
				 foreach($Add_rateplan as $Add_rateplan_result){
		  
					$Add_rateplan_results .= '<option '.$selected3.' value="'.$Add_rateplan_result->id.'">'.ucfirst($Add_rateplan_result->name).'</option>';
				}
	
	

$sqlspecial_group = executeSql("SELECT special_group FROM `".TBL_RATE_DETAILS_SPECIAL."` WHERE `rate_id` = '".addslashes(encryptor('decrypt',$_REQUEST['id']))."'AND hotel_id='".addslashes($hotelId)."' group by special_group");
$special_groupArr=array();
			while($RecordRowspecial_group = $db->fetch_object2($sqlspecial_group)){

			array_push($special_groupArr,$RecordRowspecial_group->special_group);
			}
			
	
	$SpecialIncrementId=count($special_groupArr);
		$OnKeyClickAddSpecialRate	   = "AddSpecialRateDiv('".$uniqueCode."','".$SpecialIncrementId."')";
	$availableData = '
	<div class="box" style="border-top:none;">
            
             
			  <div class="btn btn-block btn-primary" style="float: left;padding: 7px;"> 
			  <p  style="float: left;margin: 4px;margin-right: 18px;" class="box-title">Special Rates :</p> <button style="float: left;" type="button" name="add" class="btn btn-success btn-sm add" onClick="'.$OnKeyClickAddSpecialRate.';"><span class="glyphicon glyphicon-plus"></span></button>
			  </div>
			 
  
            <input type="hidden" id="SpecialIncrementId" name="SpecialIncrementId" value="'.$SpecialIncrementId.'">	
				  	  <input type="hidden" id="blockIncrementId" name="blockIncrementId" value="'.$SpecialIncrementId.'">
		</div>	
	';
	
	foreach($special_groupArr as $groupdata){
		$SpecialIncrementId=$groupdata;
	
		$start_date	=	selectColumn(TBL_RATE_DETAILS_SPECIAL,'start_date'," WHERE `rate_id` = '".addslashes(encryptor('decrypt',$_REQUEST['id']))."' AND special_group='".addslashes($groupdata)."' AND hotel_id='".addslashes($hotelId)."'  ");
		$end_date	=	selectColumn(TBL_RATE_DETAILS_SPECIAL,'end_date'," WHERE `rate_id` = '".addslashes(encryptor('decrypt',$_REQUEST['id']))."' AND special_group='".addslashes($groupdata)."' AND hotel_id='".addslashes($hotelId)."'  ");
		$special_rate_name=	selectColumn(TBL_RATE_DETAILS_SPECIAL,'special_rate_name'," WHERE `rate_id` = '".addslashes(encryptor('decrypt',$_REQUEST['id']))."' AND special_group='".addslashes($groupdata)."' AND hotel_id='".addslashes($hotelId)."' ");
		/*$availableData .= '<div class="alert alert-success alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<p><i class="icon fa fa-check"></i> 
						sdfasd'.selectColumn(TBL_HOTELS,'name'," WHERE status='1' AND `id` = '".addslashes($hotelId)."'").' will be added to Rate Letter '.$row->rate_name.'-'.$row->sub_code.'.</p>
					 </div>';*/
					
//$editsql = executeSql("SELECT * FROM `".TBL_RATE."` as a join `".TBL_RATE_DETAILS_SPECIAL."` as b where  a.`id` = '".addslashes(encryptor('decrypt',$_REQUEST['id']))."' AND b.special_group='".addslashes($groupdata)."' AND hotel_id='".addslashes($hotelId)."' AND a.`id_shop` = '".addslashes($_SESSION['shop'])."'  AND a.id=b.rate_id ");
$editsql = executeSql("SELECT * FROM `".TBL_RATE."` as a join `".TBL_RATE_DETAILS_SPECIAL."` as b where  a.`id` = '".addslashes(encryptor('decrypt',$_REQUEST['id']))."' AND b.special_group='".addslashes($groupdata)."' AND hotel_id='".addslashes($hotelId)."' AND a.`id_shop` = '".addslashes($_SESSION['shop'])."'  AND a.id=b.rate_id ");

			
////////////////////////////show grid data////////////////////////////////////////////////////////
$resRoom = executeSql("SELECT rt.name, ahr.hotel_id,ahr.inventory, ahr.room_id,ahr.double_pax_price from `".TBL_ASSIGN_HOTEL_ROOM."` ahr left join `".TBL_ROOM_TYPE."` rt on ahr.room_id = rt.id where ahr.status='1' and rt.status='1' and ahr.hotel_id='".addslashes($hotelId)."'");


$resInclusion = executeSql("SELECT * from `".TBL_RATE_DETAILS_SPECIAL."` where hotel_id='".addslashes($hotelId)."' and rate_id='".addslashes(encryptor('decrypt',$_REQUEST['id']))."'");
$rowInclusion = $db->fetch_object2($resInclusion);

if(num_rows($resInclusion)==0){
	/*$availableData .= '<div class="alert alert-success alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<p><i class="icon fa fa-check"></i> 
						'.selectColumn(TBL_HOTELS,'name'," WHERE status='1' AND `id` = '".addslashes($hotelId)."'").' will be added to Rate Letter '.$row->rate_name.'-'.$row->sub_code.'.</p>
					 </div>';*/
	
}
 $availableData .= '<div class="box box-primary  table-responsive no-padding" style="border: 1px solid #00a65a;">';

											
                  $availableData .= '
					 <div class="form-group col-sm-3" style="margin-top: 10px;">
                  <label for="start_date">Name</label>
                 
                 
<select class="form-control"  name="special_rate_name['.$SpecialIncrementId.']" id="special_rate_name" data-parsley-required  '.$disabled.'>
											  <option value="">--Select Special Rate Period--</option>';
											  $resCat_rooms = executeSql("SELECT * FROM `fs_event_calender` AS A Inner JOIN  fs_event_calender_detail AS B ON A.id=B.event_id WHERE B.id_hotel='".addslashes($hotelId)."' AND A.`id_shop`='".addslashes($_SESSION['shop'])."' AND A.`start`>='".$dateStart."' AND A.`end`<='".$dateEnd."'");
											  
											  	while($row_room_type = $db->fetch_object2($resCat_rooms)){
													if($special_rate_name == $row_room_type->id){
													   $selectedroom_type = 'selected="selected"';
													}else{
														$selectedroom_type = '';
													}
													
													$availableData .= '<option '.$selectedroom_type.' value="'.$row_room_type->id.'">'.ucfirst($row_room_type->name).'</option>';
												
											  }
											 	 $availableData .= '</select>
                  </div>';

											
                  $availableData .= '<div class="form-group col-sm-2" style="margin-top: 10px;">
                  <label for="start_date">Start Date</label>
                  <div class="input-group">
                    <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                    <input type="text" class="form-control datepickerspecial" placeholder="Enter date" id="special_date_start" name="special_date_start['.$SpecialIncrementId.']" value="'.date('d-m-Y',strtotime($start_date)).'"  data-parsley-required>
        
                  </div>
                  <!-- /.input group -->
                  <span id="start_dateError">'.$err_start_date.'</span> </div>
                <div class="form-group col-sm-2" style="margin-top: 10px;">
                  <label for="end_date">End Date </label>
                  <div class="input-group">
                    <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                    <input type="text" class="form-control datepickerspecial" id="special_date_end" name="special_date_end['.$SpecialIncrementId.']" value="'.date('d-m-Y',strtotime($end_date)).'" data-parsley-required data-parsley-errors-container="#special_date_endError" >
                     </div>
                 
                  <span id="end_dateError">'.$err_end_date.'</span> </div> 
			  ';
			  
$availableData .= '
				  <table class="table table-hover" style="margin-bottom:none !important;">
		
		<tr>
		<th>Room</th>
		<th width="8%">Rate Plan</th>		
		<th>Single</th>';
if($GetDisplyWeekEnd == 1){
		$availableData .= '<th>Weekend/<br>Single</th>';	
}
		$availableData .= '<th>Double</th>';	
if($GetDisplyWeekEnd == 1){		
		$availableData .= '<th>Weekend/<br>Double</th>';	
}
		//$availableData .= '<th>Package</th>';	
						  
		$availableData .= '<th>Extra Bed</th>
		<th>Breakfast</th>
		<th>Lunch</th>
		<th>Dinner</th>
		<th>Tax</th>
		<th>Status</th>
		</tr>';
					

								
$rowRoom = $db->fetch_object2($resRoom);

//print_r($rowRoom);
$counter = 0;	
$resRatePlan = executeSql("SELECT * from `".TBL_RATE_PLAN."`  where status='1' AND `id_shop` = '".addslashes($_SESSION['shop'])."' order by display_order");

$rack_rate = $rowRoom->double_pax_price;
$rowRatePlan = $db->fetch_object2($resRatePlan);

$resRate = executeSql("SELECT `".TBL_RATE_DETAILS_SPECIAL."`.* from `".TBL_RATE."` LEFT JOIN `".TBL_RATE_DETAILS_SPECIAL."` ON `".TBL_RATE."`.id= `".TBL_RATE_DETAILS_SPECIAL."`.rate_id  where `".TBL_RATE."`.id='".$id ."' and `".TBL_RATE_DETAILS_SPECIAL."`.room_id='".$rowRoom->room_id."' and `".TBL_RATE_DETAILS_SPECIAL."`.rate_plan_id='".$rowRatePlan->id."'  and `".TBL_RATE_DETAILS_SPECIAL."`.rate_assign_id='".$rowInclusion->id."'");

$rate_plan_id = $rowRatePlan->id;
$roomId = $rowRoom->room_id;

/////planType is pkgCounter to check how many pkg are created ////////

$planType = '0';



				
$CountNumber	=	1;			
				
while($editrow = $db->fetch_object2($editsql)){
		
		//echo "<pre>";print_r($editrow);echo "</pre>";
			$editid_id				=	$editrow->id;
			$editroom_id			=	$editrow->room_id;
			$editrate_id			=	$editrow->rate_id;
			$editrate_plan_id		=	$editrow->rate_plan_id;
			$editsingle_pax_price 	=	$editrow->single_pax_price;
			$editdouble_pax_price 	=	$editrow->double_pax_price;
			$editweekend_single_pax_price 	=	$editrow->weekend_single_pax_price;
			$editweekend_double_pax_price 	=	$editrow->weekend_double_pax_price;
			$editextra_bed		 	=	$editrow->extra_bed_price;
			$editbreakfast_price 	=	$editrow->breakfast_price;
			$editlunch_price 		=	$editrow->lunch_price;
			$editdinner_price 		=	$editrow->dinner_price;
			$editdpkg_price 		=	$editrow->pkg_price;
			$editstart_date 		=	$editrow->start_date;
			$editend_date 			=	$editrow->end_date;
			$edittax_room 			=	$editrow->tax_room;
 		    $statusCheck			=	$editrow->detail_status;
			$edithotel_remarks		=	$editrow->hotel_remarks;
			 
			 
if($statusCheck==1){
	$statusCheck = 'checked="checked"';
}else{
	$statusCheck = '';
}


$inclusionDetail= json_decode($rowInclusion->inclusion_detail,true);	
$inclusion = explode(',',$rowRatePlan->inclusion);
$availableData .= '<tr id="rateMaster|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'">';

	
foreach($inclusion as $value){
$resRateInclusion = executeSql("SELECT * from `".TBL_RATE_INCLUSION."`  where status='1' and id='".addslashes($value)."' AND `id_shop` = '".addslashes($_SESSION['shop'])."' limit 0,1");
$rowRateInclusion = $db->fetch_object2($resRateInclusion);
if($value !=''){
					if($inclusionDetail[$rowRateInclusion->id]!=''){
						$inclusionValue = $inclusionDetail[$rowRateInclusion->id];
					}else{
						$inclusionValue = 0;
					}
$availableData .= '<input type="hidden" class="form-control  inclusion|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'" placeholder="Enter price" id="inclusion|'.$rowRateInclusion->id.'|'.$rowRateInclusion->type.'" name="inclusion_food|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'[]" value="'.$inclusionValue.'" data-parsley-required data-parsley-type="digits" onkeyup="rateCalAll(this.id,this.value);">
					  ';
	}
}	
		  $editstart_date 		=	$editrow->start_date;
			$editend_date 			=	$editrow->end_date;

	
$availableData .= '<input type="hidden" id="edit_id" name="edit_id['.$SpecialIncrementId.'][]" value="'.$editid_id.'" >';	
$availableData .= '<input type="hidden" id="special_group" name="special_group['.$SpecialIncrementId.'][]" value="'.$SpecialIncrementId.'" >';	
$availableData .= '<input type="hidden" id="rate_id" name="rate_id" value="'.$editrate_id.'" >';
	
//$availableData .= '<input type="hidden" id="data_id|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'" name="data_id[]" value="|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'" >';	


					
					
					$availableData .= '<td><span><select class="form-control"  name="special_room_type_id['.$SpecialIncrementId.'][]" id="special_room_type_id" data-parsley-required  '.$disabled.'>
											  <option value="">Room Type</option>';
											  $resCat_rooms = executeSql("SELECT rt.name, ahr.hotel_id,ahr.inventory, ahr.room_id,ahr.double_pax_price from `fs_assign_hotel_room` ahr left join `fs_room_type` rt on ahr.room_id = rt.id where ahr.status='1' and rt.status='1' and ahr.hotel_id=".addslashes($hotelId));
											  
											  	while($row_room_type = $db->fetch_object2($resCat_rooms)){
													if($editroom_id == $row_room_type->room_id){
													   $selectedroom_type = 'selected="selected"';
													}else{
														$selectedroom_type = '';
													}
													
													$availableData .= '<option '.$selectedroom_type.' value="'.$row_room_type->room_id.'">'.ucfirst($row_room_type->name).'</option>';
												
											  }
											 	 $availableData .= '</select></td></span></td>';

					$availableData .= '<td><select class="form-control " name="special_rate_plan_id['.$SpecialIncrementId.'][]" id="special_rate_plan_id"  data-parsley-required '.$disabled.'  >
											  <option value="">Rate Plan</option>';
	  $resCat = selectSql(TBL_RATE_PLAN," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ",'  order by display_order');
											 
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($editrate_plan_id == $resultCat->id){
													   $selected3 = 'selected="selected"';
													}else{
														$selected3 = '';
													}
													
													
													
													$availableData .= '<option '.$selected3.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
													
												}
											 	 $availableData .= '</select></td>';
												 
												
												

							 
				  
				 $availableData .= '
				  
				  <input type="hidden" id="rate_plan_id|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'" name="rate_plan_id|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'" value="'.$rate_plan_id.'" >
				  <input type="hidden" id="room_id|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'" name="room_id|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'" value="'.$roomId.'" >
				  <input type="hidden" id="plan_type|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'" name="plan_type|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'" value="1" >
				  <input type="hidden" id="pkg_title|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'" name="pkg_title|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'" value="'.$pkg_title.'" >
				  <input type="hidden" id="pkg_description|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'" name="pkg_description|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'" value="'.$pkg_description.'" >
				  <input type="hidden" id="pkg_min_pax|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'" name="pkg_min_pax|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'" value="'.$pkg_min_pax.'" >
				  <input type="hidden" id="pkg_min_nights|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'" name="pkg_min_nights|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'" value="'.$pkg_min_nights.'" >
				  <input type="hidden" id="pkg_discount|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'" name="pkg_discount|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'" value="'.$pkg_discount.'" >
				  <input type="hidden" id="pkg_extra_price|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'" name="pkg_extra_price|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'" value="'.$pkg_extra_price.'" >
				  <input type="hidden" id="pkg_tarrif_price|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'" name="pkg_tarrif_price|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'" value="'.$pkg_tarrif_price.'" >
				  <input type="hidden" id="pkg_status|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'" name="pkg_status|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'" value="'.$pkg_status.'" >
				  <input type="hidden" id="pkgCounter|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'" value="1" >
				 
				 
				  
				  <td>
				  <input type="text" class="form-control  special_single_pax_rice" id="special_single_pax_price" name="special_single_pax_price['.$SpecialIncrementId.'][]" value="'.$editsingle_pax_price.'" data-parsley-required automcomplete="off" data-parsley-type="number" style="width:60px;">
				  </td>';
				  
				  if($GetDisplyWeekEnd == 1){
					   $availableData .= '<td>
				  <input type="text" class="form-control  special_weekend_single_pax_rice" id="special_weekend_single_pax_price" name="special_weekend_single_pax_price['.$SpecialIncrementId.'][]" value="'.$editweekend_single_pax_price.'" data-parsley-required automcomplete="off" data-parsley-type="number" style="width:60px;">
				  </td>';
				  
				}
				  
				 
				  $availableData .= '<td>				  
				  <input type="text" class="form-control  special_double_pax_rice" id="special_double_pax_rice" name="special_double_pax_price['.$SpecialIncrementId.'][]" value="'.$editdouble_pax_price.'" data-parsley-required automcomplete="off" data-parsley-type="number" style="width:60px;">
				  </td>';
				  
				  
				  
				if($GetDisplyWeekEnd == 1){	  
				   $availableData .= '<td>				  
				  <input type="text" class="form-control  special_weekend_double_pax_rice" id="special_weekend_double_pax_rice" name="special_weekend_double_pax_price['.$SpecialIncrementId.'][]" value="'.$editweekend_double_pax_price.'" data-parsley-required automcomplete="off" data-parsley-type="number" style="width:60px;">
				  </td>';
			}
			  
				  
				     $availableData .= '<td>				  
				  <input type="hidden" class="form-control  special_pkg_price" id="special_pkg_price" name="special_pkg_price['.$SpecialIncrementId.'][]" value="'.$editdpkg_price.'" data-parsley-required automcomplete="off" data-parsley-type="number"  style="width:60px;">

				 
				 
				   <input class="form-control  special_extra_bed" type="text" name="special_extra_bed['.$SpecialIncrementId.'][]" id="special_extra_bed" value="'.$editextra_bed.'" style="width:60px;" />
				  </td>
				  
				  
				  
					
				
				  
				  <td>				  
				  <input type="text" class="form-control  special_breakfast_price" id="special_breakfast_price" name="special_breakfast_price['.$SpecialIncrementId.'][]" value="'.$editbreakfast_price.'" data-parsley-required automcomplete="off" data-parsley-type="number" style="width:60px;">
				  </td>
				  <td>				  
				  <input type="text" class="form-control  special_lunch_price" id="special_lunch_price" name="special_lunch_price['.$SpecialIncrementId.'][]" value="'.$editlunch_price.'" data-parsley-required automcomplete="off" data-parsley-type="number" style="width:60px;">
				  </td>
				  
				  <td>				  
				  <input type="text" class="form-control  special_dinner_price" id="special_dinner_price" name="special_dinner_price['.$SpecialIncrementId.'][]" value="'.$editdinner_price.'" data-parsley-required automcomplete="off" data-parsley-type="number" style="width:60px;">
				  </td>';
				  
				  
				  
				  
				  
				  
				  
				  $availableData .='<td><input type="text" class="form-control  special_tax_room" id="special_tax_room" name="special_tax_room['.$SpecialIncrementId.'][]" value="'.$edittax_room.'" data-parsley-required automcomplete="off" data-parsley-type="number" style="width:60px;"></td>
				  
				  <td><label class="switchCheck"><input type="checkbox" '.$statusCheck.'  name="special_detail_status['.$SpecialIncrementId.'][]" id="special_detail_status"><span class="slider round"></span></label></td>';	
				 if($CountNumber=='1'){ 
				 $uniqueCode=1;
				  $AddTextBoxSpecialRate	   = "AddTextBoxSpecialRate('".$uniqueCode."','".$SpecialIncrementId."')";
			  $availableData .='<td><button type="button" name="add" class="btn btn-success btn-sm add" onClick="'.$AddTextBoxSpecialRate.';"><span class="glyphicon glyphicon-plus"></span></button></td>';
				 }
				$availableData .='</tr>';	
				
				
				$CountNumber++;
				
			}//Foreach
				$availableData .= ' </table>';
				$availableData .= ' <div id="TextBoxContainerSpecialRate_'.$SpecialIncrementId.'"></div>';
				$availableData .= ' <div class="form-group col-sm-12"><label for="remarks">Remarks </label><textarea rows="2"  class="form-control " name="special_hotel_remarks['.$SpecialIncrementId.'][]" id="special_hotel_remarks" rows="1" placeholder="Enter Remarks" automcomplete="off" >'.$edithotel_remarks.'</textarea></div>
				</div>';
					}
				
			}else{ //EDIT=============================================================
			
		$uniqueCode = 'SPECIAL'.rand(0000,9999);
		$SpecialIncrementId	=0;	
			$editsql = executeSql("SELECT * FROM `".TBL_RATE."` as a join `".TBL_RATE_DETAILS_SPECIAL."` as b where  a.`company_id` = '".addslashes($_POST['company_id'])."' AND										
										a.`rate_level_id` = '".addslashes($DuplicateID['1'])."' AND 
										a.`rate_category_id` = '".addslashes($DuplicateID['0'])."' AND hotel_id='".addslashes($hotelId)."' AND a.`id_shop` = '".addslashes($_SESSION['shop'])."'  AND a.id=b.rate_id ");
	

				
			//$disabled = 'disabled="disabled"';

	
			
////////////////////////////show grid data////////////////////////////////////////////////////////
$resRoom = executeSql("SELECT rt.name, ahr.hotel_id,ahr.inventory, ahr.room_id,ahr.double_pax_price from `".TBL_ASSIGN_HOTEL_ROOM."` ahr left join `".TBL_ROOM_TYPE."` rt on ahr.room_id = rt.id where ahr.status='1' and rt.status='1' and ahr.hotel_id='".addslashes($hotelId)."'");


$resInclusion = executeSql("SELECT * from `".TBL_RATE_DETAILS_SPECIAL."` where hotel_id='".addslashes($hotelId)."' and rate_id='".addslashes(encryptor('decrypt',$_REQUEST['id']))."'");
$rowInclusion = $db->fetch_object2($resInclusion);

if(num_rows($resInclusion)==0){
	$OnKeyClickAddSpecialRate	   = "AddSpecialRateDiv('".$uniqueCode."','".$SpecialIncrementId."')";
	$availableData .= '
	<div class="box" style="border-top:none;">
            
             
			  <div class="btn btn-block btn-primary" style="float: left;padding: 7px;"> <p  style="float: left;margin: 4px;margin-right: 18px;" class="box-title">Special Rates :</p> <button style="float: left;" type="button" name="add" class="btn btn-success btn-sm add" onClick="'.$OnKeyClickAddSpecialRate.';"><span class="glyphicon glyphicon-plus"></span></button></div>
			 
  
            <input type="hidden" id="SpecialIncrementId" name="SpecialIncrementId" value="'.$SpecialIncrementId.'">	
				  	  <input type="hidden" id="blockIncrementId" name="blockIncrementId" value="'.$SpecialIncrementId.'">
			
	';
}
/*$availableData .= '<div class="alert alert-success alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<p><i class="icon fa fa-check"></i> 
						sdfasd'.selectColumn(TBL_HOTELS,'name'," WHERE status='1' AND `id` = '".addslashes($hotelId)."'").' will be added to Rate Letter '.$row->rate_name.'-'.$row->sub_code.'.</p>
					 </div>';*/
					 
$availableData .= '
				  <table class="table table-hover" style="margin-bottom:none !important;">
		
		';
					

			
								
$rowRoom = $db->fetch_object2($resRoom);

//print_r($rowRoom);
$counter = 0;	
$resRatePlan = executeSql("SELECT * from `".TBL_RATE_PLAN."`  where status='1' AND `id_shop` = '".addslashes($_SESSION['shop'])."' order by display_order");

$rack_rate = $rowRoom->double_pax_price;
$rowRatePlan = $db->fetch_object2($resRatePlan);

$resRate = executeSql("SELECT `".TBL_RATE_DETAILS_SPECIAL."`.* from `".TBL_RATE."` LEFT JOIN `".TBL_RATE_DETAILS_SPECIAL."` ON `".TBL_RATE."`.id= `".TBL_RATE_DETAILS_SPECIAL."`.rate_id  where `".TBL_RATE."`.id='".$id ."' and `".TBL_RATE_DETAILS_SPECIAL."`.room_id='".$rowRoom->room_id."' and `".TBL_RATE_DETAILS_SPECIAL."`.rate_plan_id='".$rowRatePlan->id."'  and `".TBL_RATE_DETAILS_SPECIAL."`.rate_assign_id='".$rowInclusion->id."'");

$rate_plan_id = $rowRatePlan->id;
$roomId = $rowRoom->room_id;

/////planType is pkgCounter to check how many pkg are created ////////

$planType = '0';


$resCat_rooms22 = executeSql("SELECT rt.name, ahr.hotel_id,ahr.inventory, ahr.room_id,ahr.double_pax_price from `fs_assign_hotel_room` ahr left join `fs_room_type` rt on ahr.room_id = rt.id where ahr.status='1' and rt.status='1' and ahr.hotel_id=".addslashes($hotelId));											  
while($rowInclusion22 = $db->fetch_object2($resCat_rooms22))
	{
	$y[] = $rowInclusion22;
	}
foreach($y as $rest){
											  
	$outputasd .= '<option value="'.$rest->room_id.'">'.ucfirst($rest->name).'</option>';
			}




$inclusionDetail= json_decode($rowInclusion->inclusion_detail,true);	
$inclusion = explode(',',$rowRatePlan->inclusion);
$availableData .= '<tr id="rateMaster|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'">';

	
foreach($inclusion as $value){
$resRateInclusion = executeSql("SELECT * from `".TBL_RATE_INCLUSION."`  where status='1' and id='".addslashes($value)."' AND `id_shop` = '".addslashes($_SESSION['shop'])."' limit 0,1");
$rowRateInclusion = $db->fetch_object2($resRateInclusion);
if($value !=''){
					if($inclusionDetail[$rowRateInclusion->id]!=''){
						$inclusionValue = $inclusionDetail[$rowRateInclusion->id];
					}else{
						$inclusionValue = 0;
					}
$availableData .= '<input type="hidden" class="form-control  inclusion|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'" placeholder="Enter price" id="inclusion|'.$rowRateInclusion->id.'|'.$rowRateInclusion->type.'" name="inclusion_food|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'[]" value="'.$inclusionValue.'" data-parsley-required data-parsley-type="digits" onkeyup="rateCalAll(this.id,this.value);">
					  ';
	}
}	
		  $editstart_date 		=	$editrow->start_date;
			$editend_date 			=	$editrow->end_date;

	
$availableData .= '<input type="hidden" id="edit_id" name="edit_id['.$SpecialIncrementId.'][]" value="'.$editid_id.'" >';	
//$availableData .= '<input type="hidden" id="data_id|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'" name="data_id[]" value="|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'" >';	


							 
					
				$availableData3 = '<td><select class="form-control"  name="special_room_type_id['.$SpecialIncrementId.'][]" id="special_room_type_id" data-parsley-required  '.$disabled.' >
										  <option value="">Room Type</option>';
										  $resCat_rooms = executeSql("SELECT rt.name, ahr.hotel_id,ahr.inventory, ahr.room_id,ahr.double_pax_price from `fs_assign_hotel_room` ahr left join `fs_room_type` rt on ahr.room_id = rt.id where ahr.status='1' and rt.status='1' and ahr.hotel_id=".addslashes($hotelId));
										  
											while($rowInclusion = $db->fetch_object2($resCat_rooms)){
												if($editroom_id == $rowInclusion->room_id){
												   $selected = 'selected="selected"';
												}else{
													$selected = '';
												}
												
												$availableData3 .= '<option '.$selected.' value="'.$rowInclusion->room_id.'">'.ucfirst($rowInclusion->name).'</option>';
											
										  }
											 $availableData3 .= '</select></td>';

					$availableData1 = '<td><select class="form-control " name="special_rate_plan_id['.$SpecialIncrementId.'][]" id="special_rate_plan_id"  data-parsley-required '.$disabled.' >
											  <option value="">Rate Plan</option>';
	  $resCat = selectSql(TBL_RATE_PLAN," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ",'  order by display_order');
											 
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($editrate_plan_id == $resultCat->id){
													   $selected3 = 'selected="selected"';
													}else{
														$selected3 = '';
													}
													
													
													
													$availableData1 .= '<option '.$selected3.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
													$Add_rateplan[] = $resultCat;
												}
											 	 $availableData1 .= '</select></td>';
												 
												 foreach($Add_rateplan as $Add_rateplan_result){
											  
														$Add_rateplan_results .= '<option '.$selected3.' value="'.$Add_rateplan_result->id.'">'.ucfirst($Add_rateplan_result->name).'</option>';
													}
													

							 
				  
				 
				 
				  
					
				
				
				
				
					
			
			
				
			 $availableData .= '</div></div>';	
				$availableData .= ' </div>';
				}				 
											 
 	
			
	
	//}

			
		 $availableData .= ' <div id="AddLoopSpecialRate"></div>';
			
			 
		


					  
					



			  
echo $availableData;
?>




<script type="text/javascript">
function GetDynamicTextBoxSpecialRate(value,SpecialIncrementId){
	
	
	//var SpecialIncrementId = $('#SpecialIncrementId').val();
	
	
    return '<table class="table table-hover" style="margin-bottom:none !important;"><tr><td><select name="special_room_type_id['+SpecialIncrementId+'][]" id="special_room_type_id[]" data-parsley-required  class="form-control room_type_id" ><option value="">Room Type</option><?php echo $outputasd; ?></select></td>' + '<td style="width: 86px;"><select name="special_rate_plan_id['+SpecialIncrementId+'][]" id="special_rate_plan_id['+SpecialIncrementId+'][]" class="form-control rate_plan_id" data-parsley-required  ><option value="">Rate Plan</option><?php echo $Add_rateplan_results; ?></select></td>'+'<td><input type="text" class="form-control  special_single_pax_price" id="special_single_pax_price[]" name="special_single_pax_price['+SpecialIncrementId+'][]" value="0" data-parsley-required automcomplete="off" data-parsley-type="number" style="width:63px;"></td>' + '<?php echo $WeekendDisplysingle_pax_price; ?><td><input type="text" class="form-control  double_pax_rice" id="special_double_pax_rice[]" name="special_double_pax_price['+SpecialIncrementId+'][]" value="0" data-parsley-required automcomplete="off" data-parsley-type="number" style="width:60px;"></td>'  + '<?php echo $WeekendDisplydouble_pax_price; ?><td><input class="form-control  special_extra_bed" type="text" name="special_extra_bed['+SpecialIncrementId+'][]" id="special_extra_bed[]" value="0" style="width:60px;" /></td>' + '<td><input type="text" class="form-control  special_breakfast_price" id="special_breakfast_price" name="special_breakfast_price['+SpecialIncrementId+'][]" value="" data-parsley-required automcomplete="off" data-parsley-type="number" style="width:60px;"></td>' + '<td><input type="text" class="form-control  special_lunch_price" id="special_lunch_price" name="special_lunch_price['+SpecialIncrementId+'][]" value="" data-parsley-required automcomplete="off" data-parsley-type="number" style="width:60px;"></td>' + '<td><input type="text" class="form-control  special_dinner_price" id="special_dinner_price" name="special_dinner_price['+SpecialIncrementId+'][]" value="" data-parsley-required automcomplete="off" data-parsley-type="number" style="width:60px;"></td>' + '<td><input class="form-control  tax" type="text"  id="special_tax_room[]" name="special_tax_room['+SpecialIncrementId+'][]" value="" data-parsley-required automcomplete="off" data-parsley-type="number" style="width:60px;"></td>' + '<td><label class="switchCheck"><input type="checkbox" checked="checked" name="special_detail_status['+SpecialIncrementId+'][]" id="special_detail_status[]"><span class="slider round"></span></label></td>' + '<td><button type="button" value="Remove" onclick = "RemoveTextBoxSpecialRate(this)" class="btn btn-danger btn-sm remove"><span class="glyphicon glyphicon-minus"></span></button></td></tr></table>'
}
function AddTextBoxSpecialRate(value,SpecialIncrementId) {

    var div = document.createElement('DIV');
    div.innerHTML = GetDynamicTextBoxSpecialRate(value,SpecialIncrementId);
    document.getElementById("TextBoxContainerSpecialRate_"+SpecialIncrementId).appendChild(div);
}
 
function RemoveTextBoxSpecialRate(div) {
	
	document.getElementById("TextBoxContainerSpecialRate").removeChild(table.parentNode);
  //document.getElementById("TextBoxContainer").removeChild(div.parentNode);
   
	//y.remove();
}
 
 

function GetDynamicDivSpecialRate(value,SpecialIncrementIdvalue){
	
var roomheader	='<tr><th>Room</th><th width="8%">Rate Plan</th><th>Single</th><th>Double</th><th>Extra Bed</th><th>Breakfast</th><th>Lunch</th><th>Dinner</th><th>Tax</th><th>Status</th></tr>';	
	var built	="'"+value+"','"+SpecialIncrementIdvalue+"'";
	var calenderEventList	='<select name="special_rate_name['+SpecialIncrementIdvalue+']" id="special_rate_name[]" data-parsley-required  class="form-control special_rate_name" ><option value="">---Select Special Rate Period---</option><?php echo $outputResultEventList; ?></select>';
	
    return '<div class="box box-primary  table-responsive no-padding" style="border: 1px solid #00a65a;"><div class="form-group col-sm-3" style="margin-top: 10px;"><input type="hidden" id="blockIncrementId" name="blockIncrementId" value="'+SpecialIncrementIdvalue+'"><label for="start_date">Name</label>'+calenderEventList+'</div><button type="button" value="Remove" onclick = "RemoveSpecialRateDiv(this)" class="btn btn-danger btn-sm remove" style="margin-top: 36px;"><span class="glyphicon glyphicon-minus"></span></button><div class="form-group col-sm-2" style="margin-top: 10px;"><label for="start_date">Start Date</label><div class="input-group"><div class="input-group-addon"> <i class="fa fa-calendar"></i></div><input type="text" class="form-control datepickerspecialinc" placeholder="Enter date" id="special_date_start['+SpecialIncrementIdvalue+']" name="special_date_start['+SpecialIncrementIdvalue+']" value=""  data-parsley-required></div><span id="special_date_startError"></span></div><div class="form-group col-sm-2" style="margin-top: 10px;"><label for="end_date">End Date </label><div class="input-group"><div class="input-group-addon"><i class="fa fa-calendar"></i> </div><input type="text" class="form-control datepickerspecialinc" id="special_date_end['+SpecialIncrementIdvalue+']" name="special_date_end['+SpecialIncrementIdvalue+']" value="" data-parsley-required data-parsley-errors-container="#special_date_endError" ></div></div><table class="table table-hover" style="margin-bottom:none !important;">'+roomheader+'<tr><td><select name="special_room_type_id['+SpecialIncrementIdvalue+'][]" id="special_room_type_id[]" data-parsley-required  class="form-control room_type_id" ><option value="">Room Type</option><?php echo $outputasd; ?></select></td>' + '<td style="width: 86px;"><select name="special_rate_plan_id['+SpecialIncrementIdvalue+'][]" id="special_rate_plan_id[]" class="form-control rate_plan_id" data-parsley-required  ><option value="">Rate Plan</option><?php echo $Add_rateplan_results; ?></select></td>'+'<td><input type="text" class="form-control  special_single_pax_price" id="special_single_pax_price" name="special_single_pax_price['+SpecialIncrementIdvalue+'][]" value="" data-parsley-required automcomplete="off" data-parsley-type="number" style="width:63px;"></td>' + '<?php echo $WeekendDisplysingle_pax_price; ?><td><input type="text" class="form-control  special_double_pax_rice" id="special_double_pax_rice[]" name="special_double_pax_price['+SpecialIncrementIdvalue+'][]" value="" data-parsley-required automcomplete="off" data-parsley-type="number" style="width:60px;"></td>'  + '<?php echo $WeekendDisplydouble_pax_price; ?><td><input class="form-control  special_extra_bed" type="text" name="special_extra_bed['+SpecialIncrementIdvalue+'][]" id="special_extra_bed[]" value="0" style="width:60px;" /></td>' + '<td><input type="text" class="form-control  special_breakfast_price" id="special_breakfast_price" name="special_breakfast_price['+SpecialIncrementIdvalue+'][]" value="" data-parsley-required automcomplete="off" data-parsley-type="number" style="width:60px;"></td>' + '<td><input type="text" class="form-control  special_lunch_price" id="special_lunch_price" name="special_lunch_price['+SpecialIncrementIdvalue+'][]" value="" data-parsley-required automcomplete="off" data-parsley-type="number" style="width:60px;"></td>' + '<td><input type="text" class="form-control  special_dinner_price" id="special_dinner_price" name="special_dinner_price['+SpecialIncrementIdvalue+'][]" value="" data-parsley-required automcomplete="off" data-parsley-type="number" style="width:60px;"></td>' + '<td><input class="form-control  tax" type="text"  id="special_tax_room[]" name="special_tax_room['+SpecialIncrementIdvalue+'][]" value="" data-parsley-required automcomplete="off" data-parsley-type="number" style="width:60px;"></td>' + '<td><label class="switchCheck"><input type="checkbox" checked="checked" name="special_detail_status['+SpecialIncrementIdvalue+'][]" id="special_detail_status"><span class="slider round"></span></label></td>' + '<td><button type="button" value="Add" onclick = "AddTextBoxSpecialRate('+built+')" class="btn btn-success btn-sm add"><span class="glyphicon glyphicon-plus"></span></button></td></tr></table><div id="TextBoxContainerSpecialRate_'+SpecialIncrementIdvalue+'"></div><div class="form-group col-sm-12"><label for="remarks">Remarks </label><textarea rows="2"  class="form-control " name="special_hotel_remarks['+SpecialIncrementIdvalue+'][]" id="special_hotel_remarks" rows="1" placeholder="Enter Remarks" automcomplete="off" ></textarea></div></div>'

	
}
 

 function AddSpecialRateDiv(value,SpecialIncrementId) {
	 
	
	 var SpecialIncrementId = $('#SpecialIncrementId').val();
	 var SpecialIncrementIdvalue	=+SpecialIncrementId+ + +1;
	$( "#SpecialIncrementId" ).val(SpecialIncrementIdvalue);
	
    var div = document.createElement('DIV');
    div.innerHTML = GetDynamicDivSpecialRate(value,SpecialIncrementIdvalue);
    document.getElementById("AddLoopSpecialRate").appendChild(div);
	
$(".datepickerspecialinc").datepicker({
	dateFormat : 'dd-mm-yy',
	minDate : new Date('<?php echo date('Y-m-d',strtotime($dateStart) ); ?>'),
	maxDate: new Date('<?php echo date('Y-m-d',strtotime($dateEnd) ); ?>')
});

	}
 
 function RemoveSpecialRateDiv(div) {
	
	 var SpecialIncrementId = $('#SpecialIncrementId').val();
	 var SpecialIncrementIdvalue	=+SpecialIncrementId+ - +1;
	$( "#SpecialIncrementId" ).val(SpecialIncrementIdvalue);
	document.getElementById("AddLoopSpecialRate").removeChild(table.parentNode);
  //document.getElementById("TextBoxContainer").removeChild(div.parentNode);
   
	//y.remove();
}
 
 
 
 
function RecreateDynamicTextboxes() {
    var values = eval('<%=Values%>');
	
    if (values != null) {
        var html = "";
        for (var i = 0; i < values.length; i++) {            		
			html += "<div>" + room_type_id(values[i]) + "</div>";
			html += "<div>" + rate_plan_id(values[i]) + "</div>";	
        }
        document.getElementById("TextBoxContainerSpecialRate").innerHTML = html;
    }
}
	window.onload = RecreateDynamicTextboxes;
	
</script>

<script type="text/javascript">
   function addTextArea(){}
		
	$(document).on('click', '.remove', function(){
  		$(this).closest('div').remove();
 	});
   
   
   
function roomtypevalue(k){
	
alert(k);

var room_type_id = $('#room_type_id').val();
var rate_plan_id = $('#rate_plan_id').val();
alert(room_type_id);
alert(rate_plan_id)/*
 var ratePointId = $('#rate_points option:selected').val();
 var ratePointDetail = $('#ratePointDetail').val();*/
	$.ajax({
	   type: "GET",
	   url: 'ajax/ajaxRoomTypeValue.php',
	   data: 'room_type_id='+room_type_id+'&rate_plan_id='+rate_plan_id, 
	   success: function (result) {	
					$( "#ratePoinData" ).html(result);
					$('#ratePoint').popup({
        			transition: 'all 0.3s',
           			 autoopen: true,            
        			});
		}
	})
}

   
   
   
   
   
   
   
   
    </script>
   <script>





function Creditallow(id_company,rate_id){
 var form1=$("#availabiltyForm");	
 var dataString = $("#availabiltyForm").serialize();	
	if(form1.parsley().validate()){
		$.ajax({
		   type: "POST",
		   url: 'ajax/ajaxCreditallow.php',
		   data: dataString+'&id_company='+id_company+'&rate_id='+rate_id, 
		   success: function (result) {					
				$( "#Creditallow_value" ).html(result);								
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
