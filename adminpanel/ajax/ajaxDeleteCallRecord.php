<?php 
include_once("../../config/auto_loader.php");
header('content-type:application/json');

$response = ['success' => false, 'message' => ''];

if(!empty($_POST['id']) && !empty($_POST['call_id'])){
	$Id = intval($_POST['id']);
	$call_id = intval($_POST['call_id']);
	$id_list = intval($_POST['id_list']);
	
	
	$sql = "DELETE FROM `call_details` WHERE id= $Id";
	
	if (!$connNew) {
    $response['message'] = 'Database connection failed: ' . mysqli_connect_error();
   echo json_encode($response);
    exit;
}
	
	$result =  mysqli_query($connNew, $sql);
	
	if($result){
		$sql = 'SELECT * FROM call_details WHERE call_id = "'.$call_id.'" AND id_list_name="'.$id_list.'" ORDER BY id DESC LIMIT 0,1';
$res = mysqli_query($connNew, $sql);
		$Count = mysqli_num_rows($res);
		
		if ($Count === 0) {
    $response['message'] = 'No remaining call_details entries found after delete.';
			echo json_encode($response);
			exit;
}
		
$last_data = mysqli_fetch_array($res);

$id_user = $last_data['id_user'];
$assign_user = $last_data['assign_user_id'];
$call_ids = $last_data['call_id'];
$id = $last_data['id'];
$dated = $last_data['followup_date'];
$status = $last_data['call_status'];
		$id_list = $last_data['id_list_name'];

if($Count > 0){
	$updateCalender = 'UPDATE fs_daily_calender SET 
							id_user = "'.$id_user.'",
							assign_user_id = "'.$assign_user.'",
							visit_id = "'.$call_ids.'",
							doc_id = "'.$id.'",
							id_list_name = "'.$id_list.'",
							dated = "'.$dated.'",
							status = "'.$status.'" WHERE visit_id = "'.$call_ids.'" AND id_list_name="'.$id_list.'" AND doc_id="'.$Id.'"';

	mysqli_query($connNew, $updateCalender);
	
}else{
	$deleteCalender = 'DELETE FROM fs_daily_calender WHERE visit_id = "'.$call_id.'" AND id_list_name="'.$id_list.'" AND doc_id="'.$Id.'"';
	mysqli_query($connNew, $deleteCalender);
	
}
		
		$response['success'] = true;
	}else{
		$response['message'] = 'Database error: ' . mysqli_error($connNew);
	}
}else{
$response['message'] = 'Missing call ID.';
}

echo json_encode($response);
?>