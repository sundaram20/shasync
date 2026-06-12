<?php include_once("../../config/auto_loader.php");
/////////////////////////////////////////////////////////////////////////////////////////////////////


$hotel_id = $_REQUEST['hotel_id'];
$room_id = $_REQUEST['room_id'];
$reservation_date = explode(' to ',$_REQUEST['reservation_date']);
$checkinDate = $reservation_date[0];
$checkoutDate = $reservation_date[1];
$days =  abs((strtotime($reservation_date['0']) - strtotime($reservation_date['1']))/ 86400 );
if($days == '0'){
	$noOfDays = '1';
}else {
	$noOfDays = $days;
}
////////////////////////////////////////////////////////////////////////////////////////////////////
echo '|||';
/////////////////////////////executing query///////////////////////////////////////////////////////////
$resRoom = executeSql("SELECT rt.name as room_name, rp.name as rate_name, rd.* from `".TBL_RATE_DETAILS."` rd left join `".TBL_ROOM_TYPE."` rt on rd.room_id = rt.id left join `".TBL_RATE_PLAN."` rp on rd.rate_plan_id = rp.id where rd.status='1' and rt.status='1' and rd.rate_assign_id='".addslashes($_REQUEST['rate_assign_id'])."'  and rd.rate_plan_id='".addslashes($_REQUEST['rate_plan_id'])."' and rd.rate_id='".addslashes($_REQUEST['rate_id'])."' and room_id='".addslashes($_REQUEST['room_id'])."' order by rd.room_id");	

$ratePlan = executeSql("SELECT * FROM `".TBL_RATE_PLAN."` where id_shop='".addslashes($_SESSION['shop'])."' and status='1' and id='".addslashes($_REQUEST['rate_plan_id'])."'");
$rowPlan = $db->fetch_object2($ratePlan);

if(num_rows($resRoom) >0){
$uniqueCode = 'CODE'.rand(0000,9999);
$rowRoom = $db->fetch_object2($resRoom);

////////////////////////setting the value in session /////////////////////////////
if($_REQUEST['type'] == 1){
$_SESSION['bookCart']['room_price'][$uniqueCode] = $rowRoom->pkg_price;
$_SESSION['bookCart']['pkg_extra'][$uniqueCode] = $rowRoom->pkg_extra_price;
$_SESSION['bookCart']['pkg_description'][$uniqueCode] = $rowRoom->pkg_description;
$_SESSION['bookCart']['tarrif_price'][$uniqueCode] = $rowRoom->pkg_tarrif_price;
$type='1';
}else{
$_SESSION['bookCart']['room_price'][$uniqueCode] = $rowRoom->double_pax_price;
$_SESSION['bookCart']['pkg_extra'][$uniqueCode] = 0;
$_SESSION['bookCart']['pkg_description'][$uniqueCode] = 'N/A';
$_SESSION['bookCart']['tarrif_price'][$uniqueCode] = $rowRoom->room_price;
$type='0';
}
$_SESSION['bookCart']['inclusion_food'][$uniqueCode] = $rowRoom->inclusion_food+$rowRoom->inclusion_food;




$_SESSION['bookCart']['hotel_id'] = $_REQUEST['hotel_id'];
$_SESSION['bookCart']['noOfDays'] = $noOfDays;
$_SESSION['bookCart']['reservation_date'] = $_REQUEST['reservation_date'];
$_SESSION['bookCart']['rate_id'] = $_REQUEST['rate_id'];
$_SESSION['bookCart']['id_company'] = $_REQUEST['id_company'];
$_SESSION['bookCart']['id_guest'] = $_REQUEST['id_guest'];
$_SESSION['bookCart']['id_contacts'] = $_REQUEST['id_contacts'];
$_SESSION['bookCart']['dataValue'][$uniqueCode] = 'dateValue|'.$_REQUEST['hotel_id'].'|'.$_REQUEST['room_id'].'|'.$_REQUEST['rate_id'].'|'.$_REQUEST['rate_plan_id'].'|'.$_REQUEST['rate_assign_id'].'|'.$type;
$_SESSION['bookCart']['room_quantity'][$uniqueCode] = '1';
$_SESSION['bookCart']['adult_no'][$uniqueCode] = '2';
$_SESSION['bookCart']['infant_no'][$uniqueCode] = '0';
$_SESSION['bookCart']['child_no'][$uniqueCode] = '0';
$_SESSION['bookCart']['type'] = $_REQUEST['book_type'];
$_SESSION['bookCart']['series'] = $_REQUEST['series'];
$_SESSION['bookCart']['operator'] = $_REQUEST['operator'];





////////////////////////setting the value in session end/////////////////////////////
$availableData = '<tr id="'.$uniqueCode.'" class="ajaxAddRoom">';
$availableData .=' <td><strong><select class="form-control"  name="room_type_id[]" id="room_type_id|'.$uniqueCode.'" data-parsley-required  '.$disabled.'>
											  <option value="">Room Type</option>';
											  $resCat_rooms = executeSql("SELECT rt.name, ahr.hotel_id,ahr.inventory, ahr.room_id,ahr.double_pax_price from `fs_assign_hotel_room` ahr left join `fs_room_type` rt on ahr.room_id = rt.id where ahr.status='1' and rt.status='1' and ahr.hotel_id=".addslashes($hotel_id));
											  
											  	while($rowInclusion = $db->fetch_object2($resCat_rooms)){
													if($rowOrderDetail->room_id == $rowInclusion->room_id){
													   $selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													
													$availableData .= '<option '.$selected.' value="'.$rowInclusion->room_id.'">'.ucfirst($rowInclusion->name).'</option>';
												
											  }
											 	 $availableData .= '</select><strong></td>';
												 	
$availableData .=' <td ><strong><select class="form-control " name="rate_plan_id[]" id="rate_plan_id"  data-parsley-required   >
											  <option value="">Rate Plan</option>';
	  $resCat = selectSql(TBL_RATE_PLAN," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ",'  order by display_order');
											 
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($_REQUEST['rate_plan_id'] == $resultCat->id){
													   $selected3 = 'selected="selected"';
													}else{
														$selected3 = '';
													}
													
													
													
													$availableData .= '<option '.$selected3.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
													
												}
											 	 $availableData .= '</select></strong></td>';
												 
												 				
$availableData .=' <td> <select class="form-control input-sm" name="room_quantity[]" id="room_quantity|'.$uniqueCode.'" data-parsley-required onchange="getRate($(this).attr(\'id\'));" >';
                 
for ($i=1; $i<=100; $i++)
    				{
        
            $availableData .='<option value="'.$i.'"';
			 if($_SESSION['bookCart']['room_quantity'][$uniqueCode] == $i){
			 $availableData .='selected="selected"';
			 }
			 
			 $availableData .='>'.$i.'</option>';
       
    }	
	
				  
                $availableData .='</select></td>
				<input type="hidden" name="uniqueCode[]" value="'.$uniqueCode.'" id="uniqueCode|'.$uniqueCode.'">
				<input type="hidden" name="dataValue[]" value="dateValue|'.$_REQUEST['hotel_id'].'|'.$_REQUEST['room_id'].'|'.$_REQUEST['rate_id'].'|'.$_REQUEST['rate_plan_id'].'|'.$_REQUEST['rate_assign_id'].'|'.$type.'" id="dataValue|'.$uniqueCode.'">
				  <td>  <select class="form-control input-sm" name="adult_no[]" id="adult_no|'.$uniqueCode.'" data-parsley-required  onchange="getRate($(this).attr(\'id\'));">
                  <option value="1" >1</option>
				  <option value="2" selected="selected">2</option>
				  <option value="3" >3</option>
                </select></td>
				 <td> <select class="form-control input-sm" name="infant_no[]" id="infant_no|'.$uniqueCode.'" data-parsley-required onchange="getRate($(this).attr(\'id\'));">
                   <option value="0" >0</option>
				   <option value="1" >1</option>
				   <option value="2" >2</option>
                </select></td>
				  <td> <select class="form-control input-sm" name="child_no[]" id="child_no|'.$uniqueCode.'" data-parsley-required onchange="getRate($(this).attr(\'id\'));">
                   <option value="0" >0</option>
				   <option value="1" >1</option>
				   <option value="2" >2</option>
                </select></td>
				  <td id="price_'.$uniqueCode.'"><strong><i class="fa fa-inr"></i> '.$_SESSION['bookCart']['room_price'][$uniqueCode]*$_SESSION['bookCart']['noOfDays'].'</strong>&nbsp;&nbsp;<span class="pricePopUp_open" onclick="pricePopUp(this.id);" id="pricePopUp_'.$uniqueCode.'" ><i class="fa fa-pencil"></i></span></td>  
				  <td> <a class="btn btn-danger btn-sm" href="javascript:void(0);"  id="'.$uniqueCode.'" onclick="ajaxRoomRemove($(this).attr(\'id\'));");">
				  <i class="fa fa-trash-o fa-lg"></i> </a></td>              
                </tr>';

}else{
	
$uniqueCode = 'CODE'.rand(0000,9999);
$rowRoom = $db->fetch_object2($resRoom);

////////////////////////setting the value in session /////////////////////////////
if($_REQUEST['type'] == 1){
$_SESSION['bookCart']['room_price'][$uniqueCode] = $rowRoom->pkg_price;
$_SESSION['bookCart']['pkg_extra'][$uniqueCode] = $rowRoom->pkg_extra_price;
$_SESSION['bookCart']['pkg_description'][$uniqueCode] = $rowRoom->pkg_description;
$_SESSION['bookCart']['tarrif_price'][$uniqueCode] = $rowRoom->pkg_tarrif_price;
$type='1';
}else{
$_SESSION['bookCart']['room_price'][$uniqueCode] = $rowRoom->double_pax_price;
$_SESSION['bookCart']['pkg_extra'][$uniqueCode] = 0;
$_SESSION['bookCart']['pkg_description'][$uniqueCode] = 'N/A';
$_SESSION['bookCart']['tarrif_price'][$uniqueCode] = $rowRoom->room_price;
$type='0';
}
$_SESSION['bookCart']['inclusion_food'][$uniqueCode] = $rowRoom->inclusion_food+$rowRoom->inclusion_food;




$_SESSION['bookCart']['hotel_id'] = $_REQUEST['hotel_id'];
$_SESSION['bookCart']['noOfDays'] = $noOfDays;
$_SESSION['bookCart']['reservation_date'] = $_REQUEST['reservation_date'];
$_SESSION['bookCart']['rate_id'] = $_REQUEST['rate_id'];
$_SESSION['bookCart']['id_company'] = $_REQUEST['id_company'];
$_SESSION['bookCart']['id_guest'] = $_REQUEST['id_guest'];
$_SESSION['bookCart']['id_contacts'] = $_REQUEST['id_contacts'];
$_SESSION['bookCart']['dataValue'][$uniqueCode] = 'dateValue|'.$_REQUEST['hotel_id'].'|'.$_REQUEST['room_id'].'|'.$_REQUEST['rate_id'].'|'.$_REQUEST['rate_plan_id'].'|'.$_REQUEST['rate_assign_id'].'|'.$type;
$_SESSION['bookCart']['room_quantity'][$uniqueCode] = '1';
$_SESSION['bookCart']['adult_no'][$uniqueCode] = '2';
$_SESSION['bookCart']['infant_no'][$uniqueCode] = '0';
$_SESSION['bookCart']['child_no'][$uniqueCode] = '0';
$_SESSION['bookCart']['type'] = $_REQUEST['book_type'];
$_SESSION['bookCart']['series'] = $_REQUEST['series'];
$_SESSION['bookCart']['operator'] = $_REQUEST['operator'];





////////////////////////setting the value in session end/////////////////////////////
$availableData = '<tr id="'.$uniqueCode.'" class="ajaxAddRoom">';

$availableData .=' <td><strong><select class="form-control"  name="room_type_id[]" id="room_type_id|'.$uniqueCode.'" data-parsley-required  '.$disabled.'>
											  <option value="">Room Type</option>';
											  $resCat_rooms = executeSql("SELECT rt.name, ahr.hotel_id,ahr.inventory, ahr.room_id,ahr.double_pax_price from `fs_assign_hotel_room` ahr left join `fs_room_type` rt on ahr.room_id = rt.id where ahr.status='1' and rt.status='1' and ahr.hotel_id=".addslashes($hotel_id));
											  
											  	while($rowInclusion = $db->fetch_object2($resCat_rooms)){
													if($rowOrderDetail->room_id == $rowInclusion->room_id){
													   $selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													
													$availableData .= '<option '.$selected.' value="'.$rowInclusion->room_id.'">'.ucfirst($rowInclusion->name).'</option>';
												
											  }
											 	 $availableData .= '</select><strong></td>';
												 	
$availableData .=' <td ><strong><select class="form-control " name="rate_plan_id[]" id="rate_plan_id"  data-parsley-required   >
											  <option value="">Rate Plan</option>';
	  $resCat = selectSql(TBL_RATE_PLAN," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ",'  order by display_order');
											 
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($_REQUEST['rate_plan_id'] == $resultCat->id){
													   $selected3 = 'selected="selected"';
													}else{
														$selected3 = '';
													}
													
													
													
													$availableData .= '<option '.$selected3.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
													
												}
											 	 $availableData .= '</select></strong></td>';
												 
												 				
$availableData .=' <td> <select class="form-control input-sm" name="room_quantity[]" id="room_quantity|'.$uniqueCode.'" data-parsley-required onchange="getRate($(this).attr(\'id\'));" >';
                 
for ($i=1; $i<=100; $i++)
    				{
        
            $availableData .='<option value="'.$i.'"';
			 if($_SESSION['bookCart']['room_quantity'][$uniqueCode] == $i){
			 $availableData .='selected="selected"';
			 }
			 
			 $availableData .='>'.$i.'</option>';
       
    }	
	
				  
                $availableData .='</select></td>
				<input type="hidden" name="uniqueCode[]" value="'.$uniqueCode.'" id="uniqueCode|'.$uniqueCode.'">
				<input type="hidden" name="dataValue[]" value="dateValue|'.$_REQUEST['hotel_id'].'|'.$_REQUEST['room_id'].'|'.$_REQUEST['rate_id'].'|'.$_REQUEST['rate_plan_id'].'|'.$_REQUEST['rate_assign_id'].'|'.$type.'" id="dataValue|'.$uniqueCode.'">
				  <td>  <select class="form-control input-sm" name="adult_no[]" id="adult_no|'.$uniqueCode.'" data-parsley-required  onchange="getRate($(this).attr(\'id\'));">
                  <option value="1" >1</option>
				  <option value="2" selected="selected">2</option>
				  <option value="3" >3</option>
                </select></td>
				 <td> <select class="form-control input-sm" name="infant_no[]" id="infant_no|'.$uniqueCode.'" data-parsley-required onchange="getRate($(this).attr(\'id\'));">
                   <option value="0" >0</option>
				   <option value="1" >1</option>
				   <option value="2" >2</option>
                </select></td>
				  <td> <select class="form-control input-sm" name="child_no[]" id="child_no|'.$uniqueCode.'" data-parsley-required onchange="getRate($(this).attr(\'id\'));">
                   <option value="0" >0</option>
				   <option value="1" >1</option>
				   <option value="2" >2</option>
                </select></td>
				  <td id="price_'.$uniqueCode.'"><strong><i class="fa fa-inr"></i> '.$_SESSION['bookCart']['room_price'][$uniqueCode]*$_SESSION['bookCart']['noOfDays'].'</strong>&nbsp;&nbsp;<span class="pricePopUp_open" onclick="pricePopUp(this.id);" id="pricePopUp_'.$uniqueCode.'" ><i class="fa fa-pencil"></i></span></td>  
				  <td> <a class="btn btn-danger btn-sm" href="javascript:void(0);"  id="'.$uniqueCode.'" onclick="ajaxRoomRemove($(this).attr(\'id\'));");">
				  <i class="fa fa-trash-o fa-lg"></i> </a></td>              
                </tr>';


echo 'There is some error. Please Try again.';

}
echo $availableData;




	unset($_SESSION['bookCart']['taxablePrice']);
	unset($_SESSION['bookCart']['discountType']);
	unset($_SESSION['bookCart']['discountVar']);
	unset($_SESSION['bookCart']['discountPrice']);
	unset($_SESSION['bookCart']['totalPrice']);
	unset($_SESSION['bookCart']['totalRoom']);
	unset($_SESSION['bookCart']['totalAdult']);
	unset($_SESSION['bookCart']['totalChild']);
	unset($_SESSION['bookCart']['totalInfant']);
	unset($_SESSION['bookCart']['totalPriceTarrif']);
	unset($_SESSION['bookCart']['totalPriceFood']);
	unset($_SESSION['bookCart']['totalPriceExtra']);
?>