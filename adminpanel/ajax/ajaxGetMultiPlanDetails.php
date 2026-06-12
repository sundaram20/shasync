<?php include_once("../../config/auto_loader.php");
/////////////////////////////////////////////////////////////////////////////////////////////////////

$hotel_id = $_REQUEST['hotel_id'];
$reservation_date = explode(' to ',$_REQUEST['reservation_date']);
$checkin_date = $reservation_date[0];
$checkout_date = $reservation_date[1];

$SlecteduniqueCode	=	$_REQUEST['uniqueCode'];
$rate_id = $_REQUEST['rate_id'];
$id_company = $_REQUEST['id_company'];
if($hotel_id !='' && $rate_id !='' && $id_company !='' && $checkin_date != '' && $checkout_date !=''){

/////////////calculate no of days //////////////////////////////////////
$days =  abs((strtotime($reservation_date['0']) - strtotime($reservation_date['1']))/ 86400 );
if($days == '0'){
	$noOfDays = '1';
}else {
	$noOfDays = $days;
}

//////////////////////////////getting rate data on edit//////////////////////////////////////////////////////
$sql = "  SELECT `".TBL_RATE."`.*,`".TBL_RATE_ASSIGN_DETAILS."`.hotel_id, `".TBL_RATE_ASSIGN_DETAILS."`.id as rate_assign_id,  `".TBL_RATE_ASSIGN_DETAILS."`.inclusion_detail FROM `".TBL_RATE."` RIGHT JOIN  `".TBL_RATE_ASSIGN_DETAILS."` ON `".TBL_RATE."`.id= `".TBL_RATE_ASSIGN_DETAILS."`.rate_id WHERE `".TBL_RATE."`.`id` = '".addslashes($rate_id)."' and ((start_date <=  '".date('Y-m-d',strtotime($checkin_date))."' and end_date >= '".date('Y-m-d',strtotime($checkout_date))."') OR ( start_date between '".date('Y-m-d',strtotime($checkin_date))."' and '".date('Y-m-d',strtotime($checkout_date))."') OR ( end_date between '".date('Y-m-d',strtotime($checkin_date))."' and '".date('Y-m-d',strtotime($checkout_date))."')) and hotel_id='".addslashes($hotel_id)."' ";

	$db->query($sql);
	if($db->num_rows() > 0){
		$row = $db->fetch_object();
		
		$resRoom = executeSql("SELECT rt.name as room_name, rp.name as rate_name, rd.* from `".TBL_RATE_DETAILS."` rd left join `".TBL_ROOM_TYPE."` rt on rd.room_id = rt.id left join `".TBL_RATE_PLAN."` rp on rd.rate_plan_id = rp.id where rd.status='1' and  rt.status='1' and rd.rate_assign_id='".addslashes($row->rate_assign_id)."' and rd.rate_id='".addslashes($row->id)."'  and  hotel_id!='0'  order by rd.room_id");		
//and hotel_id='".addslashes($hotel_id)."'
$availableData .= '<div class="clearfix"></div>
						<div class="box box-danger no-padding">';


$inclusionDetail= json_decode($row->inclusion_detail,true);					
	


$availableData .=' <div class="form-group col-sm-2" style="width: 211px;"> 
	  <label>Checkin -checkout</label> 
	  <div>'.date('M d Y',strtotime($checkin_date)).' To '.date('M d Y',strtotime($checkout_date)).'</div> 
  </div> ';


 
		
$availableData .= '</div>';

$availableData .= '<div class="box box-success  table-responsive no-padding">
				  <table class="table table-bordered table-striped" >
					<tr >
					  <th>Date</th>
					  <th>Plan</th>					
					  <th>Rate</th>
					  <th>Tax</th>
					  <th>Inclusive Tax</th>
					  
					</tr>';	
$counter = 0;	
$reservationDate = explode(' to ',$_REQUEST['reservation_date']);

$startDate = $reservationDate['0'];	

do{ $ii=0;
	
		$UniqueDate	=date('Y-m-d',strtotime($startDate));				  
$availableData .= '<tr id="planDetail|'.$roomId.'|'.$ratePlanId.'|'.$planType.'" style="text-align:center;">';


$availableData .= '<td><span class="label label-success pull-left">'.addslashes(date('Y-m-d',strtotime($startDate))).'</span> </td>';	  

$availableData .=' <td><strong><select class="form-control " name="rate_plan_id[]" id="rate_plan_id|'.$SlecteduniqueCode.'"  data-parsley-required  onchange="getRateEdit($(this).attr(\'id\'));" >
											  <option value="">Rate Plan</option>';
	  $resCat = selectSql(TBL_RATE_PLAN," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ",'  order by display_order');
											 
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($rowOrderDetail->rate_plan_id == $resultCat->id){
													   $selected3 = 'selected="selected"';
													}else{
														$selected3 = '';
													}
													
													
													
													$availableData .= '<option '.$selected3.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
													
												}
											 	 $availableData .= '</select></strong></td></strong></td>';
												 
												 
$availableData .='<td id="trafficprice_'.$SlecteduniqueCode.'"><input type="text" class="form-control input-sm"   name="tarrif[]"  id="tarrif|'.$SlecteduniqueCode.'" value="'.round(($_SESSION['editCart']['tarrif'][$UniqueDate][$uniqueCode]),0,PHP_ROUND_HALF_UP).'" style="width: 80px;" data-parsley-type="digits" onKeyUp="getRateEdit(\'tarrif|'.$SlecteduniqueCode.'\');" '.$disabledq.'></td>';


$availableData .='<td><input type="text" class="form-control input-sm"  name="tax[]"  id="tax|'.$SlecteduniqueCode.'"  value="'.round($_SESSION['editCart']['tax'][$uniqueCode],0,PHP_ROUND_HALF_UP).'" style="width: 80px;" data-parsley-type="digits" onKeyUp="getRateEdit(\'tax|'.$SlecteduniqueCode.'\');"></td>';
				
		

$availableData .='<td><input type="text" class="form-control input-sm"  name="inclusive_tax[]"  id="inclusive_tax|'.$SlecteduniqueCode.'"  value="'.round($_SESSION['editCart']['inclusive_tax'][$SlecteduniqueCode],0,PHP_ROUND_HALF_UP).'" style="width: 80px;" data-parsley-type="digits" onKeyUp="getRateEdit(\'inclusive_tax|'.$SlecteduniqueCode.'\');"></td>';


$availableData .= '</tr>';

$startDate = date ("Y-m-d", strtotime("+1 day", strtotime($startDate)));

}
while (strtotime($startDate) < strtotime($reservationDate[1]));




			

 	$availableData .= '  </table>
            </div>';			  
	echo $availableData;
		
}else {
	
	
	
	
	$availableData .= '<div class="clearfix"></div>
						<div class="box box-danger no-padding">';


$inclusionDetail= json_decode($row->inclusion_detail,true);					
	


$availableData .=' <div class="form-group col-sm-2" style="width: 211px;"> 
	  <label>Checkin -checkout</label> 
	  <div>'.date('M d Y',strtotime($checkin_date)).' To '.date('M d Y',strtotime($checkout_date)).'</div> 
  </div> ';


 
		
$availableData .= '</div>';

$availableData .= '<div class="box box-success  table-responsive no-padding">
				  <table class="table table-bordered table-striped" >
					<tr >
					  <th>Date</th>
					  <th>Plan</th>					
					  <th>Rate</th>
					  <th>Tax</th>
					  
					  
					</tr>';	
$counter = 0;	
$reservationDate = explode(' to ',$_REQUEST['reservation_date']);
$startDate = $reservationDate['0'];	
	
do{ $ii=0;
	
		 $UniqueDate	=date('d-m-Y',strtotime($startDate));	
			
$availableData .= '<tr id="planDetail|'.$roomId.'|'.$ratePlanId.'|'.$planType.'" style="text-align:center;">';


$availableData .= '<td><span class="label label-success pull-left">'.addslashes($UniqueDate).'</span> </td>';	  

$availableData .=' <td><strong><select class="form-control " name="rate_plan_id[]" id="rate_plan_id|'.$uniqueCode.'"  data-parsley-required  onchange="getRateEdit($(this).attr(\'id\'));" >
											  <option value="">Rate Plan</option>';
	  $resCat = selectSql(TBL_RATE_PLAN," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ",'  order by display_order');
											 
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($_SESSION['editCart']['rate_plan_id'][$UniqueDate][$SlecteduniqueCode] == $resultCat->id){
													   $selected3 = 'selected="selected"';
													}else{
														$selected3 = '';
													}
													
													
													
													$availableData .= '<option '.$selected3.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
													
												}
											 	 $availableData .= '</select></strong></td></strong></td>';
												 
												 
$availableData .='<td id="trafficprice_'.$SlecteduniqueCode.'"><input type="text" class="form-control input-sm"   name="tarrif[]"  id="tarrif|'.$SlecteduniqueCode.'" value="'.round($_SESSION['editCart']['tarrif'][$UniqueDate][$SlecteduniqueCode]).'" style="width: 80px;" data-parsley-type="digits" onKeyUp="getRateEdit(\'tarrif|'.$SlecteduniqueCode.'\');" '.$disabledq.'></td>';


$availableData .='<td><input type="text" class="form-control input-sm"  name="tax[]"  id="tax|'.$SlecteduniqueCode.'"  value="'.round($_SESSION['editCart']['tax'][$UniqueDate][$SlecteduniqueCode],0,PHP_ROUND_HALF_UP).'" style="width: 80px;" data-parsley-type="digits" onKeyUp="getRateEdit(\'tax|'.$SlecteduniqueCode.'\');"></td>';
				
		




$availableData .= '</tr>';

$startDate = date ("Y-m-d", strtotime("+1 day", strtotime($startDate)));

}
while (strtotime($startDate) < strtotime($reservationDate[1]));



	//echo 'No Rate Assigned for this hotel.';	
	$availableData .= '  </table><div class="box-footer" style="float: left;width: 98%;">
                <input type="submit" value="Apply" class="btn btn-primary" name="Save" >


              </div>
            </div>';			  
	echo $availableData;
}
}else{
echo 'There is some error. Please try again.';

}
?>