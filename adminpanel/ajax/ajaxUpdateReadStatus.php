<?php 
	include_once("../../config/auto_loader.php");

	$sqlNotify = "UPDATE ".TBL_NOTIFICATION." SET
				`read_status`='1',";
	
	$sqlNotify .="`last_modified`='".date('Y-m-d H:i:s')."',
				`id_mst_user_modified_by`='".$_SESSION['userId']."'
				WHERE id='".str_replace('readStatus','',$_REQUEST['target'])."' AND id_shop='".$_SESSION['shop']."' ";
				

	$res = mysqli_query($connNew,$sqlNotify);
	if($res){
		echo 1;
	}
	else{
		echo 0;
	}			
?>	