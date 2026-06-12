<?php include_once("../../config/auto_loader.php");

checkUserLevelPermission($_SESSION['userLevel'],TBL_AGENT_BUDGET,'update');
$conn = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);




//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//print_r($_REQUEST);

$hotelId = $_REQUEST['hotelId'];

//$room_id = implode(',',$_REQUEST['roomId'])	;
$season = $_REQUEST['seasonId'];

//$id = encryptor('decrypt',$_REQUEST['hotelId']);


$start_date	=	selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'");		

$end_date	=selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$_REQUEST['seasonId']."'");



if($_REQUEST['hotelId']!=''){
	$editRowvalue = executeSql("SELECT * FROM `".TBL_UNIT_AGENT_BUDGET."` as a  where  a.`id_shop` = '".addslashes($_SESSION['shop'])."' and  a.`id_user`='".$_REQUEST['hotelId']."' AND season_id='".$_REQUEST['seasonId']."' AND id_hotel='".$_REQUEST['id_hotel']."' ");
}

//////////////////////////////getting rate data on edit//////////////////////////////////////////////////////

$CountNumber_row	=	num_rows($editRowvalue); 

if($_REQUEST['hotelId']!='' && $CountNumber_row > 0){


	
	 //EDIT
////////////////////////////show grid data////////////////////////////////////////////////////////
$availableData .= '<div class="box box-success  table-responsive no-padding">

				  <table class="table table-hover" style="margin-bottom:none !important;">

		

		<tr>

		<th>Agent\'s  Budget For Financial Year    ('.date('d-M-Y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).' to '.date('d-M-Y',strtotime(selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).')</th>

			

		<th>Room Nights</th>	

		<!--<th>Value</th>-->	

		

		</tr>';

 	/*$resCat_rooms2 ="SELECT A.name,A.id_company FROM `".TBL_COMPANY."` AS A 
 				  LEFT JOIN `".TBL_AREAS."` AS B ON A.area=B.id
 				  WHERE  B.user_id=".$_REQUEST['hotelId']." AND A.id_shop=".$_SESSION['shop']." AND A.name !='' ORDER BY A.name";*/

 	$resCat_rooms2 ="SELECT A.name,A.id_company FROM `".TBL_COMPANY."` AS A 
 				  LEFT JOIN `".TBL_AREAS."` AS B ON A.area=B.id
 				  WHERE  FIND_IN_SET('".$_REQUEST['hotelId']."',B.ids_unit_user) AND A.id_shop=".$_SESSION['shop']." AND A.name !='' ORDER BY A.name";	


 	$resCat_rooms2;
 		$totalValue=0;			  
 	$resCat_rooms1 = mysqli_query($conn,$resCat_rooms2);
	while($rowHotelResult = mysqli_fetch_object($resCat_rooms1)){

		
		$availableData .= '<tr id="rateMaster|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'">';
	    $editstart_date 		=	$editrow->start_date;
		$editend_date 			=	$editrow->end_date;

	$availableData .= '<input type="hidden" id="data_id" name="data_id[]" value="'.$rowHotelResult->id_company.'" >';	



$availableData .= '<input type="hidden" id="bugetHotel" name="bugetHotel[]" value="'.$rowHotelResult->id_company.'" >';
$availableData .= '<td>'.selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$rowHotelResult->id_company."'").'</td>'; 
$start_date	=	selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'");		
$end_date	=selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$_REQUEST['seasonId']."'");	

$rnBudget = selectColumn(TBL_UNIT_AGENT_BUDGET,'SUM(room_nights)','WHERE id_company="'.$rowHotelResult->id_company.'" AND season_id="'.$_REQUEST['seasonId'].'" AND id_user="'.$_REQUEST['hotelId'].'" AND  id_hotel="'.$_REQUEST['id_hotel'].'" ');

if($rnBudget==''){
	$rnBudget=0;
}


	 $availableData .= '<td>

				  <input type="text" class="form-control  buget_qty " id="buget_qty|'.$DateValue.'|'.$hotelId.'|'.$rowHotelResult->id_company.'" name="buget_qty|'.$rowHotelResult->id_company.'[]" onkeyup="calcTotal(this.id,this.value);" value="'.$rnBudget.'"  automcomplete="off" data-parsley-type="number" style="width:60px;">
				  		<input id="prevbuget_qty|'.$DateValue.'|'.$hotelId.'|'.$rowHotelResult->id_company.'" type="hidden" value="'.$rnBudget.'">

				  </td>';
	 $availableData .= ' <td><input disabled="disabled" type="hidden" class="form-control  tax" id="buget_value|'.$DateValue.'|'.$hotelId.'|'.$rowHotelResult->id.'" name="buget_value|'.$rowHotelResult->id_company.'[]" value="'.$rowHotelResult->value.'" automcomplete="off" data-parsley-type="number" style="width:60px;"></td>';	
				  
			  		  



$availableData .='</tr>';
$totalValue+=$rnBudget;
}
$availableData .='<tr><td style="font-weight:bold;">Total</td><td><input style="width:60px;" class="form-control" name="totalValue" id="totalValue" readonly type="text" value="'.$totalValue.'"></td></tr>';					
// -------------------Bottom Total END----------------------------------------------------
}else{ //EDIT


////////////////////////////show grid data////////////////////////////////////////////////////////
$availableData .= '<div class="box box-success  table-responsive no-padding">

				  <table class="table table-hover" style="margin-bottom:none !important;">

		

		<tr>

		<th>Agent\'s  Budget For Financial Year    ('.date('d-M-Y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).' to '.date('d-M-Y',strtotime(selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).')</th>

			

		<th>Room Nights</th>	

		<!--<th>Value</th>-->	

		

		</tr>';

 	$resCat_rooms2 ="SELECT A.name,A.id_company FROM `".TBL_COMPANY."` AS A 
 				  LEFT JOIN `".TBL_AREAS."` AS B ON A.area=B.id
 				  WHERE  FIND_IN_SET(".$_REQUEST['hotelId'].",B.ids_unit_user) AND A.id_shop=".$_SESSION['shop']." AND A.name !='' ORDER BY A.name";

 	$resCat_rooms1 = mysqli_query($conn,$resCat_rooms2);
	while($rowHotelResult = mysqli_fetch_object($resCat_rooms1)){
		$availableData .= '<tr id="rateMaster|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'">';
	    $editstart_date 		=	$editrow->start_date;
		$editend_date 			=	$editrow->end_date;

$availableData .= '<input type="hidden" id="data_id" name="data_id[]" value="'.$rowHotelResult->id_company.'" >';	



$availableData .= '<input type="hidden" id="bugetHotel" name="bugetHotel[]" value="'.$rowHotelResult->id_company.'" >';
$availableData .= '<td>'.$rowHotelResult->name.'</td>'; 
$start_date	=	selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'");		
$end_date	=selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$_REQUEST['seasonId']."'");								 

	 $availableData .= '<td>

				  <input type="text" class="form-control  buget_qty" id="buget_qty|'.$DateValue.'|'.$hotelId.'|'.$rowHotelResult->id_company.'" name="buget_qty|'.$rowHotelResult->id_company.'[]" value="0" data-parsley-required automcomplete="off" data-parsley-type="number" style="width:60px;">

				  </td>';
	 $availableData .= ' <td><input disabled="disabled" type="hidden" class="form-control  tax" id="buget_value|'.$DateValue.'|'.$hotelId.'|'.$rowHotelResult->id.'" name="buget_value|'.$rowHotelResult->id_company.'[]" value="0" data-parsley-required automcomplete="off" data-parsley-type="number" style="width:60px;"></td>';	
				  
			  		  



$availableData .='</tr>';

	}
}				 

											 

$availableData .='</table>	';
//}
$availableData .= '  
            </div>';
echo $availableData;

?>
