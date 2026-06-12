<?php include_once("../../config/auto_loader.php");
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$ratePlanId = $_REQUEST['ratePlanId'];
$roomId = $_REQUEST['roomId'];
$rack_rate = $_REQUEST['rack_rate'];
/////planType is pkgCounter to check how many pkg are created ////////
$planType = $_REQUEST['pkgCounter'];

$inclusionDetail = array_combine($_REQUEST['inclusion_id'],$_REQUEST['inclusion_detail']);

//////executing query for rate plan and inclusion///////////////////////
$resRatePlan = executeSql("SELECT * from `".TBL_RATE_PLAN."`  where status='1' and id='".addslashes($ratePlanId)."' Limit 1");
$rowRatePlan = $db->fetch_object2($resRatePlan);

$room_price = $rack_rate;
$single_pax_price = $rack_rate;
$double_pax_price = $rack_rate;
$extra_bed = 0;
$tax_room = 18;
$discountFlat = 0;
$discountPercent = 0;
$extra_bed_price = 0;
$extra_bed = 0;
$selected='';
$statusCheck = 'checked="checked"';
$displayFlat = 'style="display:none;"';	
$displayPercent = 'style="display:block;"';
$pkg = '<span class="label label-danger" style="cursor:pointer;" id="pkgMaster|'.$roomId.'|'.$ratePlanId.'|'.$planType.'">Edit PKG</span>';	
$availableData .= '<tr id="rateMaster|'.$roomId.'|'.$ratePlanId.'|'.$planType.'">';
$availableData .= '<td style="vertical-align:middle !important;"></td>';	
$inclusion = explode(',',$rowRatePlan->inclusion);
foreach($inclusion as $value){
$resRateInclusion = executeSql("SELECT * from `".TBL_RATE_INCLUSION."`  where status='1' and id='".addslashes($value)."' limit 0,1");
$rowRateInclusion = $db->fetch_object2($resRateInclusion);
if($value !=''){
					if($inclusionDetail[$rowRateInclusion->id]!=''){
						$inclusionValue = $inclusionDetail[$rowRateInclusion->id];
					}else{
						$inclusionValue = 0;
					}
$availableData .= '<input type="hidden" class="form-control input-sm inclusion|'.$roomId.'|'.$ratePlanId.'|'.$planType.'" placeholder="Enter price" id="inclusion|'.$rowRateInclusion->id.'|'.$rowRateInclusion->type.'" name="inclusion_food|'.$roomId.'|'.$ratePlanId.'|'.$planType.'" value="'.$inclusionValue.'" data-parsley-required data-parsley-type="digits" onkeyup="rateCalAll(this.id,this.value);">
					  ';
	}
}	
	
		  
$availableData .= '  <input type="hidden" id="data_id|'.$roomId.'|'.$ratePlanId.'|'.$planType.'" name="data_id[]" value="|'.$roomId.'|'.$ratePlanId.'|'.$planType.'" >
					<td ><span class="label label-success">'.$rowRatePlan->name.'-PKG</span></td>
				  <input type="hidden" id="rate_plan_id|'.$roomId.'|'.$ratePlanId.'|'.$planType.'" name="rate_plan_id|'.$roomId.'|'.$ratePlanId.'|'.$planType.'" value="'.$ratePlanId.'" >
				  <input type="hidden" id="room_id|'.$roomId.'|'.$ratePlanId.'|'.$planType.'" name="room_id|'.$roomId.'|'.$ratePlanId.'|'.$planType.'" value="'.$roomId.'" >
				  <input type="hidden" id="plan_type|'.$roomId.'|'.$ratePlanId.'|'.$planType.'" name="plan_type|'.$roomId.'|'.$ratePlanId.'|'.$planType.'" value="1" >
				  <input type="hidden" id="title|'.$roomId.'|'.$ratePlanId.'|'.$planType.'" name="title|'.$roomId.'|'.$ratePlanId.'|'.$planType.'" value="" >
				  <input type="hidden" id="description|'.$roomId.'|'.$ratePlanId.'|'.$planType.'" name="description|'.$roomId.'|'.$ratePlanId.'|'.$planType.'" value="" >
				  <input type="hidden" id="min_pax|'.$roomId.'|'.$ratePlanId.'|'.$planType.'" name="min_pax|'.$roomId.'|'.$ratePlanId.'|'.$planType.'" value="1" >
				  
				  
				  <td><input type="text" class="form-control input-sm rack_rate" id="rack_rate|'.$roomId.'|'.$ratePlanId.'|'.$planType.'" name="rack_rate|'.$roomId.'|'.$ratePlanId.'|'.$planType.'" value="'.$rack_rate.'" data-parsley-required automcomplete="off" data-parsley-type="digits" readonly="readonly" style="width:60px;" ></td>
				  
				  <td><input type="text" class="form-control input-sm" placeholder="Enter min nights" id="min_nights|'.$roomId.'|'.$ratePlanId.'|'.$planType.'" name="min_nights|'.$roomId.'|'.$ratePlanId.'|'.$planType.'" value="1" data-parsley-required data-parsley-type="digits"></td>
				  
				  <td> <select class="form-control input-sm discount_type" name="discount_type|'.$roomId.'|'.$ratePlanId.'|'.$planType.'" id="discount_type|'.$roomId.'|'.$ratePlanId.'|'.$planType.'" onchange="rateCalSingle(this.id,this.value);"><option value="2" '.$selected.'>Percentage</option><option value="1" '.$selected.'>Flat</option></select></td>
				  
				  <td id="flat|'.$roomId.'|'.$ratePlanId.'|'.$planType.'" '.$displayFlat.'><div class="input-group"><span class="input-group-addon"><i class="fa fa-inr"></i> </span><input type="text" class="form-control input-sm discountFlat" id="discountFlat|'.$roomId.'|'.$ratePlanId.'|'.$planType.'" name="discountFlat|'.$roomId.'|'.$ratePlanId.'|'.$planType.'" value="'.$discountFlat.'" data-parsley-required automcomplete="off" data-parsley-type="digits" data-parsley-maxlength="6" style="width:60px;" onkeyup="rateCalSingle(this.id,this.value);"></div></td>
				  
				  <td id="percent|'.$roomId.'|'.$ratePlanId.'|'.$planType.'" '.$displayPercent.' ><div class="input-group"><input type="text" class="form-control input-sm discountPercent" id="discountPercent|'.$roomId.'|'.$ratePlanId.'|'.$planType.'" name="discountPercent|'.$roomId.'|'.$ratePlanId.'|'.$planType.'" value="'.$discountPercent.'" data-parsley-required automcomplete="off" data-parsley-type="digits" data-parsley-maxlength="2" style="width:60px;" onkeyup="rateCalSingle(this.id,this.value);"><span class="input-group-addon" ><i class="fa fa-percent"></i></span></div></td>
				  
				  <input type="hidden" class="form-control input-sm room_price" id="room_price|'.$roomId.'|'.$ratePlanId.'|'.$planType.'" name="room_price|'.$roomId.'|'.$ratePlanId.'|'.$planType.'" value="'.$room_price.'" data-parsley-required automcomplete="off">
				  
				 <div class="input-group" style="display:none;"><span class="input-group-addon fadeandscale_open" id="single_popup|'.$roomId.'|'.$ratePlanId.'|'.$planType.'" onclick="rateCalSinglePopUp(this.id,\'room_price\',\'single_pax_rice\');"><i class="fa fa-pencil"></i></span> <input type="hidden" class="form-control input-sm single_pax_rice" id="single_pax_rice|'.$roomId.'|'.$ratePlanId.'|'.$planType.'" name="single_pax_price|'.$roomId.'|'.$ratePlanId.'|'.$planType.'" value="'.$single_pax_price.'" data-parsley-required automcomplete="off" readonly="readonly" style="width:60px;"></div>
				 
				  <td>
				  <div class="input-group"><span class="input-group-addon fadeandscale_open" id="double_popup|'.$roomId.'|'.$ratePlanId.'|'.$planType.'" onclick="rateCalSinglePopUp(this.id,\'room_price\',\'double_pax_rice\');" onclick="rateCalSinglePopUp(this.id);"><i class="fa fa-pencil"></i></span><input type="text" class="form-control input-sm double_pax_rice" id="double_pax_rice|'.$roomId.'|'.$ratePlanId.'|'.$planType.'" name="double_pax_price|'.$roomId.'|'.$ratePlanId.'|'.$planType.'" value="'.$double_pax_price.'" data-parsley-required automcomplete="off" data-parsley-type="digits" readonly="readonly" style="width:60px;">
				  </div></td>
				 
				  <td><div class="input-group"><span class="input-group-addon fadeandscale_open" id="extra_bed_popup|'.$roomId.'|'.$ratePlanId.'|'.$planType.'" onclick="rateCalSinglePopUp(this.id,\'extra_bed\',\'extra_bed_price\');"><i class="fa fa-pencil"></i></span><input type="text" class="form-control input-sm extra_bed" id="extra_bed_price|'.$roomId.'|'.$ratePlanId.'|'.$planType.'" name="extra_bed_price|'.$roomId.'|'.$ratePlanId.'|'.$planType.'" value="'.$extra_bed_price.'" data-parsley-required automcomplete="off" data-parsley-type="digits" readonly="readonly" style="width:60px;"></div>
				   <input type="hidden" name="extra_bed|'.$roomId.'|'.$ratePlanId.'|'.$planType.'" id="extra_bed|'.$roomId.'|'.$ratePlanId.'|'.$planType.'" value="'.$extra_bed.'" />
				  </td>
				  
				  <td><input type="text" class="form-control input-sm tax" id="tax_room|'.$roomId.'|'.$ratePlanId.'|'.$planType.'" name="tax_room|'.$roomId.'|'.$ratePlanId.'|'.$planType.'" value="'.$tax_room.'" data-parsley-required automcomplete="off" data-parsley-type="digits" readonly="readonly" style="width:60px;"></td>
				  
				  <td><label class="switchCheck"><input type="checkbox" '.$statusCheck.' name="status|'.$roomId.'|'.$ratePlanId.'|'.$planType.'" id="status|'.$roomId.'|'.$ratePlanId.'|'.$planType.'"><span class="slider round"></span></label></td>	
				  <td>'.$pkg.'</td>
				
				</tr>';
		
				

			  
echo $availableData;
?>