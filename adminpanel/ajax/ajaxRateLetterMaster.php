<?php include_once("../../config/auto_loader.php");

checkUserLevelPermission($_SESSION['userLevel'],TBL_RATE,'update');
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


 $hotelId = $_REQUEST['hotelId'];


$allow_multiple_date_rates	=selectColumn(TBL_DOCUMENT_CONFIG,'allow_multiple_date_rates'," WHERE status='1' AND `id_shop` = '".addslashes($_SESSION['shop'])."' AND `doc_type`='1'");
$season = $_REQUEST['seasonId'];
$id = encryptor('decrypt',$_REQUEST['id']);
//echo "SELECT * FROM `".TBL_RATE."` as a join `".TBL_RATE_DETAILS."` as b where a.b.hotel_id='".addslashes($hotelId)."' AND a.`id_shop` = '".addslashes($_SESSION['shop'])."'  AND a.id=b.rate_id ";

$DuplicateID	=	explode('|',$_REQUEST['rate_level_id']);
$resRoom = executeSql("SELECT rt.name, ahr.hotel_id,ahr.inventory, ahr.room_id,ahr.double_pax_price from `".TBL_ASSIGN_HOTEL_ROOM."` ahr left join `".TBL_ROOM_TYPE."` rt on ahr.room_id = rt.id where ahr.status='1' and rt.status='1' and ahr.hotel_id='".addslashes($hotelId)."'");


$resInclusion = executeSql("SELECT * from `".TBL_RATE_DETAILS."` where hotel_id='".addslashes($hotelId)."' and rate_id='".addslashes(encryptor('decrypt',$_REQUEST['id']))."'");
$rowInclusion = $db->fetch_object2($resInclusion);



$GetDisplyWeekEnd	=	selectColumn(TBL_HOTELS,'excel_display_weekday'," WHERE `id` = '".addslashes($hotelId)."'");


		if($GetDisplyWeekEnd 	== 1 || $GetDisplyWeekEnd == '2'){
			$WeekendDisplydouble_pax_price	=	'<td><input type="text" class="form-control  weekend_double_pax_price" id="weekend_double_pax_price[]" name="weekend_double_pax_price[]" value="" data-parsley-required automcomplete="off" data-parsley-type="number" style="width:60px;"></td> ';
			
			$WeekendDisplysingle_pax_price	=	'<td><input type="text" class="form-control  weekend_single_pax_price" id="weekend_single_pax_price[]" name="weekend_single_pax_price[]" value="" data-parsley-required automcomplete="off" data-parsley-type="number" style="width:60px;"></td> ';
			
			}else{
				$WeekendDisplydouble_pax_price	=	'';
				$WeekendDisplysingle_pax_price	=	'';
				}

if(num_rows($resInclusion)==0){
?>
<?php /*?><script>
function Toggle(id) {
			if (document.getElementById(id).style.display == "none" || document.getElementById(id).style.display == "") {
				document.getElementById(id).style.display = "block";
			} else if (document.getElementById(id).style.display == "block") {
				document.getElementById(id).style.display = "none";
			} else {
				document.getElementById(id).style.display = "none";
			}
		}
		$(document).ready(function() {
    $("input[name$='HotelDuplicateInsert']").click(function() {
        var test = $(this).val();

        $("div.desc").hide();
        $("#Cars" + test).show();
    });
});
</script>
<style>
#test01 { width:500px; padding:15px;  display:none; }
</style>
   <input type="checkbox" name="" onclick="Toggle('test01')"  /> Auto Fill Rate Letter<br /> 
   <div id="test01">
		
<form id="duplicateform name="duplicateform data-parsley-validate autocomplete="off" method="post"  action="manageRateLetters.php" >


<div id="myRadioGroup">
    <input type="radio" name="HotelDuplicateInsert"  checked="checked" value="1"  />All Hotels

<input type="radio" name="HotelDuplicateInsert" value="2" />Select Hotels

   
    <div id="Cars2" class="desc" style="display: none;" >
       <div class="form-group col-sm-3">
					  <label for="hotelId">Hotel <font color="#FF0000">*</font></label>
					  <?php $hotelDropDown = '<select class="form-control select2" name="hotelId[]" id="hotelId" style="width:300px;" multiple="multiple">
												  <option value="">Select Hotel</option>';
												  
												  if(empty($_SESSION['hotel_access'])){
													$resCat = selectSql(TBL_HOTELS," where status='1' and id_shop='".addslashes($_SESSION['shop'])."'",' ORDER BY `name`');		
												  }else{
												  $resCat = selectSql(TBL_HOTELS," where status='1' and find_in_set(id,'".$_SESSION['hotel_access']."') and id_shop='".addslashes($_SESSION['shop'])."'",' ORDER BY `name`');												}
												  if($db->num_rows2($resCat)){
													while($resultCat = $db->fetch_object2($resCat)){
														if($resultCat->id == $row->hotelId){
															$selected = 'selected="selected"';
														}else if(encryptor('decrypt',$_REQUEST['hotelId'])== $resultCat->id){
															$selected = 'selected="selected"';
														}else{
															$selected = '';
														}	
														$hotelDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
													}
												  }
													echo $hotelDropDown .= '</select>';
												  ?>
					  <span id="hotelError"><?php echo $err_hotel;?></span> </div>
    </div>
</div>



<br /><br /><br /><br /><br /><br />

 <input type="hidden" id="RateEditId" name="RateEditId" value="<?php echo encryptor('decrypt',$_REQUEST['id']); ?>" >

      <input type="hidden" id="dupId" name="dupId" value="<?php echo $DuplicateID['0']; ?>" >
       <input type="hidden" id="rate_level_id" name="rate_level_id" value="<?php echo $_REQUEST['new_rate_level_id']; ?>" >
	  <input type="hidden" id="start_date" name="start_date" value="<?php echo $_REQUEST['start_date']; ?>">
	  <input type="hidden" id="end_date" name="end_date" value="<?php echo $_REQUEST['end_date']; ?>">
      
<!--       <input type="hidden" id="hotelId" name="hotelId" value="<?php echo $_REQUEST['hotelId']; ?>" >-->
       <input type="hidden" id="market" name="market" value="<?php echo $_REQUEST['market']; ?>" >
              <input type="hidden" id="rate_category_id" name="rate_category_id" value="<?php echo $DuplicateID['0']; ?>" >
        <input type="hidden" id="seasonId" name="seasonId" value="<?php echo $_REQUEST['seasonId']; ?>" >

       <input type="hidden" id="company_id" name="company_id" value="<?php echo $_REQUEST['company_id']; ?>" >
        <input type="hidden" id="company_id" name="company_id" value="<?php echo $_REQUEST['company_id']; ?>" >
        <input type="hidden" id="id_contacts" name="id_contacts" value="<?php echo $_REQUEST['id_contacts']; ?>" >
        
     
              
      
      <input  type="submit" class="btn btn-default" name="action" value="Generate">
   
    </form>

</div><?php */?>
<?php
}
//die;

if(addslashes(encryptor('decrypt',$_REQUEST['id']))!=''){
	
	
$editRowvalue = executeSql("SELECT * FROM `".TBL_RATE."` as a join `".TBL_RATE_DETAILS."` as b where  a.`id` = '".addslashes(encryptor('decrypt',$_REQUEST['id']))."' AND hotel_id='".addslashes($hotelId)."' AND a.`id_shop` = '".addslashes($_SESSION['shop'])."'  AND a.id=b.rate_id ");


}
//////////////////////////////getting rate data on edit//////////////////////////////////////////////////////
$CountNumber_row	=	num_rows($editRowvalue); 
if(addslashes(encryptor('decrypt',$_REQUEST['id']))!='' && $CountNumber_row >0){
	
$editsql = executeSql("SELECT * FROM `".TBL_RATE."` as a join `".TBL_RATE_DETAILS."` as b where  a.`id` = '".addslashes(encryptor('decrypt',$_REQUEST['id']))."' AND hotel_id='".addslashes($hotelId)."' AND a.`id_shop` = '".addslashes($_SESSION['shop'])."'  AND a.id=b.rate_id ");
//	$editsql = executeSql("SELECT * FROM `".TBL_RATE."`
									//WHERE `id` = '".addslashes(encryptor('decrypt',$_REQUEST['id']))."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."'");
		

				
			//$disabled = 'disabled="disabled"';

	
			
////////////////////////////show grid data////////////////////////////////////////////////////////
$resRoom = executeSql("SELECT rt.name, ahr.hotel_id,ahr.inventory, ahr.room_id,ahr.double_pax_price from `".TBL_ASSIGN_HOTEL_ROOM."` ahr left join `".TBL_ROOM_TYPE."` rt on ahr.room_id = rt.id where ahr.status='1' and rt.status='1' and ahr.hotel_id='".addslashes($hotelId)."'");


$resInclusion = executeSql("SELECT * from `".TBL_RATE_DETAILS."` where hotel_id='".addslashes($hotelId)."' and rate_id='".addslashes(encryptor('decrypt',$_REQUEST['id']))."'");
$rowInclusion = $db->fetch_object2($resInclusion);

$availableData .= '<div class="alert alert-success alert-dismissible" style="padding: 9px;">';
						
						$availableData .= '<p>
						Seasonal Rates :  ';
		if($allow_multiple_date_rates==1){				
			$availableData .= '<input type="checkbox" class="icheckbox_minimal-blue" name="getSeasonalRate" id="getSeasonalRate" onclick="getSeasonalRateValue();" checked  style="margin-left:15px;margin-right:25px;"  />';
		}
						
						
						$availableData .= selectColumn(TBL_HOTELS,'name'," WHERE status='1' AND `id` = '".addslashes($hotelId)."'").' will be added to Rate Letter '.$row->rate_name.'-'.$row->sub_code.'.</p>
					 </div>';
if(num_rows($resInclusion)==0){
	$availableData .= '<div class="alert alert-success alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<p><i class="icon fa fa-check"></i> 
						'.selectColumn(TBL_HOTELS,'name'," WHERE status='1' AND `id` = '".addslashes($hotelId)."'").' will be added to Rate Letter '.$row->rate_name.'-'.$row->sub_code.'.</p>
					 </div>';
}

$availableData .= '<div class="box box-success  table-responsive no-padding" id="disbleSeasonalRate">
				  <table class="table table-hover" style="margin-bottom:none !important;">
		
		<tr>
		<th>Room</th>
		<th width="8%">Rate Plan</th>		
		<th>Single</th>';
if($GetDisplyWeekEnd == '1'){
		$availableData .= '<th>Weekend/<br>Single/<br>(FRI-SUN)</th>';	
}
if($GetDisplyWeekEnd == '2'){
		$availableData .= '<th>Weekend/<br>Single/<br>(FRI-SAT)</th>';	
}
		$availableData .= '<th>Double</th>';	
if($GetDisplyWeekEnd == '1'){		
		$availableData .= '<th>Weekend/<br>Double/<br>(FRI-SUN)</th>';	
}
if($GetDisplyWeekEnd =='2'){		
		$availableData .= '<th>Weekend/<br>Double/<br>(FRI-SAT)</th>';	
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

$resRate = executeSql("SELECT `".TBL_RATE_DETAILS."`.* from `".TBL_RATE."` LEFT JOIN `".TBL_RATE_DETAILS."` ON `".TBL_RATE."`.id= `".TBL_RATE_DETAILS."`.rate_id  where `".TBL_RATE."`.id='".$id ."' and `".TBL_RATE_DETAILS."`.room_id='".$rowRoom->room_id."' and `".TBL_RATE_DETAILS."`.rate_plan_id='".$rowRatePlan->id."'  and `".TBL_RATE_DETAILS."`.rate_assign_id='".$rowInclusion->id."'");

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
			
			
			
$resCat_2 = selectSql(TBL_RATE_PLAN," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ",'  order by display_order');
											 
			while($resultCat_2 = $db->fetch_object2($resCat_2)){
			$Add_rateplan[] = $resultCat_2;
			}
				 foreach($Add_rateplan as $Add_rateplan_result){
		  
					$Add_rateplan_results .= '<option '.$selected3.' value="'.$Add_rateplan_result->id.'">'.ucfirst($Add_rateplan_result->name).'</option>';
				}
				
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

	
$availableData .= '<input type="hidden" id="data_id" name="data_id[]" value="'.$editid_id.'" >';	
$availableData .= '<input type="hidden" id="rate_id" name="rate_id" value="'.$editrate_id.'" >';	
//$availableData .= '<input type="hidden" id="data_id|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'" name="data_id[]" value="|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'" >';	


					
					
					$availableData .= '<td><span><select class="form-control"  name="room_type_id[]" id="room_type_id" data-parsley-required  '.$disabled.'>
											  <option value="">Room Type</option>';
											  $resCat_rooms = executeSql("SELECT rt.name, ahr.hotel_id,ahr.inventory, ahr.room_id,ahr.double_pax_price from `fs_assign_hotel_room` ahr left join `fs_room_type` rt on ahr.room_id = rt.id where ahr.status='1' and rt.status='1' and ahr.hotel_id=".addslashes($hotelId));
											  
											  	while($rowInclusion = $db->fetch_object2($resCat_rooms)){
													if($editroom_id == $rowInclusion->room_id){
													   $selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													
													$availableData .= '<option '.$selected.' value="'.$rowInclusion->room_id.'">'.ucfirst($rowInclusion->name).'</option>';
												
											  }
											 	 $availableData .= '</select></td></span></td>';

					$availableData .= '<td><select class="form-control " name="rate_plan_id[]" id="rate_plan_id"  data-parsley-required '.$disabled.'  >
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
				  <input type="text" class="form-control  single_pax_price" id="single_pax_price|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'" name="single_pax_price[]" value="'.$editsingle_pax_price.'" data-parsley-required automcomplete="off" data-parsley-type="number" style="width:60px;">
				  </td>';
				  
				  if($GetDisplyWeekEnd == '1' || $GetDisplyWeekEnd == '2'){
					   $availableData .= '<td>
				  <input type="text" class="form-control  weekend_single_pax_rice" id="weekend_single_pax_price|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'" name="weekend_single_pax_price[]" value="'.$editweekend_single_pax_price.'" data-parsley-required automcomplete="off" data-parsley-type="number" style="width:60px;">
				  </td>';
				  
				}
				  
				 
				  $availableData .= '<td>				  
				  <input type="text" class="form-control  double_pax_price" id="double_pax_rice|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'" name="double_pax_price[]" value="'.$editdouble_pax_price.'" data-parsley-required automcomplete="off" data-parsley-type="number" style="width:60px;">
				  </td>';
				  
				  
				  
				if($GetDisplyWeekEnd == 1 || $GetDisplyWeekEnd == '2'){	  
				   $availableData .= '<td>				  
				  <input type="text" class="form-control  weekend_double_pax_rice" id="weekend_double_pax_rice|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'" name="weekend_double_pax_price[]" value="'.$editweekend_double_pax_price.'" data-parsley-required automcomplete="off" data-parsley-type="number" style="width:60px;">
				  </td>';
			}
			  
				  
				     $availableData .= '<td>				  
				  <input type="hidden" class="form-control  pkg_price" id="pkg_price|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'" name="pkg_price[]" value="'.$editdpkg_price.'" data-parsley-required automcomplete="off" data-parsley-type="number"  style="width:60px;">

				 
				 
				   <input class="form-control  " type="text" name="extra_bed[]" id="extra_bed" value="'.$editextra_bed.'" style="width:60px;" />
				  </td>
				  
				  
				  
					
				
				  
				  <td>				  
				  <input type="text" class="form-control  breakfast_price" id="breakfast_price|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'" name="breakfast_price[]" value="'.$editbreakfast_price.'" data-parsley-required automcomplete="off" data-parsley-type="number" style="width:60px;">
				  </td>
				  <td>				  
				  <input type="text" class="form-control  lunch_price" id="lunch_price|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'" name="lunch_price[]" value="'.$editlunch_price.'" data-parsley-required automcomplete="off" data-parsley-type="number" style="width:60px;">
				  </td>
				  
				  <td>				  
				  <input type="text" class="form-control  dinner_price" id="dinner_price|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'" name="dinner_price[]" value="'.$editdinner_price.'" data-parsley-required automcomplete="off" data-parsley-type="number" style="width:60px;">
				  </td>';
				  
				  
				  
				  
				  
				  
				  
				  $availableData .='<td><input type="text" class="form-control  tax_room" id="tax_room" name="tax_room[]" value="'.$edittax_room.'" data-parsley-required automcomplete="off" data-parsley-type="number" style="width:60px;"></td>
				  
				  <td><label class="switchCheck"><input type="checkbox" '.$statusCheck.'  value="'.$editid_id.'"  name="detail_status[]" id="detail_status"><span class="slider round"></span></label></td>';	
				 if($CountNumber=='1'){ 
				  
				  $availableData .='
				  
				 <td><button type="button" name="add" class="btn btn-success btn-sm add" onClick="AddTextBox();"><span class="glyphicon glyphicon-plus"></span></button></td>';
				 }
				$availableData .='</tr>';	
				
				
				$CountNumber++;
				
					}
			
			}else{ //EDIT
			
		
			
			$editsql = executeSql("SELECT * FROM `".TBL_RATE."` as a join `".TBL_RATE_DETAILS."` as b where  a.`company_id` = '".addslashes($_POST['company_id'])."' AND										
										a.`rate_level_id` = '".addslashes($DuplicateID['1'])."' AND 
										a.`rate_category_id` = '".addslashes($DuplicateID['0'])."' AND hotel_id='".addslashes($hotelId)."' AND a.`id_shop` = '".addslashes($_SESSION['shop'])."'  AND a.id=b.rate_id ");
	
//$editsql = executeSql("SELECT * FROM `".TBL_RATE."` as a join `".TBL_RATE_DETAILS."` as b where  a.`id` = '".addslashes(encryptor('decrypt',$_REQUEST['id']))."' AND hotel_id='".addslashes($hotelId)."' AND a.`id_shop` = '".addslashes($_SESSION['shop'])."'  AND a.id=b.rate_id ");





//	$editsql = executeSql("SELECT * FROM `".TBL_RATE."`
									//WHERE `id` = '".addslashes(encryptor('decrypt',$_REQUEST['id']))."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."'");
		

				
			//$disabled = 'disabled="disabled"';

	
			
////////////////////////////show grid data////////////////////////////////////////////////////////
$resRoom = executeSql("SELECT rt.name, ahr.hotel_id,ahr.inventory, ahr.room_id,ahr.double_pax_price from `".TBL_ASSIGN_HOTEL_ROOM."` ahr left join `".TBL_ROOM_TYPE."` rt on ahr.room_id = rt.id where ahr.status='1' and rt.status='1' and ahr.hotel_id='".addslashes($hotelId)."'");


$resInclusion = executeSql("SELECT * from `".TBL_RATE_DETAILS."` where hotel_id='".addslashes($hotelId)."' and rate_id='".addslashes(encryptor('decrypt',$_REQUEST['id']))."'");
$rowInclusion = $db->fetch_object2($resInclusion);

if(num_rows($resInclusion)==0){
	$availableData .= '<div class="alert alert-success alert-dismissible" style="padding: 9px;">
						
						<p>
						Seasonal Rates :  ';
						
if($allow_multiple_date_rates==1){
			$availableData .= '<input type="checkbox" class="icheckbox_minimal-blue" name="getSeasonalRate" id="getSeasonalRate" onclick="getSeasonalRateValue();" checked  style="margin-left:15px;margin-right:25px;"  />';
}
						
						$availableData .= selectColumn(TBL_HOTELS,'name'," WHERE status='1' AND `id` = '".addslashes($hotelId)."'").' will be added to Rate Letter '.$row->rate_name.'-'.$row->sub_code.'.</p>
					 </div>';
}


$availableData .= '<div class="box box-success  table-responsive no-padding" id="disbleSeasonalRate">
				  <table class="table table-hover" style="margin-bottom:none !important;">
		
		<tr>
		<th>Room</th>
		<th width="8%">Rate Plan</th>		
		<th>Single</th>	';
		if($GetDisplyWeekEnd == '1'){
		$availableData .= '<th>Weekend/<br>Single/<br>(FRI-SUN)</th>';	
}
if($GetDisplyWeekEnd == '2'){
		$availableData .= '<th>Weekend/<br>Single/<br>(FRI-SAT)</th>';	
}
		$availableData .= '<th>Double</th>';	
if($GetDisplyWeekEnd == '1'){		
		$availableData .= '<th>Weekend/<br>Double/<br>(FRI-SUN)</th>';	
}
if($GetDisplyWeekEnd == '2'){		
		$availableData .= '<th>Weekend/<br>Double/<br>(FRI-SAT)</th>';	
}					  
		$availableData .='<th>Extra Bed</th>
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

$resRate = executeSql("SELECT `".TBL_RATE_DETAILS."`.* from `".TBL_RATE."` LEFT JOIN `".TBL_RATE_DETAILS."` ON `".TBL_RATE."`.id= `".TBL_RATE_DETAILS."`.rate_id  where `".TBL_RATE."`.id='".$id ."' and `".TBL_RATE_DETAILS."`.room_id='".$rowRoom->room_id."' and `".TBL_RATE_DETAILS."`.rate_plan_id='".$rowRatePlan->id."'  and `".TBL_RATE_DETAILS."`.rate_assign_id='".$rowInclusion->id."'");

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

	
$availableData .= '<input type="hidden" id="data_id" name="data_id[]" value="'.$editid_id.'" >';	
//$availableData .= '<input type="hidden" id="data_id|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'" name="data_id[]" value="|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'" >';	


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
				  <input type="hidden" id="pkgCounter|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'" value="1" >';
		
					
					$availableData .= '<td><select class="form-control"  name="room_type_id[]" id="room_type_id" data-parsley-required  '.$disabled.' >
											  <option value="">Room Type</option>';
											  $resCat_rooms = executeSql("SELECT rt.name, ahr.hotel_id,ahr.inventory, ahr.room_id,ahr.double_pax_price from `fs_assign_hotel_room` ahr left join `fs_room_type` rt on ahr.room_id = rt.id where ahr.status='1' and rt.status='1' and ahr.hotel_id=".addslashes($hotelId));
											  
											  	while($rowInclusion = $db->fetch_object2($resCat_rooms)){
													if($editroom_id == $rowInclusion->room_id){
													   $selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													
													$availableData .= '<option '.$selected.' value="'.$rowInclusion->room_id.'">'.ucfirst($rowInclusion->name).'</option>';
												
											  }
											 	 $availableData .= '</select></td>';

					$availableData .= '<td><select class="form-control " name="rate_plan_id[]" id="rate_plan_id"  data-parsley-required '.$disabled.' >
											  <option value="">Rate Plan</option>';
	  $resCat = selectSql(TBL_RATE_PLAN," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ",'  order by display_order');
											 
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($editrate_plan_id == $resultCat->id){
													   $selected3 = 'selected="selected"';
													}else{
														$selected3 = '';
													}
													
													
													
													$availableData .= '<option '.$selected3.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
													$Add_rateplan[] = $resultCat;
												}
											 	 $availableData .= '</select></td>';
												 
												 foreach($Add_rateplan as $Add_rateplan_result){
											  
														$Add_rateplan_results .= '<option '.$selected3.' value="'.$Add_rateplan_result->id.'">'.ucfirst($Add_rateplan_result->name).'</option>';
													}
													

							 
				  
				 
				 
				  
				$availableData .= '<td>
				  <input type="text" class="form-control  single_pax_price" id="single_pax_price|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'" name="single_pax_price[]" value="'.$editsingle_pax_price.'" data-parsley-required automcomplete="off" data-parsley-type="number" style="width:60px;">
				  </td>';
				  
				 if($GetDisplyWeekEnd 	== 1 || $GetDisplyWeekEnd == '2'){ 
				    $availableData .= '<td>
				  <input type="text" class="form-control  weekend_single_pax_rice" id="weekend_single_pax_price|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'" name="weekend_single_pax_price[]" value="'.$editweekend_single_pax_price.'" data-parsley-required automcomplete="off" data-parsley-type="number" style="width:60px;">
				  </td>';
				 }
				  
				   $availableData .= '<td>				  
				  <input type="text" class="form-control  double_pax_price" id="double_pax_rice|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'" name="double_pax_price[]" value="'.$editdouble_pax_price.'" data-parsley-required automcomplete="off" data-parsley-type="number" style="width:60px;">
				  </td>';
				  
				  
				  if($GetDisplyWeekEnd 	== 1 || $GetDisplyWeekEnd == '2'){ 
				  
				  $availableData .= '<td>				  
				  <input type="text" class="form-control  weekend_double_pax_rice" id="weekend_double_pax_rice|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'" name="weekend_double_pax_price[]" value="'.$editweekend_double_pax_price.'" data-parsley-required automcomplete="off" data-parsley-type="number" style="width:60px;">
				  </td>';
				  }
				    /*$availableData .= '<td>
				  
				  <input type="text" class="form-control  pkg_price" id="pkg_price|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'" name="pkg_price[]" value="'.$editdpkg_price.'" data-parsley-required automcomplete="off" data-parsley-type="number"  style="width:60px;">
				</td>';*/
				 
				$availableData .='  <td>
				   <input class="form-control  " type="text" name="extra_bed[]" id="extra_bed" value="'.$editextra_bed.'" style="width:60px;" />
				  </td>
				  
				  
				  
					
				
				  
				  <td>				  
				  <input type="text" class="form-control  breakfast_price" id="breakfast_price|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'" name="breakfast_price[]" value="'.$editbreakfast_price.'" data-parsley-required automcomplete="off" data-parsley-type="number" style="width:60px;">
				  </td>
				  <td>				  
				  <input type="text" class="form-control  lunch_price" id="lunch_price|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'" name="lunch_price[]" value="'.$editlunch_price.'" data-parsley-required automcomplete="off" data-parsley-type="number" style="width:60px;">
				  </td>
				  
				  <td>				  
				  <input type="text" class="form-control  dinner_price" id="dinner_price|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'" name="dinner_price[]" value="'.$editdinner_price.'" data-parsley-required automcomplete="off" data-parsley-type="number" style="width:60px;">
				  </td>';
				  
				  
				  
				  
				  
				  
				  
				  $availableData .='<td><input type="text" class="form-control  tax_room" id="tax_room" name="tax_room[]" value="'.$edittax_room.'" data-parsley-required automcomplete="off" data-parsley-type="number" style="width:60px;"></td>
				  
				  <td><label class="switchCheck"><input type="checkbox" checked="checked" name="detail_status[]" id="detail_status"><span class="slider round"></span></label></td>';	
				// if(addslashes(encryptor('decrypt',$_REQUEST['id']))==''){ 
				  
				  $availableData .='			  
				 <td><button type="button" name="add" class="btn btn-success btn-sm add" onClick="AddTextBox();"><span class="glyphicon glyphicon-plus"></span></button></td>';
				// }
				$availableData .='</tr>';	
				
				
				
				
					
			
			
				
				
				
				}				 
											 
 $availableData .='</table>
				<div id="TextBoxContainer"></div>';
				  
			$availableData .='<div class="form-group col-sm-12">
                  <label for="remarks">Remarks </label>
                  <textarea rows="2"  class="form-control " name="hotel_remarks" id="hotel_remarks" rows="1" placeholder="Enter Remarks" automcomplete="off" >'.str_replace('<br />','',$edithotel_remarks).'</textarea>
                </div>';	
			
	
	//}
 $availableData .= '  
            </div>';
		


					  
					



			  
echo $availableData;
?>




<script type="text/javascript">


//===========New Function Special Rate Letter ========================
			
				    
$(function () {
        $("#getSeasonalRate").click(function () {
            if ($(this).is(":checked")) {
				
$('#room_type_id').attr('data-parsley-required', 'true');
$('#rate_plan_id').attr('data-parsley-required', 'true');
$('.single_pax_price').attr('data-parsley-required', 'true');
$('#weekend_single_pax_rice').attr('data-parsley-required', 'true');
$('.double_pax_price').attr('data-parsley-required', 'true');
$('.weekend_double_pax_rice').attr('data-parsley-required', 'true');
$('.extra_bed').attr('data-parsley-required', 'true');
$('.breakfast_price').attr('data-parsley-required', 'true');
$('.dinner_price').attr('data-parsley-required', 'true');
$('.lunch_price').attr('data-parsley-required', 'true');
$('.tax_room').attr('data-parsley-required', 'true');
$("#disbleSeasonalRate").show();
                
            } else {
				
$('#room_type_id').attr('data-parsley-required', 'false');
$('#rate_plan_id').attr('data-parsley-required', 'false');
$('.single_pax_price').attr('data-parsley-required', 'false');
$('#weekend_single_pax_rice').attr('data-parsley-required', 'false');
$('.double_pax_price').attr('data-parsley-required', 'false');
$('.weekend_double_pax_rice').attr('data-parsley-required', 'false');
$('.extra_bed').attr('data-parsley-required', 'false');
$('.breakfast_price').attr('data-parsley-required', 'false');
$('.dinner_price').attr('data-parsley-required', 'false');
$('.lunch_price').attr('data-parsley-required', 'false');
$('.tax_room').attr('data-parsley-required', 'false');


$("#disbleSeasonalRate").hide();
                
            }
        });
    });
//===========New Function Special Rate Letter ========================	
function GetDynamicTextBox(value){
    return '<table class="table table-hover" style="margin-bottom:none !important;"><tr><td><select name="room_type_id[]" id="room_type_id[]" data-parsley-required  class="form-control room_type_id" ><option value="">Room Type</option><?php echo $outputasd; ?></select></td>' + '<td style="width: 86px;"><select name="rate_plan_id[]" id="rate_plan_id" class="form-control rate_plan_id" data-parsley-required  ><option value="">Rate Plan</option><?php echo $Add_rateplan_results; ?></select></td>'+'<td><input type="text" class="form-control  single_pax_price" id="single_pax_price[]" name="single_pax_price[]" value="' + value + '" data-parsley-required automcomplete="off" data-parsley-type="number" style="width:63px;"></td>' + '<?php echo $WeekendDisplysingle_pax_price; ?><td><input type="text" class="form-control  double_pax_rice" id="double_pax_rice[]" name="double_pax_price[]" value="" data-parsley-required automcomplete="off" data-parsley-type="number" style="width:60px;"></td>'  + '<?php echo $WeekendDisplydouble_pax_price; ?><td><input class="form-control  extra_bed" type="text" name="extra_bed[]" id="extra_bed[]" value="0" style="width:60px;" /></td>' + '<td><input type="text" class="form-control  breakfast_price" id="breakfast_price" name="breakfast_price[]" value="" data-parsley-required automcomplete="off" data-parsley-type="number" style="width:60px;"></td>' + '<td><input type="text" class="form-control  lunch_price" id="lunch_price" name="lunch_price[]" value="" data-parsley-required automcomplete="off" data-parsley-type="number" style="width:60px;"></td>' + '<td><input type="text" class="form-control  dinner_price" id="dinner_price" name="dinner_price[]" value="" data-parsley-required automcomplete="off" data-parsley-type="number" style="width:60px;"></td>' + '<td><input class="form-control  tax" type="text"  id="tax_room[]" name="tax_room[]" value="" data-parsley-required automcomplete="off" data-parsley-type="number" style="width:60px;"></td>' + '<td><label class="switchCheck"><input type="checkbox" checked="checked" name="status[]" id="status[]"><span class="slider round"></span></label></td>' + '<td><button type="button" value="Remove" onclick = "RemoveTextBox(this)" class="btn btn-danger btn-sm remove"><span class="glyphicon glyphicon-minus"></span></button></td></tr></table>'
}
function AddTextBox() {
    var div = document.createElement('DIV');
    div.innerHTML = GetDynamicTextBox("");
    document.getElementById("TextBoxContainer").appendChild(div);
}
 
function RemoveTextBox(div) {
	
	document.getElementById("TextBoxContainer").removeChild(table.parentNode);
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
        document.getElementById("TextBoxContainer").innerHTML = html;
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
