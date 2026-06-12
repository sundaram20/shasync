<?php include_once("../../config/auto_loader.php");

$conn = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);


$checkUserType = selectColumn(TBL_USERS,'user_type','WHERE id="'.$_REQUEST['selectuserid'].'" '); 

$checkUserHotelAccess = selectColumn(TBL_USERS,'hotel_access','WHERE id="'.$_REQUEST['selectuserid'].'" '); 

	if($checkUserType=='2'){
	$userTypeTable	= TBL_UNIT_AGENT_ACHIEVED;	
	$SqlConn		  = "and a.id_hotel = '".$_REQUEST['id_hotel']."' and FIND_IN_SET('".$_REQUEST['selectuserid']."',C.ids_unit_user)";
	$SqlunitConn		  = "and ach.id_hotel = '".$_REQUEST['id_hotel']."' ";
	//echo 'UNIT USER';
	}else{
			$userTypeTable	= TBL_AGENT_ACHIEVED;			
			$SqlConn		  = "and C.`user_id`='".$_REQUEST['selectuserid']."'";
				$SqlunitConn		  = "and ach.id_user = '".$_REQUEST['selectuserid']."' ";
			$ConnTotalSql= "and a.id_user = '".$_REQUEST['selectuserid']."' ";
			//echo 'RSO USER ';
		}
		
  $resCat_rooms2 ="select  
budget.name,
budget.id_company,
budget.ids_unit_user,
sum(budget.Apr) as Apr,sum(budget.May) as May,sum(budget.Jun) as Jun,
sum(budget.Jul) as Jul,sum(budget.Aug) as Aug,sum(budget.Sep) as 
'Sep',
sum(budget.Oct) as Oct,sum(budget.Nov) as Nov,sum(budget.Dec) as 'Dec',
sum(budget.Jan) as Jan,sum(budget.Feb) as Feb,sum(budget.Mar) as Mar,
sum(budget.Total) as Total
from 
(
select distinct
com.name, com.id_company, ar.ids_unit_user,
case when month(ach.month)=4 then ach.qty end as 'Apr',case when month(ach.month)=5 then ach.qty end as 'May',
case when month(ach.month)=6 then ach.qty end as 'Jun',case when month(ach.month)=7 then ach.qty end as 'Jul',
case when month(ach.month)=8 then ach.qty end as 'Aug',case when month(ach.month)=9 then ach.qty end as 'Sep',
case when month(ach.month)=10 then ach.qty end as 'Oct',case when month(ach.month)=11 then ach.qty end as 'Nov',
case when month(ach.month)=12 then ach.qty end as 'Dec',case when month(ach.month)=1 then ach.qty end as 'Jan',
case when month(ach.month)=2 then ach.qty end as 'Feb',case when month(ach.month)=3 then ach.qty end as 'Mar',
ach.qty as Total
from fs_areas_assign ar
inner join
fs_company com
on
com.area = ar.id
inner join
`".$userTypeTable."` ach
on
ach.id_company=com.id_company
where com.id_shop='".addslashes($_SESSION['shop'])."' $SqlunitConn and ach.id_company='".$_REQUEST['id_company']."' and ach.seasonId='".$_REQUEST['selectseasonId']."'  and com.name<>'' and FIND_IN_SET('".$_REQUEST['selectuserid']."',ar.ids_unit_user)
) as budget
group by budget.name,
budget.id_company,
budget.ids_unit_user
having sum(budget.Total)>0
order by budget.name";
		$resCat_rooms1=mysqli_query($conn,$resCat_rooms2);	
		$rowHotelResult = mysqli_fetch_object($resCat_rooms1);
		if($rowHotelResult->id_company==''){
			$id_company	= $_REQUEST['id_company'];
			$Button='Add';
			}else{
				$id_company	= $rowHotelResult->id_company;
							$Button='Edit';
				}
	 $Year	=	 date('Y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['selectseasonId']."'")));
$AddCompanylist .= '<div id="Listbudgetvalue"><table class="table table-bordered table-striped"><tr>';
		 
			
		  if($checkUserType=='2'){
				 //$OnKeyUpOne	   ='updateRowValueUnit(this.value,'.$_REQUEST['selectuserid'].','.$id_company.','.$_REQUEST['selectseasonId'].','.strtotime(date($Year.'-04-01')).','.$_REQUEST['id_hotel'].')';
			 }else{
				 //$OnKeyUpOne	   = 'updateRowValue(this.value,'.$_REQUEST['selectuserid'].','.$id_company.','.$_REQUEST['selectseasonId'].','.strtotime(date($Year.'-04-01')).')';
				 }
	 
$AddCompanylist .= '<td>	
<input type="hidden" name="month[]" value="'.strtotime(date($Year.'-04-01')).'">
		  <input type="text" class="form-control  budget_quantity" id="budget_quantity|'.$DateValue.'|'.$_REQUEST['selectuserid'].'|'.$id_company.'" name="budget_quantity[]" value="'.($rowHotelResult->Apr?$rowHotelResult->Apr:0).'"  automcomplete="off" data-parsley-type="number" style="width:55px;" onchange="'.$OnKeyUpOne.'" >
		  </td>';
		  
		  	if($checkUserType=='2'){
				 //$OnKeyUpOne	   ='updateRowValueUnit(this.value,'.$_REQUEST['selectuserid'].','.$id_company.','.$_REQUEST['selectseasonId'].','.strtotime(date($Year.'-05-01')).','.$_REQUEST['id_hotel'].')';
			 }else{
				 //$OnKeyUpOne	   = 'updateRowValue(this.value,'.$_REQUEST['selectuserid'].','.$id_company.','.$_REQUEST['selectseasonId'].','.strtotime(date($Year.'-05-01')).')';
				 }

$AddCompanylist .= '<td>
<input type="hidden" name="month[]" value="'.strtotime(date($Year.'-05-01')).'">
		  <input type="text" class="form-control  budget_quantity" id="budget_quantity|'.$DateValue.'|'.$_REQUEST['selectuserid'].'|'.$id_company.'" name="budget_quantity[]" value="'.($rowHotelResult->May?$rowHotelResult->May:0).'"  automcomplete="off" data-parsley-type="number" style="width:55px;" onchange="'.$OnKeyUpOne.'" onKeyUp="">
		  </td>';
		  
			 if($checkUserType=='2'){
			 //$OnKeyUpOne	   ='updateRowValueUnit(this.value,'.$_REQUEST['selectuserid'].','.$id_company.','.$_REQUEST['selectseasonId'].','.strtotime(date($Year.'-06-01')).','.$_REQUEST['id_hotel'].')';
		 }else{
			 //$OnKeyUpOne	   = 'updateRowValue(this.value,'.$_REQUEST['selectuserid'].','.$id_company.','.$_REQUEST['selectseasonId'].','.strtotime(date($Year.'-06-01')).')';
			 }

$AddCompanylist .= '<td>
<input type="hidden" name="month[]" value="'.strtotime(date($Year.'-06-01')).'">
		  <input type="text" class="form-control  budget_quantity" id="budget_quantity|'.$DateValue.'|'.$_REQUEST['selectuserid'].'|'.$id_company.'" name="budget_quantity[]" value="'.($rowHotelResult->Jun?$rowHotelResult->Jun:0).'"  automcomplete="off" data-parsley-type="number" style="width:55px;" onchange="'.$OnKeyUpOne.'" onKeyUp="">
		  </td>';
		  
		 if($checkUserType=='2'){
		 //$OnKeyUpOne	   ='updateRowValueUnit(this.value,'.$_REQUEST['selectuserid'].','.$id_company.','.$_REQUEST['selectseasonId'].','.strtotime(date($Year.'-07-01')).','.$_REQUEST['id_hotel'].')';
	 }else{
		 //$OnKeyUpOne	   = 'updateRowValue(this.value,'.$_REQUEST['selectuserid'].','.$id_company.','.$_REQUEST['selectseasonId'].','.strtotime(date($Year.'-07-01')).')';
		 }

$AddCompanylist .= '<td>
<input type="hidden" name="month[]" value="'.strtotime(date($Year.'-07-01')).'">
		  <input type="text" class="form-control  budget_quantity" id="budget_quantity|'.$DateValue.'|'.$_REQUEST['selectuserid'].'|'.$id_company.'" name="budget_quantity[]" value="'.($rowHotelResult->Jul?$rowHotelResult->Jul:0).'"  automcomplete="off" data-parsley-type="number" style="width:55px;" onchange="'.$OnKeyUpOne.'" onKeyUp="">
		  </td>';


		 if($checkUserType=='2'){
			 //$OnKeyUpOne	   ='updateRowValueUnit(this.value,'.$_REQUEST['selectuserid'].','.$id_company.','.$_REQUEST['selectseasonId'].','.strtotime(date($Year.'-08-01')).','.$_REQUEST['id_hotel'].')';
		 }else{
			 //$OnKeyUpOne	   = 'updateRowValue(this.value,'.$_REQUEST['selectuserid'].','.$id_company.','.$_REQUEST['selectseasonId'].','.strtotime(date($Year.'-08-01')).')';
			 }
$AddCompanylist .= '<td>
<input type="hidden" name="month[]" value="'.strtotime(date($Year.'-08-01')).'">
		  <input type="text" class="form-control  budget_quantity" id="budget_quantity|'.$DateValue.'|'.$_REQUEST['selectuserid'].'|'.$id_company.'" name="budget_quantity[]" value="'.($rowHotelResult->Aug?$rowHotelResult->Aug:0).'"  automcomplete="off" data-parsley-type="number" style="width:55px;" onchange="'.$OnKeyUpOne.'" onKeyUp="">
		  </td>';

		if($checkUserType=='2'){
		//$OnKeyUpOne	   ='updateRowValueUnit(this.value,'.$_REQUEST['selectuserid'].','.$id_company.','.$_REQUEST['selectseasonId'].','.strtotime(date($Year.'-09-01')).','.$_REQUEST['id_hotel'].')';
		}else{
		//$OnKeyUpOne	   = 'updateRowValue(this.value,'.$_REQUEST['selectuserid'].','.$id_company.','.$_REQUEST['selectseasonId'].','.strtotime(date($Year.'-09-01')).')';
		}
		
		
$AddCompanylist .= '<td>
<input type="hidden" name="month[]" value="'.strtotime(date($Year.'-09-01')).'">
		  <input type="text" class="form-control  budget_quantity" id="budget_quantity|'.$DateValue.'|'.$_REQUEST['selectuserid'].'|'.$id_company.'" name="budget_quantity[]" value="'.($rowHotelResult->Sep?$rowHotelResult->Sep:0).'"  automcomplete="off" data-parsley-type="number" style="width:55px;" onchange="'.$OnKeyUpOne.'" onKeyUp="">
		  </td>';
		if($checkUserType=='2'){
		//$OnKeyUpOne	   ='updateRowValueUnit(this.value,'.$_REQUEST['selectuserid'].','.$id_company.','.$_REQUEST['selectseasonId'].','.strtotime(date($Year.'-10-01')).','.$_REQUEST['id_hotel'].')';
		}else{
		//$OnKeyUpOne	   = 'updateRowValue(this.value,'.$_REQUEST['selectuserid'].','.$id_company.','.$_REQUEST['selectseasonId'].','.strtotime(date($Year.'-10-01')).')';
		}
$AddCompanylist .= '<td>
<input type="hidden" name="month[]" value="'.strtotime(date($Year.'-10-01')).'">
		  <input type="text" class="form-control  budget_quantity" id="budget_quantity|'.$DateValue.'|'.$_REQUEST['selectuserid'].'|'.$id_company.'" name="budget_quantity[]" value="'.($rowHotelResult->Oct?$rowHotelResult->Oct:0).'"  automcomplete="off" data-parsley-type="number" style="width:55px;" onchange="'.$OnKeyUpOne.'" onKeyUp="">
		  </td>';

		if($checkUserType=='2'){
		//$OnKeyUpOne	   ='updateRowValueUnit(this.value,'.$_REQUEST['selectuserid'].','.$id_company.','.$_REQUEST['selectseasonId'].','.strtotime(date($Year.'-11-01')).','.$_REQUEST['id_hotel'].')';
		}else{
		//$OnKeyUpOne	   = 'updateRowValue(this.value,'.$_REQUEST['selectuserid'].','.$id_company.','.$_REQUEST['selectseasonId'].','.strtotime(date($Year.'-11-01')).')';
		}

$AddCompanylist .= '<td>
<input type="hidden" name="month[]" value="'.strtotime(date($Year.'-11-01')).'">
		  <input type="text" class="form-control  budget_quantity" id="budget_quantity|'.$DateValue.'|'.$_REQUEST['selectuserid'].'|'.$id_company.'" name="budget_quantity[]" value="'.($rowHotelResult->Nov?$rowHotelResult->Nov:0).'"  automcomplete="off" data-parsley-type="number" style="width:55px;" onchange="'.$OnKeyUpOne.'" onKeyUp="">
		  </td>';
		if($checkUserType=='2'){
		//$OnKeyUpOne	   ='updateRowValueUnit(this.value,'.$_REQUEST['selectuserid'].','.$id_company.','.$_REQUEST['selectseasonId'].','.strtotime(date($Year.'-12-01')).','.$_REQUEST['id_hotel'].')';
		}else{
		//$OnKeyUpOne	   = 'updateRowValue(this.value,'.$_REQUEST['selectuserid'].','.$id_company.','.$_REQUEST['selectseasonId'].','.strtotime(date($Year.'-12-01')).')';
		}
		
$AddCompanylist .= '<td>
<input type="hidden" name="month[]" value="'.strtotime(date($Year.'-12-01')).'">
		  <input type="text" class="form-control  budget_quantity" id="budget_quantity|'.$DateValue.'|'.$_REQUEST['selectuserid'].'|'.$id_company.'" name="budget_quantity[]" value="'.($rowHotelResult->Dec?$rowHotelResult->Dec:0).'"  automcomplete="off" data-parsley-type="number" style="width:55px;" onchange="'.$OnKeyUpOne.'" onKeyUp="">
		  </td>';
		$Year	=	 date('Y',strtotime(selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$_REQUEST['selectseasonId']."'")));
		if($checkUserType=='2'){
		//$OnKeyUpOne	   ='updateRowValueUnit(this.value,'.$_REQUEST['selectuserid'].','.$id_company.','.$_REQUEST['selectseasonId'].','.strtotime(date($Year.'-01-01')).','.$_REQUEST['id_hotel'].')';
		}else{
		//$OnKeyUpOne	   = 'updateRowValue(this.value,'.$_REQUEST['selectuserid'].','.$id_company.','.$_REQUEST['selectseasonId'].','.strtotime(date($Year.'-01-01')).')';
		}
$AddCompanylist .= '<td>
<input type="hidden" name="month[]" value="'.strtotime(date($Year.'-01-01')).'">
		  <input type="text" class="form-control  budget_quantity" id="budget_quantity|'.$DateValue.'|'.$_REQUEST['selectuserid'].'|'.$id_company.'" name="budget_quantity[]" value="'.($rowHotelResult->Jan?$rowHotelResult->Jan:0).'"  automcomplete="off" data-parsley-type="number" style="width:55px;" onchange="'.$OnKeyUpOne.'" onKeyUp="">
		  </td>';
		if($checkUserType=='2'){
		//$OnKeyUpOne	   ='updateRowValueUnit(this.value,'.$_REQUEST['selectuserid'].','.$id_company.','.$_REQUEST['selectseasonId'].','.strtotime(date($Year.'-02-01')).','.$_REQUEST['id_hotel'].')';
		}else{
		//$OnKeyUpOne	   = 'updateRowValue(this.value,'.$_REQUEST['selectuserid'].','.$id_company.','.$_REQUEST['selectseasonId'].','.strtotime(date($Year.'-02-01')).')';
		}
$AddCompanylist .= '<td>
<input type="hidden" name="month[]" value="'.strtotime(date($Year.'-02-01')).'">
		  <input type="text" class="form-control  budget_quantity" id="budget_quantity|'.$DateValue.'|'.$_REQUEST['selectuserid'].'|'.$id_company.'" name="budget_quantity[]" value="'.($rowHotelResult->Feb?$rowHotelResult->Feb:0).'"  automcomplete="off" data-parsley-type="number" style="width:55px;" onchange="'.$OnKeyUpOne.'" onKeyUp="">
		  </td>';
		if($checkUserType=='2'){
		//$OnKeyUpOne	   ='updateRowValueUnit(this.value,'.$_REQUEST['selectuserid'].','.$id_company.','.$_REQUEST['selectseasonId'].','.strtotime(date($Year.'-03-01')).','.$_REQUEST['id_hotel'].')';
		}else{
		//$OnKeyUpOne	   = 'updateRowValue(this.value,'.$_REQUEST['selectuserid'].','.$id_company.','.$_REQUEST['selectseasonId'].','.strtotime(date($Year.'-03-01')).')';
		}
$AddCompanylist .= '<td>
<input type="hidden" name="month[]" value="'.strtotime(date($Year.'-03-01')).'">
		  <input type="text" class="form-control  budget_quantity" id="budget_quantity|'.$DateValue.'|'.$_REQUEST['selectuserid'].'|'.$id_company.'" name="budget_quantity[]" value="'.($rowHotelResult->Mar?$rowHotelResult->Mar:0).'"  automcomplete="off" data-parsley-type="number" style="width:55px;" onchange="'.$OnKeyUpOne.'" onKeyUp="">
		  </td>';
		
$AddCompanylist .= '<td>
		  <input type="text" class="form-control  budget_quantity"  id="budget_total|'.$DateValue.'|'.$_REQUEST['selectuserid'].'|'.$id_company.'" name="budget_total" value="'.($rowHotelResult->Total?$rowHotelResult->Total:0).'"  automcomplete="off" data-parsley-type="number" style="width:55px;" onchange="'.$OnKeyUpOne.'" onKeyUp="">
		  </td>';
	

	
	
echo $AddCompanylist .='</tr></table>
<input name="Save" type="hidden" value="'.$Button.'" id="Save">
 <input type="button" value="'.$Button.'" name="Save" class="btn btn-primary" onclick="submitBudgetAchievedForm_1();"  >
</div>';
?>

