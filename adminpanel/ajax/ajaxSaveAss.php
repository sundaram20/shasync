<?php  include_once("../../config/auto_loader.php");
header('Content-Type: application/json');

/*echo "<pre>";
print_r($_SESSION);
echo "-------------request--------<br>";
print_r($_REQUEST);
echo "</pre>";
exit;*/


try{
	$call_id = isset($_REQUEST['call_id']) ? intval($_REQUEST['call_id']) : 0;
	$list_id = isset($_REQUEST['list_id']) ? intval($_REQUEST['list_id']) : 0;
	$format = isset($_REQUEST['format']) ? addslashes($_REQUEST['format']) : '';
	
	$extra_data = selectColumn('call_details','extra_data', "WHERE call_id='".$call_id."' ORDER BY id desc LIMIT 1");
	//$extra_data = json_encode($extraDataArray, JSON_UNESCAPED_UNICODE);
	//$extra_data = $db->real_escape_string($extra_data);
	$assigned_user = isset($_REQUEST['assign_followup_user_id']) ? mysqli_real_escape_string($connNew, 	trim($_REQUEST['assign_followup_user_id'])) : '';
	$call_status = isset($_REQUEST['followupstatus']) ? mysqli_real_escape_string($connNew, trim($_REQUEST['followupstatus'])) : '';
	$internal_remark = isset($_REQUEST['followup_description']) ? mysqli_real_escape_string($connNew, trim($_REQUEST['followup_description'])) : '';
	$call_remark = isset($_REQUEST['remark']) ? mysqli_real_escape_string($connNew, trim($_REQUEST['remark'])) : '';
	//$followup_date = isset($_REQUEST['followup_date']) ? mysqli_real_escape_string($connNew, trim($_REQUEST['followup_date'])) : '';
	
	$close_type = isset($_REQUEST['close_type']) ? mysqli_real_escape_string($connNew, trim($_REQUEST['close_type'])) : '';
	
	$close_remark = $close_type.' - '.$call_remark ;
	
	if($call_status == 1){
		$remark = $call_remark;
	}else if($call_status == 0){
		$remark = $close_remark;
	}
	
	
	$followup_date = isset($_REQUEST['followup_date']) ? date('Y-m-d', strtotime(str_replace('/', '-', trim($_REQUEST['followup_date'])))) : '';
$followup_date = mysqli_real_escape_string($connNew, $followup_date);
	
    $created_at = date('Y-m-d H:i:s'); // Current timestamp for generation_date
    $id_shop = isset($_SESSION['shop']) ? mysqli_real_escape_string($connNew, trim($_SESSION['shop'])) : '';
    $id_user = isset($_SESSION['userId']) ? intval($_SESSION['userId']) : 0;

	if ($call_id <= 0) {
        throw new Exception('Missing or invalid call ID');
    }
    if (empty($id_shop) || $id_user <= 0) {
        throw new Exception('Invalid session data');
    }
    
	
	$insertDetails = "INSERT INTO `call_details` SET
                      `call_id` = '$call_id',
                      `id_shop` = '$id_shop',
                      `id_user` = '$id_user',
                      `assign_user_id` = '$assigned_user',
					  `id_list_name` = '$list_id',
					  `format_type` = '$format',
					  `extra_data` = '".addslashes($extra_data)."',
                      `call_remark` = '$remark',
                      `internal_remark` = '$internal_remark',
                      `call_status` = '$call_status',
                      `followup_date` = '$followup_date',
                      `created_at` = '$created_at'";

    if (mysqli_query($connNew, $insertDetails)) {
		
		$call_details_id = mysqli_insert_id($connNew);
		
		$resState = executeSql("SELECT * from `fs_daily_calender` where `id_shop` = '".addslashes($_SESSION['shop'])."' and   `type` = '7'  and  `visit_id` = '".addslashes($call_id)."' and `id_list_name`='".$list_id."'");
				
			if(num_rows($resState) > 0){
			//Update
				
				$insertCalendar = "UPDATE `".TBL_DAILY_CALENDER."` SET 
				 		`enquiry_details`='1',
				 		`type`='7',
						`id_shop` = '".addslashes($_SESSION['shop'])."',
						`id_user`='".addslashes($_SESSION['userId'])."',
						`assign_user_id` = '".$assigned_user."',
						`doc_id` ='".addslashes($call_details_id)."',
						`visit_id` ='".addslashes($call_id)."',
						`id_list_name` = '$list_id',
						`dated`='".$followup_date."',
						
						`status` = '".$call_status."' WHERE visit_id = '".$call_id."' AND id_list_name='".$list_id."'";
				mysqli_query($connNew, $insertCalendar);
				
			}else{
			//Insert=
			
				$insertCalendar = "INSERT INTO `".TBL_DAILY_CALENDER."` SET 
				 		`enquiry_details`='1',
				 		`type`='7',
						`id_shop` = '".addslashes($_SESSION['shop'])."',
						`id_user`='".addslashes($_SESSION['userId'])."',
						`assign_user_id` = '".$assigned_user."',
						`doc_id` ='".addslashes($call_details_id)."',
						`visit_id` ='".addslashes($call_id)."',
						`id_list_name` = '$list_id',
						`dated`='".$followup_date."',
						
						`status` = '".$call_status."'";
				mysqli_query($connNew, $insertCalendar);
				
			}
		
		
		
		
		
		
	
		
		
        echo json_encode(['status' => 'success', 'message' => 'Call details added successfully', 'call_id' => $call_id]);
    } else {
        throw new Exception('Insert failed: ' . mysqli_error($connNew));
    }
}catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>

