<?php
	include_once("../../config/auto_loader.php");

	//echo "<pre>";print_r($_POST);exit;

	//echo $_POST['id_report_config']; exit;
	//$table_field = $_POST['table_field'];
	$sessionUserId = $_SESSION['userId']; 
	$display_order = $_POST['display_order'];

	//$arrFields=array("display_order"=>addslashes($display_order),"date_created"=>currenDateTime(),"last_modified"=>currenDateTime(),"id_mst_user_modified_by"=>$sessionUserId,"id_mst_user_created_by"=>$sessionUserId);
	//echo "UPDATE ".TBL_REPORT." display_order='".addslashes($display_order)."',date_created='".currenDateTime()."',last_modified='".currenDateTime()."',id_mst_user_modified_by='".$sessionUserId."',id_mst_user_created_by='".$sessionUserId."' WHERE ".$condition;
	$condition = "table_name='".$_POST['table_name']."' AND field_name ='".$_POST['table_field']."' AND id =".$_POST['fieldId'];
	
	$result = mysqli_query($connNew, "UPDATE ".TBL_REPORT." SET display_order='".addslashes($display_order)."',date_created='".currenDateTime()."',last_modified='".currenDateTime()."',id_mst_user_modified_by='".$sessionUserId."',id_mst_user_created_by='".$sessionUserId."' WHERE ".$condition);                      
	//$result = updateData(TBL_REPORT,$arrFields,$condition);
	if($result == true){
		echo "Updated Successfully";
	}else{
		echo "Records Not Updated";
	}
?>