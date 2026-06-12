<?
include_once("../../config/auto_loader.php");
$image_path = $UPLOAD_FILES.'/'.$_GET['path'].'/';
$image_display_path = $UPLOAD_FILES_PATH ."/".$_GET['path']."/";
$table_name = $_GET['tbl'];
$id=$_GET['id'];

//checkUserLevelPermission
if($_SESSION['userLevel'] == 1){
	//do nothing for super admin
	}else{
	$resActionId = selectColumn(TBL_USER_ACTIONS, 'id' , "WHERE name = 'delete'");
	$sqlCheckkPermission = "SELECT * FROM ".TBL_USER_PERMISSIONS." 
							WHERE status = '1' 
							AND FIND_IN_SET('".$resActionId."',user_actions) 
							AND user_level_id = '".$_SESSION['userLevel']."' 
							AND module_id = '". selectColumn(TBL_MODULES, 'id' , "WHERE table_name= '".$table_name."'")."'";
	if($db->num_rows2(executeSql($sqlCheckkPermission))>0){
		//return true;
	}else{
		echo  '2' ;		
		exit;		
	}	
}

$delSql = "DELETE FROM `".$table_name."` WHERE `id` = '".addslashes($id)."'";
$sqlDelUserLevel = selectRow($table_name," WHERE `id` = '".addslashes($id)."'");
	if(executeSql($delSql)){		
		if(file_exists($image_path.$sqlDelUserLevel['image'])){
			@unlink($image_path.$sqlDelUserLevel['image']);
			@unlink($image_path.'small-'.$sqlDelUserLevel['image']);
			@unlink($image_path.'main-'.$sqlDelUserLevel['image']);
			@unlink($image_path.'medium-'.$sqlDelUserLevel['image']);
		}
		echo '1';
	}else{
		echo 'Error';
	}
?>