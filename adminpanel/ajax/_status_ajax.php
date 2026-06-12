<?
include_once("../../config/auto_loader.php");

$table_name = $_GET['tbl'];
$id=$_GET['id'];
$status =$_GET['status'];
//checkUserLevelPermission
if($status==0){
	if($_SESSION['userLevel'] == 1){
		//do nothing for super admin
		}else{
		$resActionId = selectColumn(TBL_USER_ACTIONS, 'id' , "WHERE name = 'activate'");
		$sqlCheckkPermission = "SELECT * FROM ".TBL_USER_PERMISSIONS." 
								WHERE status = '1' 
								AND FIND_IN_SET('".$resActionId."',user_actions) 
								AND user_level_id = '".$_SESSION['userLevel']."' 
								AND module_id = '". selectColumn(TBL_MODULES, 'id' , "WHERE table_name= '".$table_name."'")."'";
		if($db->num_rows2(executeSql($sqlCheckkPermission))>0){
			//return true;
		}else{
			echo  '3' ;		
			exit;		
		}	
	}
}
if($status==1){
	if($_SESSION['userLevel'] == 1){
		//do nothing for super admin
		}else{
		$resActionId = selectColumn(TBL_USER_ACTIONS, 'id' , "WHERE name = 'deactivate'");
		$sqlCheckkPermission = "SELECT * FROM ".TBL_USER_PERMISSIONS." 
								WHERE status = '1' 
								AND FIND_IN_SET('".$resActionId."',user_actions) 
								AND user_level_id = '".$_SESSION['userLevel']."' 
								AND module_id = '". selectColumn(TBL_MODULES, 'id' , "WHERE table_name= '".$table_name."'")."'";
		if($db->num_rows2(executeSql($sqlCheckkPermission))>0){
			//return true;
		}else{
			echo  '4' ;		
			exit;		
		}	
	}
}	  

$statusQuery = "UPDATE `".$table_name."` SET status = case when status = 1 then 0 else 1 end, last_modified='".currenDateTime()."', last_modified_by='".$_SESSION['userId']."' where id='".$id."'";
if(executeSql($statusQuery)){				
echo selectColumn($table_name,'status'," WHERE `id` = '".addslashes($id)."'");			
}else {
echo 'Error';
}
?>