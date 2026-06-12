<?php include_once("../../config/auto_loader.php");
////////////////////////////////////////////////////////////////////////
$table_name = $_POST['table_name'];
$id=$_POST['id'];
$column_name = $_POST['column_name'];
$image_name = $_POST['image_name'];
$image_path = $UPLOAD_FILES.'/'.$_POST['image_path'].'/';
$image_display_path = $UPLOAD_FILES_PATH ."/".$_POST['image_path']."/";
//checkUserLevelPermission($_SESSION['userLevel'],$table_name,'delete');
if($_SESSION['userLevel'] == 1){
	//do nothing for super admin
	}else{
	$resActionId = selectColumn(TBL_USER_ACTIONS, 'id' , "WHERE name = 'update'");
	$sqlCheckkPermission = "SELECT * FROM ".TBL_USER_PERMISSIONS." 
							WHERE status = '1' 
							AND FIND_IN_SET('".$resActionId."',user_actions) 
							AND user_level_id = '".$_SESSION['userLevel']."' 
							AND module_id = '". selectColumn(TBL_MODULES, 'id' , "WHERE table_name= '".$table_name."'")."'";
	if($db->num_rows2(executeSql($sqlCheckkPermission))>0){
		//return true;
	}else{
		echo  'You don\'t have permission to take action "'.ucfirst('update').'" on module "'. selectColumn(TBL_MODULES, 'name' , "WHERE table_name = '".$table_name."'").'"' ;
		echo '<span class="mailbox-attachment-icon has-img">							 
				<img src="'.$image_display_path.$image_name.'" alt="Room Image">							  
			  </span>			
			  <div class="mailbox-attachment-info">
				<a href="javascript:void(0);" class="mailbox-attachment-name"><i class="fa fa-camera"></i> '.$image_name.'</a>
					<span class="mailbox-attachment-size">
					   '.round(filesize($image_path.$image_name)/ 1024 ,2).' KB'.'
					  <a href="'.$image_display_path.$image_name.'" download class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>
					</span>
			  </div>';
		exit;		
	}	
}
	  

$removeImagequery = "UPDATE `".$table_name."` SET  ".$column_name." = '', last_modified='".currenDateTime()."', last_modified_by='".$_SESSION['userId']."' where id='".$_POST['id']."'";
if(executeSql($removeImagequery)){				
echo '<span class="mailbox-attachment-icon has-img">							 
			<img src="images/no-hotel-image.jpg" alt="Room Image">							  
		  </span>			
		  <div class="mailbox-attachment-info">
			<a href="javascript:void(0);" class="mailbox-attachment-name"><i class="fa fa-camera"></i> no-hotel-image.jpg</a>
				<span class="mailbox-attachment-size">
				   '.round(filesize('../images/no-hotel-image.jpg')/ 1024 ,2).' KB'.'
				  <a href="images/no-hotel-image.jpg" download class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>
				</span>
		  </div>';
		  
			if(file_exists($image_path.$image_name)){
				@unlink($image_path.$image_name);
				@unlink($image_path.'small-'.$image_name);
				@unlink($image_path.'medium-'.$image_name);
			}
}else {
echo '<span class="mailbox-attachment-icon has-img">							 
			<img src="images/no-hotel-image.jpg" alt="Room Image">							  
		  </span>			
		  <div class="mailbox-attachment-info">
			<a href="javascript:void(0);" class="mailbox-attachment-name"><i class="fa fa-camera"></i> no-hotel-image.jpg</a>
				<span class="mailbox-attachment-size">
				   '.round(filesize('../images/no-hotel-image.jpg')/ 1024 ,2).' KB'.'
				  <a href="images/no-hotel-image.jpg" download class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>
				</span>
		  </div>';

}
?>

	