<?php
include_once("../../config/auto_loader.php");

/*echo "<pre>";
print_r($_REQUEST);
echo "</pre>";
exit;*/


if($_REQUEST['action']=='Save'){
$sql = 'INSERT INTO '.TBL_OTHER.' SET
			`id_shop`="'.$_SESSION['shop'].'",
			`id_other_activity`="'.$_REQUEST['type'].'",
			`id_user`="'.$_SESSION['userId'].'",
			`dated` ="'.date('Y-m-d',strtotime($_REQUEST['date'])).'",
			`details`="'.$_REQUEST['details'].'",
			`KmsRun`="'.$_REQUEST['kms'].'",
			`RateKm`="'.$_REQUEST['rateKm'].'",
			`Parking`="'.$_REQUEST['park'].'",
			`entertainment`="'.$_REQUEST['entertainment'].'",
			`lunch`="'.$_REQUEST['lunch'].'",
			`Total`="'.$_REQUEST['total'].'",
			`description`="'.$_REQUEST['desc'].'",
			`id_travel_mode`="'.$_REQUEST['travelMode'].'",
			`status` ="1",
			`last_modified`="'.date('Y-m-d H:i:s').'",
			`date_created`="'.date('Y-m-d H:i:s').'",
			`id_mst_user_created_by`="'.$_SESSION['userId'].'",
			`id_mst_user_modified_by`="'.$_SESSION['userId'].'" ';

	
	$exe = mysqli_query($connNew,$sql);	
	
	if($exe){
		$insertId = mysqli_insert_id($connNew);
		$sqlRec = "SELECT * FROM ".TBL_OTHER." WHERE id_shop='".$_SESSION['shop']."' AND id='".$insertId."' ";
		$exeRec=mysqli_query($connNew,$sqlRec);
		
		if($exeRec){
			
			$data=mysqli_fetch_object($exeRec);
			echo "<tr>
					<td>".$_REQUEST['count']."</td>
					<td>".selectColumn(TBL_OTHER_ACTIVITY,'name','WHERE id="'.$data->id_other_activity.'" AND id_shop="'.$_SESSION['shop'].'" ')."</td>
					<td>".$data->description."</td>
					<td width='200px'>".$data->Total."</td>
					<td width='100px'>".$data->entertainment."</td>
					<td width='100px'>".$data->lunch."</td>
					<td><a class='fa fa-edit' href='otherEntry.php?eId=".addslashes(encryptor('encrypt',$data->id))."&action=edit&page=1' target='_blank' "."</td>
				  </tr>";
			exit;	  
		}
	}	

}
else if($_REQUEST['action']=='Edit'){
	$sql = 'UPDATE '.TBL_OTHER.' SET
			`id_other_activity`="'.$_REQUEST['type'].'",
			`description`="'.$_REQUEST['desc'].'",
			`details`="'.$_REQUEST['details'].'",
			`KmsRun`="'.$_REQUEST['kms'].'",
			`RateKm`="'.$_REQUEST['rateKm'].'",
			`id_travel_mode`="'.$_REQUEST['travelMode'].'",
			`Parking`="'.$_REQUEST['park'].'",
			`entertainment`="'.$_REQUEST['entertainment'].'",
			`lunch`="'.$_REQUEST['lunch'].'",
			`Total`="'.$_REQUEST['total'].'",
			`last_modified`="'.date('Y-m-d H:i:s').'",
			`id_mst_user_modified_by`="'.$_SESSION['userId'].'" WHERE id_shop="'.$_SESSION['shop'].'" AND id="'.$_REQUEST['eId'].'" ';
	
	$exe = mysqli_query($connNew,$sql);	
	
	if($exe){
		$insertId = $_REQUEST['eId'];
		$sqlRec = "SELECT * FROM ".TBL_OTHER." WHERE id_shop='".$_SESSION['shop']."' AND id='".$insertId."' ";
		$exeRec=mysqli_query($connNew,$sqlRec);
		
		if($exeRec){
			
			$data=mysqli_fetch_object($exeRec);
			echo "<tr>
					<td>".$_REQUEST['count']."</td>
					<td>".selectColumn(TBL_OTHER_ACTIVITY,'name','WHERE id="'.$data->id_other_activity.'" AND id_shop="'.$_SESSION['shop'].'" ')."</td>
					<td>".$data->description."</td>
					<td width='200px'>".$data->Total."</td>
					<td width='100px'>".$data->entertainment."</td>
					<td width='100px'>".$data->lunch."</td>
					<td><a class='fa fa-edit' href='otherEntry.php?eId=".addslashes(encryptor('encrypt',$data->id))."&action=edit&page=1' target='_blank' "."</td>
				  </tr>";
			exit;	  
		}
	}	
}

?>