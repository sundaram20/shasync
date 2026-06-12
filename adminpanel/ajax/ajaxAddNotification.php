<?php
include_once("../../config/auto_loader.php");
/*echo "<pre>";
print_r($_REQUEST);
echo "</pre>";
exit;*/

if($_REQUEST['action']=='Save'){
	$userArray = array();
	$userArray = explode(',',$_REQUEST['id_assigned']);
	for($i=0;$i<count($userArray);$i++){
		$sql = "INSERT INTO ".TBL_NOTIFICATION." SET
			`id_shop`='".$_SESSION['shop']."',
			`source`='Notify',
			`id_user_assigned_to`='".$userArray[$i]."',
			`id_user_assigned_by`='".$_SESSION['userId']."',
			`dated` ='".date('Y-m-d',strtotime($_REQUEST['date']))."',
			`message`='".$_REQUEST['desc']."', ";

		$sql.="	
			`last_modified`='".date('Y-m-d H:i:s')."',
			`date_created`='".date('Y-m-d H:i:s')."',
			`id_mst_user_created_by`='".$_SESSION['userId']."',
			`id_mst_user_modified_by`='".$_SESSION['userId']."' ";
	
		$exe = mysqli_query($connNew,$sql);	
	}
	
	if($exe){
		$insertId = mysqli_insert_id($connNew);
		$sqlRec = "SELECT * FROM ".TBL_NOTIFICATION." WHERE id_shop='".$_SESSION['shop']."' AND id='".$insertId."' ";
		$exeRec=mysqli_query($connNew,$sqlRec);
		
		if($exeRec){
			
			$data=mysqli_fetch_object($exeRec);
			echo "<tr>
					<td>".$_REQUEST['count']."</td>
					<td>".$data->message."</td>
				  </tr>";
			exit;	  
		}
	}	

}
else if($_REQUEST['action']=='Edit'){
	$sql = "UPDATE ".TBL_NOTIFICATION." SET
				`id_user_assigned_to`='".$_REQUEST['ids_user']."',
				`source`='Notify',
				`message`='".$_REQUEST['desc']."', ";

	$sql.="		`last_modified`='".date('Y-m-d H:i:s')."',
				`id_mst_user_modified_by`='".$_SESSION['userId']."'
				WHERE id_shop='".$_SESSION['shop']."' AND id='".$_REQUEST['eId']."' ";
			
	
	$exe = mysqli_query($connNew,$sql);	
	
	if($exe){
		$insertId = $_REQUEST['eId'];
		$sqlRec = "SELECT * FROM ".TBL_NOTIFICATION." WHERE id_shop='".$_SESSION['shop']."' AND id='".$insertId."' ";
		$exeRec=mysqli_query($connNew,$sqlRec);
		
		if($exeRec){
			
			$data=mysqli_fetch_object($exeRec);
			echo "<tr>
					<td>".$_REQUEST['count']."</td>
					<td>".$data->message."</td>
				  </tr>";
			exit;	  
		}
	}	
}


?>