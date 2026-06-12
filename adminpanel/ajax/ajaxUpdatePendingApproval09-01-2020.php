<?php 
include_once("../../config/auto_loader.php");

$field='';
$table='';
$id='';

if($_REQUEST['doc_type']=='other'){
	$field='id';
	$table=TBL_OTHER;
	$source = "Other";
	$id=encryptor('decrypt',$_REQUEST['id_user']);
}
else{
	$field='id_user';
	$source = "Sales Report";
	$table=TBL_DAILYVISIT;
	$id=$_REQUEST['id_user'];
}

$sql = "UPDATE ".$table." SET 
		supervisor_remarks='".$_REQUEST['remarks']."',
		conveyance_approved =  '".$_REQUEST['status']."'
		WHERE ".$field."='".$id."' AND dated='".date('Y-m-d',strtotime($_REQUEST['dated']))."' AND id_shop='".$_SESSION['shop']."'
		";
		
$res = mysqli_query($connNew,$sql);

if($res){

	if($_REQUEST['remarks'] !=""){

		if($_REQUEST['doc_type']=='other'){
			$id_user_assigned_to = $_REQUEST['id_other'];
		}
		else{
			$id_user_assigned_to = $_REQUEST['id_user'];

			if($_REQUEST['status']==2)
				$dsrTxt='DSR NOT APPROVED : ';
		}

		

		$sqlNotify = "INSERT INTO ".TBL_NOTIFICATION." SET
					`id_shop`='".$_SESSION['shop']."',
					`source`='".$source."',
					`id_user_assigned_to`='".$id_user_assigned_to."',
					`id_user_assigned_by`='".$_SESSION['userId']."',
					`dated` ='".date('Y-m-d',strtotime($_REQUEST['dated']))."',
					`message`='".$dsrTxt.$_REQUEST['remarks']."',";

		
		$sqlNotify .="`last_modified`='".date('Y-m-d H:i:s')."',
					`date_created`='".date('Y-m-d H:i:s')."',
					`id_mst_user_created_by`='".$_SESSION['userId']."',
					`id_mst_user_modified_by`='".$_SESSION['userId']."' ";

		$resNotify = mysqli_query($connNew,$sqlNotify);	
		if($resNotify){
			echo 1;
		}	
		else
			mysqli_error($connNew);
	}	
	echo 1;
	
}
else{
	echo 0;
}

exit;			
?>