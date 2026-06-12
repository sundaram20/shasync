<?php  include_once("../../config/auto_loader.php");


/*echo "<pre>";
print_r($_REQUEST);
echo "</pre>";
exit;*/
$hotel_id		     =	$_REQUEST['hotel_id'];
$hotel_percentage	 =	$_REQUEST['hotel_percentage'];
$executive_percentage  =	$_REQUEST['executive_percentage'];


$editId  =	$_REQUEST['editId'];

if($editId==''){	
$sqlNotify = "INSERT INTO  fs_incentive_participate_hotel SET
					`hotel_id`='".$hotel_id."',
					`hotel_percentage`='".$hotel_percentage."',
					`executive_percentage`='".$executive_percentage."',
					`id_shop`='".addslashes($_SESSION['shop'])."',
					
					";

		
		$sqlNotify .="
					`created_by`='".$_SESSION['userId']."',
						`modified_by`='".$_SESSION['userId']."',						
						`date_created`  = '".date('Y-m-d H:i:s')."',
						`date_modified`='".date('Y-m-d H:i:s')."' ";
					
	mysqli_query($connNew,$sqlNotify);	
}else{
	
	$sqlNotify = "UPDATE fs_incentive_participate_hotel SET			
					`hotel_percentage`='".$hotel_percentage."',
					`executive_percentage`='".$executive_percentage."',
					`modified_by`='".$_SESSION['userId']."',	
					`date_modified`='".date('Y-m-d H:i:s')."'
					";

		
		$sqlNotify .="
					WHERE `id`='".$editId."'";
	
	mysqli_query($connNew,$sqlNotify);
	}
		
	/*$sqlNotify = "UPDATE ".TBL_INCENTIVE." SET					
					`current_status`='".$status_incentive_id."',
					`approved_amount`='".$approved_amount."',
					`id_currently_with`='".$user_id."'
					
					
					";

		
		$sqlNotify .="
					WHERE `id`='".$id_incentive_previous."'";*/
					
	

	//$enquiry_id=selectColumn(TBL_INCENTIVE,'id_enquiry',"WHERE  id = '".$id_incentive_previous."' ");
	//$eId=encryptor('encrypt',$enquiry_id);

echo '<p class="help-block">Incentive Participate Hotel has been  Updated Sucessfully.</p><script>window.setTimeout(function() {window.location.href = "incentiveParticipateHotel.php";}, 2000);</script>';



?>
