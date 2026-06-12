<?php include_once("../../config/auto_loader.php");

checkUserLevelPermission($_SESSION['userLevel'],TBL_BUDGET_MASTER,'update');

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//print_r($_REQUEST);

 $userId = $_REQUEST['userId'];

//$room_id = implode(',',$_REQUEST['roomId'])	;

$season = $_REQUEST['seasonId'];

//$id = encryptor('decrypt',$_REQUEST['userId']);

$type = $_REQUEST['type'];

$BudgetType	=" AND a.type=".$type." ";

$start_date	=	selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'");		

$end_date	=selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$_REQUEST['seasonId']."'");

if($_REQUEST['userId']!=''){
	$editRowvalue = executeSql("SELECT * FROM `".TBL_BUDGET_MASTER."` as a  where  a.`id_shop` = '".addslashes($_SESSION['shop'])."' $BudgetType and  a.`month`='".$start_date."' and a.`id_user`='".$_REQUEST['userId']."'");
}

//////////////////////////////getting rate data on edit//////////////////////////////////////////////////////

$CountNumber_row	=	num_rows($editRowvalue); 

if($_REQUEST['userId']!='' && $CountNumber_row > 0){

	 //EDIT
////////////////////////////show grid data////////////////////////////////////////////////////////
$availableData .= '<div class="box box-success  table-responsive no-padding">

				  <table class="table table-hover" style="margin-bottom:none !important;">

		<input type="hidden" id="type" name="type" value="'.$_REQUEST['type'].'" >

		<tr> 

		<th> Hotel</th>
		<th></th>
		<th>Apr - '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>		

		<th>May- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Jun- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Jul- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>				  

		<th>Aug- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Sep- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Oct- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Nov- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Dec- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Jan- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Feb- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Mar- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Total</th>	

		</tr>';

 $resCat_rooms1 = selectSql(TBL_HOTELS," where status='1' and id_shop='".addslashes($_SESSION['shop'])."'",' ORDER BY `name`  ');

while($rowHotelResult = $db->fetch_object2($resCat_rooms1)){
	
	$availableData .= '<tr>';
    $editstart_date =	$editrow->start_date;
	$editend_date 	=	$editrow->end_date;

	$availableData .= '<input type="hidden" id="data_id" name="data_id[]" value="'.$rowHotelResult->id.'" >';	
	$availableData .= '<input type="hidden" id="bugetHotel" name="bugetHotel[]" value="'.$rowHotelResult->id.'" >';
  $availableData .= '<td>'.$rowHotelResult->name.'- '.$rowHotelResult->city.'</td><td style="color: red;font-size: 12px;">Room Nights</td>'; 

	$start_date	=	selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'");		
	$end_date	=selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$_REQUEST['seasonId']."'");								 
	$sqlqu = "SELECT * FROM `".TBL_BUDGET_MASTER."` as a  where  a.`id_shop` = '".addslashes($_SESSION['shop'])."' $BudgetType and  a.`id_user`='".$_REQUEST['userId']."' and a.`seasonId`='".$_REQUEST['seasonId']."' and a.`id_hotel`='".$rowHotelResult->id."' ORDER BY a.month";

$editRowvalue = executeSql($sqlqu);
$period='3';
$financial_year_to = (date('m') > 3) ? date('Y') +1 : date('Y');
	$financial_year_from = $financial_year_to - 1;
	if(num_rows($editRowvalue) > 0){
		$mi=3;
		while($resultCat = $db->fetch_object2($editRowvalue)){
			
		    $monthPeriod=date("n", strtotime("+".$mi." month", $period));
			$OnKeyUpOne	   = 'updateBudgetRowRoomNight(this.value,'.$rowHotelResult->id.','.$monthPeriod.')';
			$incValue	="'qty'";
			$updateRowWise	   = 'updateBudgetRowWise(this.value,'.$rowHotelResult->id.','.$monthPeriod.','.$resultCat->id.','.$type.','.$_REQUEST['seasonId'].','.$incValue.','.$_REQUEST['userId'].')';
		
		 $availableData .= '<td>
		 
		  <input type="text" class="form-control  buget_qty" id="buget_qty|'.$DateValue.'|'.$userId.'|'.$rowHotelResult->id.'" name="buget_qty|'.$rowHotelResult->id.'[]" value="'.$resultCat->qty.'"  onchange="'.$OnKeyUpOne.';'.$updateRowWise.'" data-parsley-required automcomplete="off" data-parsley-type="number" style="width:60px;">
		  
		  <input type="hidden" class="form-control" id="hiddenBox'.$rowHotelResult->id.$monthPeriod.'" name="hiddenBox_'.$rowHotelResult->id.'_'.$monthPeriod.'" value="'.$resultCat->qty.'"  style="width:60px;">
		  
		  </td>';
		  $mi++;
		}
		
		$editTotalsql = executeSql("SELECT a.*, sum(qty) as totals  FROM `".TBL_BUDGET_MASTER."` as a  where  a.`id_shop` = '".addslashes($_SESSION['shop'])."' $BudgetType and  a.`id_user`='".$_REQUEST['userId']."' and a.`seasonId`='".$_REQUEST['seasonId']."' and a.`id_hotel`='".$rowHotelResult->id."'");
		$totalresultCat = $db->fetch_object2($editTotalsql);
		$availableData .= '<td style=" background-color:#367fa9;">

		
		<input type="text" class="form-control  total" id="total_'.$rowHotelResult->id.'" name="total" value="'.$totalresultCat->totals.'" style="width:60px;background-color:#367fa9; color:#fff;"></td>';	

	}else{ //NEW INSERT

		$start = $month = strtotime($start_date);
		$end = strtotime($end_date);
		$OnKeyUpOne='';
		while($month < $end){
	     $DateValue	=	date('Y-m-d', $month);
	     $month = strtotime("+1 month", $month);
		 
		   $monthPeriod=date("n", strtotime("+".$mi." month", $period));
		   $OnKeyUpOne	   = 'updateBudgetRowRoomNight(this.value,'.$rowHotelResult->id.','.$monthPeriod.')';
		  $incValue	="'qty'";
		  $updateRowWise	   = 'updateBudgetRowWise(this.value,'.$rowHotelResult->id.','.strtotime($DateValue).',0,'.$type.','.$_REQUEST['seasonId'].','.$incValue.','.$_REQUEST['userId'].')';
		
		 $availableData .= '<td>

					  <input type="text" class="form-control  buget_qty" id="buget_qty|'.$DateValue.'|'.$userId.'|'.$rowHotelResult->id.'" name="buget_qty|'.$rowHotelResult->id.'[]" value="0" onchange="'.$OnKeyUpOne.';'.$updateRowWise.'" data-parsley-required automcomplete="off" data-parsley-type="number" style="width:60px;">
 <input type="hidden" class="form-control" id="hiddenBox'.$rowHotelResult->id.$monthPeriod.'" name="hiddenBox_'.$rowHotelResult->id.'_'.$monthPeriod.'" value="'.$resultCat->qty.'"  style="width:60px;">
		  
				  </td>';
	}
 $editTotalsql = executeSql("SELECT a.*, sum(qty) as totals  FROM `".TBL_BUDGET_MASTER."` as a  where  a.`id_shop` = '".addslashes($_SESSION['shop'])."' $BudgetType and  a.`id_user`='".$_REQUEST['userId']."' and a.`seasonId`='".$_REQUEST['seasonId']."' and a.`id_hotel`='".$rowHotelResult->id."'");

		$totalresultCat = $db->fetch_object2($editTotalsql);

		$availableData .= '<td style=" background-color:#367fa9;"><input type="text" class="form-control  total" id="total_'.$rowHotelResult->id.'" name="total" value="0" style="width:60px;background-color:#367fa9; color:#fff;"></td>';

}//end of while
$availableData .='</tr>';

$availableData .='<tr>';

//============================Budget Value START				
$availableData .='<td></td><td style="color: red;font-size: 12px; width:80px;">Budget Value</td>';

	 $editRowvalue2 = executeSql("SELECT * FROM `".TBL_BUDGET_MASTER."` as a  where  a.`id_shop` = '".addslashes($_SESSION['shop'])."'  $BudgetType and a.`id_user`='".$_REQUEST['userId']."' and a.`seasonId`='".$_REQUEST['seasonId']."' and a.`id_hotel`='".$rowHotelResult->id."' ORDER BY a.month ");

		if(num_rows($editRowvalue2)>0){
			$Bmi=3;
			$OnKeyUpOnevalue='';
			 while($resultCat2 = $db->fetch_object2($editRowvalue2)){
				 
			$monthPeriodValue=date("n", strtotime("+".$Bmi." month", $period));
			
			$OnKeyUpOnevalue	   = 'updateBudgetRowValue(this.value,'.$rowHotelResult->id.','.$monthPeriodValue.')';
			$incValue			=	"'month_value'";
			$updateRowWise	   = 'updateBudgetRowWise(this.value,'.$rowHotelResult->id.','.$monthPeriodValue.','.$resultCat2->id.','.$type.','.$_REQUEST['seasonId'].','.$incValue.','.$_REQUEST['userId'].')';	 
				 
				 
			 $availableData .= ' <td><input type="text" class="form-control  tax" id="buget_value|'.$DateValue.'|'.$userId.'|'.$rowHotelResult->id.'" name="buget_value|'.$rowHotelResult->id.'[]" value="'.$resultCat2->month_value.'" onchange="'.$OnKeyUpOnevalue.';'.$updateRowWise.'" data-parsley-required automcomplete="off" data-parsley-type="number" style="width:60px;">
			 
			  <input type="hidden" class="form-control" id="hiddenBoxValue'.$rowHotelResult->id.$monthPeriodValue.'" name="hiddenBoxValue_'.$rowHotelResult->id.'_'.$monthPeriodValue.'" value="'.$resultCat2->month_value.'"  style="width:60px;">
		  
			 </td>';

	 

	$availableData .= '<input type="hidden" id="MonthDate" name="MonthDate|'.$rowHotelResult->id.'[]" value="'.$DateValue1.'" >';	

	$availableData .= '<input type="hidden" id="id" name="id|'.$rowHotelResult->id.'[]" value="'.$resultCat2->id.'" >';	
	$Bmi++;	}

			

			

	 $editRowMonthvalue = executeSql("SELECT a.*, sum(month_value) as Monthtotals FROM `".TBL_BUDGET_MASTER."` as a  where  a.`id_shop` = '".addslashes($_SESSION['shop'])."'  $BudgetType and a.`id_user`='".$_REQUEST['userId']."' and a.`seasonId`='".$_REQUEST['seasonId']."' and a.`id_hotel`='".$rowHotelResult->id."'");

			 $resulteditRowMonthvalue = $db->fetch_object2($editRowMonthvalue);

				 

			$availableData .= '<td style=" background-color:#367fa9;">
			<input type="text" class="form-control  total" id="Totalbuget_value_'.$rowHotelResult->id.'" name="Totalbuget_value" value="'.round($resulteditRowMonthvalue->Monthtotals,2).'" style="width:60px; background-color:#367fa9; color:#fff;"></td>';
			
			
}else{
	$start = $month = strtotime($start_date);
	$end = strtotime($end_date);

while($month < $end){ //New BUGET VALUE
    $DateValue1	=	date('Y-m-d', $month);
    $month = strtotime("+1 month", $month);
	$OnKeyUpOnevalue	   = 'updateBudgetRowValue(this.value,'.$rowHotelResult->id.','.$monthPeriodValue.')';
	$incValue			=	"'month_value'";
	$updateRowWise	   = 'updateBudgetRowWise(this.value,'.$rowHotelResult->id.','.strtotime($DateValue1).',0,'.$type.','.$_REQUEST['seasonId'].','.$incValue.','.$_REQUEST['userId'].')';	 
	
	 $availableData .= ' <td>
	 
	 <input type="text" class="form-control  tax" id="buget_value|'.$DateValue1.'|'.$userId.'|'.$rowHotelResult->id.'" name="buget_value|'.$rowHotelResult->id.'[]" value="0" onchange="'.$OnKeyUpOnevalue.';'.$updateRowWise.'" data-parsley-required automcomplete="off" data-parsley-type="number" style="width:60px;"></td>';
	$availableData .= '<input type="hidden" class="form-control" id="hiddenBoxValue'.$rowHotelResult->id.date('n',$month).'" name="hiddenBoxValue_'.$rowHotelResult->id.'_'.date('n',$month).'" value="'.$resultCat2->month_value.'"  style="width:60px;">';
			  //$availableData .= '<input type="hidden" id="MonthDate" name="MonthDate|'.$rowHotelResult->id.'[]" value="'.$DateValue1.'" >';	 
}


	$editTotalsql = executeSql("SELECT a.*, sum(qty) as totals  FROM `".TBL_BUDGET_MASTER."` as a  where  a.`id_shop` = '".addslashes($_SESSION['shop'])."' $BudgetType and  a.`id_user`='".$_REQUEST['userId']."' and a.`seasonId`='".$_REQUEST['seasonId']."' and a.`id_hotel`='".$rowHotelResult->id."'");

		$totalresultCat = $db->fetch_object2($editTotalsql);

		$availableData .= '<td style=" background-color:#367fa9;">
		
			
<input type="text" class="form-control  total" id="Totalbuget_value_'.$rowHotelResult->id.'" name="Totalbuget_value" value="0" style="width:60px; background-color:#367fa9; color:#fff;"></td>';
			
			}
	$availableData .='</tr>';
}

//============================Budget Value END




// -------------------Bottom Total START----------------------------------------------------	
$availableData .='<tr style=" background-color:#367fa9; color:#fff;"><td></td>';					
$availableData .='<td style="text-align: right;vertical-align: middle;  background-color:#367fa9; color:#fff;">Total RN</td>';
$start = $month = strtotime($start_date);
$end = strtotime($end_date);
$monthPeriod=date("n", strtotime($month));
while($month < $end){
    $DateValue1	=	date('Y-m-d', $month);
	//$monthPeriod=date("n", strtotime("+1 month",$monthPeriod));
    
	//$month = strtotime("+1 month", $month);
	
	//$monthPeriod=date("n", strtotime($month));
	
	
	$editTotalsql = executeSql("SELECT a.*, sum(qty) as totals  FROM `".TBL_BUDGET_MASTER."` as a  where  a.`id_shop` = '".addslashes($_SESSION['shop'])."' $BudgetType and  a.`id_user`='".$_REQUEST['userId']."' and a.`seasonId`='".$_REQUEST['seasonId']."' and a.`month`='".$DateValue1."'");

		$totalresultCat = $db->fetch_object2($editTotalsql);

		$SubTotal	+=	$totalresultCat->totals;

		$availableData .= '<td><input type="text" class="form-control  total" id="totalbottom_night'.date('n',$month).'" name="total" value="'.$totalresultCat->totals.'" style="width:60px; background-color:#367fa9; color:#fff;"></td>';					
$month = strtotime("+1 month", $month);
$monthPeriod=date("n", strtotime($month));
}

$availableData .= '<td><input type="text" class="form-control  total" id="total" name="total" value="'.$SubTotal.'" style="width:60px; background-color:#367fa9; color:#fff;"></td>';	
$availableData .='</tr>';
$availableData .='<tr style=" background-color:#367fa9; color:#fff;"><td></td>';					
$availableData .='<td style="text-align: right;vertical-align: middle; background-color:#367fa9; color:#fff;">Total Value</td>';
$start = $month = strtotime($start_date);
$end = strtotime($end_date);
	while($month < $end){
     $DateValue1	=	date('Y-m-d', $month);
     
 	$editTotalsql = executeSql("SELECT a.*, sum(month_value) as totals  FROM `".TBL_BUDGET_MASTER."` as a  where  a.`id_shop` = '".addslashes($_SESSION['shop'])."' $BudgetType and  a.`id_user`='".$_REQUEST['userId']."' and a.`seasonId`='".$_REQUEST['seasonId']."' and a.`month`='".$DateValue1."'");

		$totalresultCat = $db->fetch_object2($editTotalsql);

		$availableData .= '<td><input type="text" class="form-control  total" id="totalbottom_value'.date('n',$month).'" name="total" value="'.round($totalresultCat->totals,2).'" style="width:60px; background-color:#367fa9; color:#fff;"></td>';					
	$SubTotal1	+=	$totalresultCat->totals;
	$month = strtotime("+1 month", $month);
}

		

	$availableData .= '<td ><input type="text"  class="form-control  total" id="total" name="total" value="'.round($SubTotal1,2).'" style="width:60px; background-color:#367fa9; color:#fff;"></td>';	

			

	$availableData .='</tr>';					
// -------------------Bottom Total END----------------------------------------------------
}else{ // New INSERT FOR BUDGET and VALUE ===================================================================================
////////////////////////////show grid data////////////////////////////////////////////////////////
$availableData .= '<div class="box box-success  table-responsive no-padding">

				  <table class="table table-hover" style="margin-bottom:none !important;">

		<input type="hidden" id="type" name="type" value="'.$_REQUEST['type'].'" >

		<tr>

		<th> Hotel</th>
<th></th>
		<th>Apr - '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>		

		<th>May- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Jun- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Jul- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>				  

		<th>Aug- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Sep- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Oct- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Nov- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Dec- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Jan- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Feb- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Mar- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		</tr>';

 $resCat_rooms1 = selectSql(TBL_HOTELS," where status='1' and id_shop='".addslashes($_SESSION['shop'])."'",' ORDER BY `name` ');
	while($rowHotelResult = $db->fetch_object2($resCat_rooms1)){
		$availableData .= '<tr id="rateMaster|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'">';
	    $editstart_date 		=	$editrow->start_date;
		$editend_date 			=	$editrow->end_date;

$availableData .= '<input type="hidden" id="data_id" name="data_id[]" value="'.$rowHotelResult->id.'" >';	

//$availableData .= '<input type="hidden" id="data_id|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'" name="data_id[]" value="|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'" >';	

$availableData .= '<input type="hidden" id="bugetHotel" name="bugetHotel[]" value="'.$rowHotelResult->id.'" >';
$availableData .= '<td>'.$rowHotelResult->name.'- '.$rowHotelResult->city.'</td><td style="color: red;font-size: 12px;">Room Nights</td>'; 
$start_date	=	selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'");		
$end_date	=selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$_REQUEST['seasonId']."'");								 
$start = $month = strtotime($start_date);
$end = strtotime($end_date);
$start_date3=$start_date;
while($month < $end){
     $DateValue	=	date('Y-m-d', $month);
     
	 $OnKeyUpOne	   = 'updateBudgetRowRoomNight(this.value,'.$rowHotelResult->id.','.date('n',$month).')';
	 $incValue	="'qty'";
	 $updateRowWise	   = 'updateBudgetRowWise(this.value,'.$rowHotelResult->id.','.strtotime($start_date3).',0,'.$type.','.$_REQUEST['seasonId'].','.$incValue.','.$_REQUEST['userId'].')';
		
	 $availableData .= '<td>

				  <input type="text" class="form-control  buget_qty" id="buget_qty|'.$DateValue.'|'.$userId.'|'.$rowHotelResult->id.'" name="buget_qty|'.$rowHotelResult->id.'[]" value="0" onchange="'.$OnKeyUpOne.';'.$updateRowWise.'" data-parsley-required automcomplete="off" data-parsley-type="number" style="width:60px;">

		  
		  <input type="hidden" class="form-control" id="hiddenBox'.$rowHotelResult->id.date('n',$month).'" name="hiddenBox_'.$rowHotelResult->id.'_'.date('n',$month).'" value="0"  style="width:60px;">
		  
				  </td>';
$start_date3 = date ("Y-m-d", strtotime("+1 month", strtotime($start_date3)));				  
$month = strtotime("+1 month", $month);
}
$availableData .= '<td style=" background-color:#367fa9;">

		
		<input type="text" class="form-control  total" id="total_'.$rowHotelResult->id.'" name="total" value="0" style="width:60px;background-color:#367fa9; color:#fff;"></td>';	

$availableData .='</tr>';
$availableData .='<tr>';				
$availableData .='<td></td><td style="color: red;font-size: 12px; width:80px;">Budget Value (lacs)</td>';
$start = $month = strtotime($start_date);
$end = strtotime($end_date);
$start_date4=$start_date;
while($month < $end){
     $DateValue1	=	date('Y-m-d', $month);
     
	 $OnKeyUpOnevalue	   = 'updateBudgetRowValue(this.value,'.$rowHotelResult->id.','.date('n',$month).')';	 
	 $incValue			=		"'month_value'";
	 $updateRowWise	   = 'updateBudgetRowWise(this.value,'.$rowHotelResult->id.','.strtotime($start_date4).',0,'.$type.','.$_REQUEST['seasonId'].','.$incValue.','.$_REQUEST['userId'].')';	 
			
	 $availableData .= ' <td>
	  
	 <input type="text" class="form-control  tax" id="buget_value|'.$DateValue.'|'.$userId.'|'.$rowHotelResult->id.'" onchange="'.$OnKeyUpOnevalue.';'.$updateRowWise.'" name="buget_value|'.$rowHotelResult->id.'[]" value="0" data-parsley-required automcomplete="off" data-parsley-type="number" style="width:60px;">
	 		  <input type="hidden" class="form-control" id="hiddenBoxValue'.$rowHotelResult->id.date('n',$month).'" name="hiddenBoxValue_'.$rowHotelResult->id.'_'.date('n',$month).'" value="'.$resultCat2->month_value.'"  style="width:60px;">
			  
			  
	 </td>';
	 
	 
				 
				 
			
			 
	$availableData .= '<input type="hidden" id="MonthDate" name="MonthDate|'.$rowHotelResult->id.'[]" value="'.$DateValue1.'" >';	 
	$start_date4 = date ("Y-m-d", strtotime("+1 month", strtotime($start_date4)));
	$month = strtotime("+1 month", $month);
}
	$availableData .= '<td style=" background-color:#367fa9;">
			<input type="text" class="form-control  total" id="Totalbuget_value_'.$rowHotelResult->id.'" name="Totalbuget_value" value="0" style="width:60px; background-color:#367fa9; color:#fff;"></td>';
			
			
			
			$availableData .='</tr>';
	}
	


	
	// -------------------Bottom Total START----------------------------------------------------	
$availableData .='<tr style=" background-color:#367fa9; color:#fff;"><td></td>';					
$availableData .='<td style="text-align: right;vertical-align: middle;  background-color:#367fa9; color:#fff;">Total RN</td>';
$start = $month = strtotime($start_date);
$end = strtotime($end_date);
$monthPeriod=date("n", strtotime($month));
while($month < $end){
    $DateValue1	=	date('Y-m-d', $month);
	//$monthPeriod=date("n", strtotime("+1 month",$monthPeriod));
    
	//$month = strtotime("+1 month", $month);
	
	//$monthPeriod=date("n", strtotime($month));
	
	
	$editTotalsql = executeSql("SELECT a.*, sum(qty) as totals  FROM `".TBL_BUDGET_MASTER."` as a  where  a.`id_shop` = '".addslashes($_SESSION['shop'])."' $BudgetType and  a.`id_user`='".$_REQUEST['userId']."' and a.`seasonId`='".$_REQUEST['seasonId']."' and a.`month`='".$DateValue1."'");

		$totalresultCat = $db->fetch_object2($editTotalsql);

		$SubTotal	+=	$totalresultCat->totals;

		$availableData .= '<td><input type="text" class="form-control  total" id="totalbottom_night'.date('n',$month).'" name="total" value="0" style="width:60px; background-color:#367fa9; color:#fff;"></td>';					
$month = strtotime("+1 month", $month);
$monthPeriod=date("n", strtotime($month));
}

$availableData .= '<td><input type="text" class="form-control  total" id="total" name="total" value="'.$SubTotal.'" style="width:60px; background-color:#367fa9; color:#fff;"></td>';	
$availableData .='</tr>';
$availableData .='<tr style=" background-color:#367fa9; color:#fff;"><td></td>';					
$availableData .='<td style="text-align: right;vertical-align: middle; background-color:#367fa9; color:#fff;">Total Value</td>';
$start = $month = strtotime($start_date);
$end = strtotime($end_date);
	while($month < $end){
     $DateValue1	=	date('Y-m-d', $month);
     
 	$editTotalsql = executeSql("SELECT a.*, sum(month_value) as totals  FROM `".TBL_BUDGET_MASTER."` as a  where  a.`id_shop` = '".addslashes($_SESSION['shop'])."' $BudgetType and  a.`id_user`='".$_REQUEST['userId']."' and a.`seasonId`='".$_REQUEST['seasonId']."' and a.`month`='".$DateValue1."'");

		$totalresultCat = $db->fetch_object2($editTotalsql);

		$availableData .= '<td>
		<input type="text" class="form-control  total" id="totalbottom_value'.date('n',$month).'" name="total" value="0" style="width:60px; background-color:#367fa9; color:#fff;">
		</td>';					
	$SubTotal1	+=	$totalresultCat->totals;
	$month = strtotime("+1 month", $month);
}

		

	$availableData .= '<td ><input type="text"  class="form-control  total" id="total" name="total" value="'.round($SubTotal1,2).'" style="width:60px; background-color:#367fa9; color:#fff;"></td>';	

			

	$availableData .='</tr>';					
// -------------------Bottom Total END----------------------------------------------------



}				 

											 

$availableData .='</table>	';
//}
$availableData .= '  
            </div>';
echo $availableData;

?>
<script type="text/javascript">

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


</script>

