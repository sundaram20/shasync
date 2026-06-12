<?php  include_once("../../config/auto_loader.php");

$Json=array();
/*echo "<pre>";
print_r($_REQUEST);
echo "</pre>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";
exit;*/
$type		     		=	$_REQUEST['weeklyplanner'];
$allocation_date		=	date('Y-m-d',strtotime($_REQUEST['start_date']));
$userId  				=	$_SESSION['userId'];

$id_account		   		=	$_REQUEST['user_account'];
$id_company				=	$_REQUEST['search_name'];
$id_contact  			=	$_REQUEST['id_contacts'];
	
	
$contact_name		    =	$_REQUEST['new_contact_name'];
$contact_mobile			=	$_REQUEST['new_contact_mobile'];
$description  				=	$_REQUEST['executive_remarks'];
$other_activity_type  				=	$_REQUEST['other_activity_type'];	
if($type=='1'){
	if($id_account=='1'){
		$color	='#00a65a';

	}else{
	$color	='#e26666';
		}
}else{
	$color	='#6bb4ef';
}


if($_REQUEST['editId']==''){
		$sqlPlanner = "INSERT INTO  fs_weeklyplanner SET					
					`allocation_date`='".$allocation_date."',
					`user_id`='".$userId."',
					`type`='".$type."',
					`color`='".$color."',
					`description`='".$description."',";
					
		$sqlPlanner .= "`id_account`='".$id_account."',";
					
		$sqlPlanner .= "`id_company`='".$id_company."',
					`id_contact`='".$id_contact."',";
					
					
		$sqlPlanner .= "`contact_name`='".$contact_name."',					
					`contact_mobile`='".$contact_mobile."',
					
					`id_other_activity`='".$other_activity_type."'
					";

		$sqlPlanner .= "	,`date_created` = '".currenDateTime()."'

							,`last_modified` = '".currenDateTime()."'

							,`last_modified_by` = '".$_SESSION['userId']."'

							,`status` = '1'";
		
					
	mysqli_query($connNew,$sqlPlanner);
	$msg	=	$type=='1'?'Visit':'Activity ';
$Json['Msg']	=$msg.' Successfully Created ';
$Json['Status']='1';
	$Json['keystatus']='1';
}else{

$sqlPlannerUpdate = "UPDATE  fs_weeklyplanner SET					
					`allocation_date`='".$allocation_date."',
					`color`='".$color."',
					`type`='".$type."',
					`description`='".$description."',";
					
		$sqlPlannerUpdate .= "`id_account`='".$id_account."',";
					
		$sqlPlannerUpdate .= "`id_company`='".$id_company."',
					`id_contact`='".$id_contact."',";
					
					
		$sqlPlannerUpdate .= "`contact_name`='".$contact_name."',					
					`contact_mobile`='".$contact_mobile."',
					
					`id_other_activity`='".$other_activity_type."'
					";

		$sqlPlannerUpdate .= "	

							,`last_modified` = '".currenDateTime()."'

							,`last_modified_by` = '".$_SESSION['userId']."'

							WHERE id='".$_REQUEST['editId']."'";
		
					 $sqlPlannerUpdate;
	mysqli_query($connNew,$sqlPlannerUpdate);
$msg	=	$type=='1'?'Visit':'Activity ';
$Json['Msg']	=$msg.' Successfully Updated ';
$Json['Status']='1';
	$Json['keystatus']='2';


}


	//$enquiry_id=selectColumn(TBL_INCENTIVE,'id_enquiry',"WHERE  id = '".$id_incentive."' ");
	//$eId=encryptor('encrypt',$enquiry_id);

echo json_encode($Json);



?>
