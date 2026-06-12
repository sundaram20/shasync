<?php include_once("../../config/auto_loader.php");
/////////////////////////////////////////////////////////////////////////////////////////////////////
/*echo "<pre>";
print_r($_REQUEST);
echo "</pre>";
exit;*/
$allow_multiple_date_rates	=selectColumn(TBL_DOCUMENT_CONFIG,'allow_multiple_date_rates'," WHERE status='1' AND `id_shop` = '".addslashes($_SESSION['shop'])."' AND `doc_type`='1'");
if($allow_multiple_date_rates==1){
$getSeasonalRate = isset($_POST['getSeasonalRate']) ? 1 : 0;
}else{
$getSeasonalRate =1;	
	}
$hotel_id = $_REQUEST['hotel_id'];

$reservation_date = explode(' to ',$_REQUEST['reservation_date']);
$checkin_date = $reservation_date[0];
$checkout_date = $reservation_date[1];
$rate_id = $_REQUEST['rate_id'];
$id_company = $_REQUEST['id_company'];

$companyName=selectColumn(TBL_COMPANY,'name','WHERE id_company="'.$id_company.'" AND id_shop="'.$_SESSION['shop'].'" ');

$sqlCompany="SELECT id_company,name FROM ".TBL_COMPANY." WHERE UPPER(REPLACE(name,'&AMP;','&'))='".strtoupper(str_replace('&AMP;','&',$companyName))."' ";

$objectCom=executeSql($sqlCompany);
$ids_companyArr=array();
while($rowCom = $db->fetch_object2($objectCom)){
	array_push($ids_companyArr,$rowCom->id_company);
}

$ids_company=implode(',',$ids_companyArr);


if($hotel_id !='' && $rate_id !='' && $id_company !='' && $checkin_date != '' && $checkout_date !=''){
/////////////calculate no of days //////////////////////////////////////

$area_id = selectColumn(TBL_COMPANY,'area','WHERE id_company="'.$id_company.'" ');	
$id_user = selectColumn(TBL_AREAS,'user_id','WHERE id="'.$area_id.'" ');
$handeledBy = selectColumn(TBL_USERS,'name','WHERE id="'.$id_user.'" ');
$handeledByMobile = selectColumn(TBL_USERS,'mobile','WHERE id="'.$id_user.'" ');

$days =  abs((strtotime($reservation_date['0']) - strtotime($reservation_date['1']))/ 86400 );
if($days == '0'){
	$noOfDays = '1';
}else {
	$noOfDays = $days;
}

$weekEndAvailable = selectColumn(TBL_HOTELS,'excel_display_weekday','WHERE id_shop="'.$_SESSION['shop'].'" AND id="'.$hotel_id.'" ');

if($weekEndAvailable==1){
	$singleTxt="Weekday (Single/Double)";
	$doubleTxt="Weekend (Single/Double)";
}	
else{
	$singleTxt="Single";
	$doubleTxt="Double";
}	
//////////////////////////////getting rate data on edit//////////////////////////////////////////////////////

//echo $ids_company;

$sql = "SELECT ".TBL_RATE.".* FROM ".TBL_RATE." LEFT JOIN 
".TBL_RATE_DETAILS." ON ".TBL_RATE.".id=".TBL_RATE_DETAILS.".rate_id
 WHERE date(".TBL_RATE.".start_date)<='".date('Y-m-d',strtotime($checkin_date))."' AND date(".TBL_RATE.".end_date)>='".date('Y-m-d',strtotime($checkout_date))."' AND  company_id IN (".$ids_company.") AND id_shop='".$_SESSION['shop']."' AND ".TBL_RATE.".id='".$rate_id."' AND ".TBL_RATE_DETAILS.".hotel_id='".$hotel_id ."' ";

$sqlUnit="SELECT ".TBL_RATE_UNIT.".* FROM ".TBL_RATE_UNIT." LEFT JOIN 
".TBL_RATE_DETAILS_UNIT." ON ".TBL_RATE_UNIT.".id=".TBL_RATE_DETAILS_UNIT.".rate_id
 WHERE date(".TBL_RATE_UNIT.".start_date)<='".date('Y-m-d',strtotime($checkin_date))."' AND date(".TBL_RATE_UNIT.".end_date)>='".date('Y-m-d',strtotime($checkout_date))."' AND  company_id IN (".$ids_company.") AND id_shop='".$_SESSION['shop']."' AND ".TBL_RATE_UNIT.".id='".$rate_id."' AND ".TBL_RATE_DETAILS_UNIT.".hotel_id='".$hotel_id ."'";

$resUnit = mysqli_query($connNew,$sqlUnit);
$unitRow = mysqli_num_rows($resUnit);

	$db->query($sql);
$comRow = $db->num_rows();

	if($comRow > 0){
		$row = $db->fetch_object();
		
		$queryMain = "SELECT rt.name as room_name, rp.name as rate_name, rd.* from `".TBL_RATE_DETAILS."` rd left join `".TBL_ROOM_TYPE."` rt on rd.room_id = rt.id left join `".TBL_RATE_PLAN."` rp on rd.rate_plan_id = rp.id
			LEFT JOIN `fs_assign_hotel_room`  ON `fs_assign_hotel_room`.room_id=rd.room_id AND `fs_assign_hotel_room`.hotel_id = '".$hotel_id."' 
		 where rd.status='1' and rd.detail_status='1' and  rt.status='1'  and rd.rate_id='".addslashes($row->id)."'  and  rd.hotel_id='".$hotel_id."'  order by `fs_assign_hotel_room`.display_order ";
		
		$resRoom = executeSql($queryMain);		
//and hotel_id='".addslashes($hotel_id)."'
$availableData .= '<div class="clearfix" style="font-weight:bold;font-size:15px;">Hotel : '.selectColumn(TBL_HOTELS,'name','WHERE id="'.$hotel_id.'" ').'-'.selectColumn(TBL_HOTELS,'city','WHERE id="'.$hotel_id.'" ').'
	<div class="box box-success no-padding" style="margin-bottom:0px;"></div>
	Company : '.
	selectColumn(TBL_COMPANY,'name'," WHERE id_company='".$id_company."' ").'<br>'.''.
	selectColumn(TBL_COMPANY,'address'," WHERE id_company='".$id_company."' ").'<br>'.
	selectColumn(TBL_COMPANY,'city'," WHERE id_company='".$id_company."' ").'-'.
	selectColumn(TBL_COMPANY,'postcode'," WHERE id_company='".$id_company."' ").'
	<div class="box box-success no-padding" style="margin-bottom:0px;"></div>
	Email : '.
	selectColumn(TBL_COMPANY,'email'," WHERE id_company='".$id_company."' ").'|Phone : '.
	selectColumn(TBL_COMPANY,'phone'," WHERE id_company='".$id_company."' ").'
</div>
						';


$availableData .= '<div class="box box-success no-padding">
				 <div class="form-group col-sm-12 no-padding"> 
	  			<label>Executive : '.$handeledBy.' &nbsp; &nbsp; Mobile : '.$handeledByMobile.' </label> 
	  			
  				</div>
  				</div>
 
  ';					
$availableData .= ' <div class="box box-success no-padding"><div class="form-group col-sm-2"> 
	  <label>Rate Ref.No</label> 
	  <div>'.$row->rate_name.'</div> 
  </div>';

$inclusionDetail= json_decode($row->inclusion_detail,true);					
	


$availableData .=' <div class="form-group col-sm-2" style="width: 211px;"> 
	  <label>Validity</label> 
	  <div>'.date('M d Y',strtotime($row->start_date)).' To '.date('M d Y',strtotime($row->end_date)).'</div> 
  </div> ';


  $availableData .= ' <div class="form-group col-sm-4"> 
	  <label>Market</label> 
	  <div>'.selectColumn(TBL_RATE_MARKET,'name'," WHERE `id` = '".$row->market."'").'</div> 
  </div>

  ';

  

$availableData .= ' <div class="form-group col-sm-12"> 
	  <label>Remarks</label> 
	  <div>
	  '.selectColumn(TBL_RATE_DETAILS,'hotel_remarks'," WHERE hotel_id='".$hotel_id."' AND `rate_id` = '".$row->id."'").'
	  </div> 
  </div>';		
$availableData .= '</div>';

$availableData .= '<div class="box box-success  table-responsive no-padding">
				  <table class="table table-bordered table-striped" >
					<tr >
					  <th>Room</th>
					  <th>Rate Plan</th>					
					  <th>'.$singleTxt.'</th>
					  <th>'.$doubleTxt.'</th>
					  <!--<th>Package</th>-->
					  <th>Extra Bed</th>
					  <th>Breakfast</th>
					  <th>Lunch</th>
					  <th>Dinner</th>
					</tr>';	
$counter = 0;					
while($rowRoom = $db->fetch_object2($resRoom)){
$planType = '0';
$rack_rate = $rowRoom->double_pax_price;
$ratePlanId = $rowRoom->rate_plan_id;
$roomId = $rowRoom->room_id;
$extra_bed = $rowRoom->extra_bed;

$extra_bed_price = $rowRoom->extra_bed_price;
$tax_room = $rowRoom->tax_room;
$pkg_price =  $rowRoom->pkg_price;
$_SESSION['editCart']['room_id']	=	$rowRoom->room_id;

if($weekEndAvailable==1){
	
	$singlePriceTxt=($rowRoom->single_pax_price==0?'Rate on request':$rowRoom->single_pax_price).'/'.($rowRoom->double_pax_price==0?'Rate on request':$rowRoom->double_pax_price);

	$doublePrixeTxt=($rowRoom->weekend_single_pax_price==0?'Rate on request':$rowRoom->weekend_single_pax_price).'/'.($rowRoom->weekend_double_pax_price==0?'Rate on request':$rowRoom->weekend_double_pax_price);
}	
else{
	$singlePriceTxt=($rowRoom->single_pax_price==0?'Rate on request':$rowRoom->single_pax_price);
	$doublePrixeTxt=($rowRoom->double_pax_price==0?'Rate on request':$rowRoom->double_pax_price);
}

////////////////////////////show grid data////////////////////////////////////////////////////////
$availableData .= '<tr id="planDetail|'.$roomId.'|'.$ratePlanId.'|'.$planType.'" style="text-align:center;">';


$availableData .= '<td><span class="label label-success pull-left">'.$rowRoom->room_name.'</span> </td>';

$availableData .= '<td ><span class="label label-danger pull-left">'.$rowRoom->rate_name.'</span></td>';

$availableData .= '	 <td > <div><i class="fa fa-inr"></i> '.$singlePriceTxt.' <button class="pull-left btn btn-success btn-xs" type="button" onclick="ajaxAddRoom('.$row->id.','.$row->rate_assign_id.','.$rowRoom->room_id.','.$rowRoom->rate_plan_id.',1);" ><i class="fa fa-plus-circle"></i></button></div></td>';				  
				  
$availableData .= '				  <td > <div><i class="fa fa-inr"></i> '.$doublePrixeTxt.' <button class="pull-left btn btn-success btn-xs" type="button" onclick="ajaxAddRoom('.$row->id.','.$row->rate_assign_id.','.$rowRoom->room_id.','.$rowRoom->rate_plan_id.',0);" ><i class="fa fa-plus-circle"></i></button></div></td>';
				  

$availableData .= '<td><div><i class="fa fa-inr"></i> '.$extra_bed_price.'</div></td>';

$availableData .= '<td><div><i class="fa fa-inr"></i> '.$rowRoom->breakfast_price.'</div></td>';
$availableData .= '<td><div><i class="fa fa-inr"></i> '.$rowRoom->lunch_price.'</div></td>';
$availableData .= '<td><div><i class="fa fa-inr"></i> '.$rowRoom->dinner_price.'</div></td>
				</tr>';
	$counter++;
	
	}
 	$availableData .= '  </table>
            </div>';		
            
            
        //==================================================================
        if($allow_multiple_date_rates==1){
        include_once("ajaxGetSpecialPlanDetails.php");
        }
        //==================================================================
                
	echo $availableData;
		
}
else if($unitRow>0){
	$row=mysqli_fetch_object($resUnit);
	$queryMain = "SELECT rt.name as room_name, rp.name as rate_name, rd.* from `".TBL_RATE_DETAILS_UNIT."` rd left join `".TBL_ROOM_TYPE."` rt on rd.room_id = rt.id left join `".TBL_RATE_PLAN."` rp on rd.rate_plan_id = rp.id
			LEFT JOIN `fs_assign_hotel_room`  ON `fs_assign_hotel_room`.room_id=rd.room_id AND `fs_assign_hotel_room`.hotel_id = '".$hotel_id."' 
		 where rd.status='1' and rd.detail_status='1' and  rt.status='1'  and rd.rate_id='".addslashes($row->id)."'  and  rd.hotel_id='".$hotel_id."'  order by `fs_assign_hotel_room`.display_order ";
		
		$resRoom = executeSql($queryMain);		
//and hotel_id='".addslashes($hotel_id)."'
$availableData .= '<div class="clearfix" style="font-weight:bold;font-size:15px;">Hotel : '.selectColumn(TBL_HOTELS,'name','WHERE id="'.$hotel_id.'" ').'-'.selectColumn(TBL_HOTELS,'city','WHERE id="'.$hotel_id.'" ').'
	<br>Company : '.
	selectColumn(TBL_COMPANY,'name'," WHERE id_company='".$id_company."' ").'<br>'.''.
	selectColumn(TBL_COMPANY,'address'," WHERE id_company='".$id_company."' ").'<br>'.
	selectColumn(TBL_COMPANY,'city'," WHERE id_company='".$id_company."' ").'-'.
	selectColumn(TBL_COMPANY,'postcode'," WHERE id_company='".$id_company."' ").'<br>Email : '.
	selectColumn(TBL_COMPANY,'email'," WHERE id_company='".$id_company."' ").'|Phone : '.
	selectColumn(TBL_COMPANY,'phone'," WHERE id_company='".$id_company."' ").'
</div>
						<div class="box box-danger no-padding">';
$availableData .= ' <div class="form-group col-sm-2"> 
	  <label>Rate Ref.No</label> 
	  <div>'.$row->rate_name.'</div> 
  </div>';

$inclusionDetail= json_decode($row->inclusion_detail,true);					
	


$availableData .=' <div class="form-group col-sm-2" style="width: 211px;"> 
	  <label>Validity</label> 
	  <div>'.date('M d Y',strtotime($row->start_date)).' To '.date('M d Y',strtotime($row->end_date)).'</div> 
  </div> ';


  $availableData .= ' <div class="form-group col-sm-4"> 
	  <label>Market</label> 
	  <div>'.selectColumn(TBL_RATE_MARKET,'name'," WHERE `id` = '".$row->market."'").'</div> 
  </div>';
$availableData .= ' <div class="form-group col-sm-12"> 
	  <label>Remarks</label> 
	  <div>
	  '.selectColumn(TBL_RATE_DETAILS_UNIT,'hotel_remarks'," WHERE hotel_id='".$hotel_id."' AND `rate_id` = '".$row->id."'").'
	  </div> 
  </div>';		
$availableData .= '</div>';

$availableData .= '<div class="box box-success  table-responsive no-padding">
				  <table class="table table-bordered table-striped" >
					<tr >
					  <th>Room</th>
					  <th>Rate Plan</th>					
					  <th>'.$singleTxt.'</th>
					  <th>'.$doubleTxt.'</th>
					  <!--<th>Package</th>-->
					  <th>Extra Bed</th>
					  <th>Breakfast</th>
					  <th>Lunch</th>
					  <th>Dinner</th>
					</tr>';	
$counter = 0;					
while($rowRoom = $db->fetch_object2($resRoom)){
$planType = '0';
$rack_rate = $rowRoom->double_pax_price;
$ratePlanId = $rowRoom->rate_plan_id;
$roomId = $rowRoom->room_id;
$extra_bed = $rowRoom->extra_bed;

$extra_bed_price = $rowRoom->extra_bed_price;
$tax_room = $rowRoom->tax_room;
$pkg_price =  $rowRoom->pkg_price;
$_SESSION['editCart']['room_id']	=	$rowRoom->room_id;

if($weekEndAvailable==1){
	
	$singlePriceTxt=($rowRoom->single_pax_price==0?'Rate on request':$rowRoom->single_pax_price).'/'.($rowRoom->double_pax_price==0?'Rate on request':$rowRoom->double_pax_price);

	$doublePrixeTxt=($rowRoom->weekend_single_pax_price==0?'Rate on request':$rowRoom->weekend_single_pax_price).'/'.($rowRoom->weekend_double_pax_price==0?'Rate on request':$rowRoom->weekend_double_pax_price);
}	
else{
	$singlePriceTxt=($rowRoom->single_pax_price==0?'Rate on request':$rowRoom->single_pax_price);
	$doublePrixeTxt=($rowRoom->double_pax_price==0?'Rate on request':$rowRoom->double_pax_price);
}

////////////////////////////show grid data////////////////////////////////////////////////////////
$availableData .= '<tr id="planDetail|'.$roomId.'|'.$ratePlanId.'|'.$planType.'" style="text-align:center;">';


$availableData .= '<td><span class="label label-success pull-left">'.$rowRoom->room_name.'</span> </td>';

$availableData .= '<td ><span class="label label-danger pull-left">'.$rowRoom->rate_name.'</span></td>';

$availableData .= '	 <td > <div><i class="fa fa-inr"></i> '.$singlePriceTxt.' <button class="pull-left btn btn-success btn-xs" type="button" onclick="ajaxAddRoom('.$row->id.','.$row->rate_assign_id.','.$rowRoom->room_id.','.$rowRoom->rate_plan_id.',1);" ><i class="fa fa-plus-circle"></i></button></div></td>';				  
				  
$availableData .= '				  <td > <div><i class="fa fa-inr"></i> '.$doublePrixeTxt.' <button class="pull-left btn btn-success btn-xs" type="button" onclick="ajaxAddRoom('.$row->id.','.$row->rate_assign_id.','.$rowRoom->room_id.','.$rowRoom->rate_plan_id.',0);" ><i class="fa fa-plus-circle"></i></button></div></td>';
				  

$availableData .= '<td><div><i class="fa fa-inr"></i> '.$extra_bed_price.'</div></td>';

$availableData .= '<td><div><i class="fa fa-inr"></i> '.$rowRoom->breakfast_price.'</div></td>';
$availableData .= '<td><div><i class="fa fa-inr"></i> '.$rowRoom->lunch_price.'</div></td>';
$availableData .= '<td><div><i class="fa fa-inr"></i> '.$rowRoom->dinner_price.'</div></td>
				</tr>';
	$counter++;
	
	}
 	$availableData .= '  </table>
            </div>';
            
        //==================================================================
        if($allow_multiple_date_rates==1){
        include_once("ajaxGetSpecialPlanDetails.php");
        }
        //==================================================================
        
	echo $availableData;
}
else {
	echo 'No Rate Assigned for this hotel.';	
}
}else{
echo 'There is some error. Please try again.';

}
?>