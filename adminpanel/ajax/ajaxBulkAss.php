<?php
include_once("../../config/auto_loader.php");
header('content-type:application/json');

$response = ['success' => false, 'message' => ''];

$ids = $_POST['ids'] ?? [];
$data = $_POST['data']??[];

$status = $data['dropdown1'];
$assignedUser = $data['dropdown2'];
$remark = $data['textInput'];
$format_type = $data['format_type'];
$lists = $data['lists'];
$current_list_id =$data['current_list_id'];


$created_at = date('Y-m-d H:i:s'); // Current timestamp for generation_date
    $id_shop = isset($_SESSION['shop']) ? mysqli_real_escape_string($connNew, trim($_SESSION['shop'])) : '';
    $id_user = isset($_SESSION['userId']) ? intval($_SESSION['userId']) : 0;


if(!empty($_POST['ids']) && !empty($lists) ){

foreach ($ids as $callId) {
	foreach($lists as $list){
		
		 $call_id = intval($callId);
            $list_id = intval($list);
		
		//$extra_data = selectColumn('call_details','extra_data',"WHERE call_id='".$callId."' ORDER BY id desc LIMIT 1");
		
		$extra_data_raw = selectColumn(
    'call_details',
    'extra_data',
    "WHERE call_id='".intval($callId)."' ORDER BY id desc LIMIT 1"
);
		$extra_data_raw = preg_replace('/[\x00-\x1F\x7F]/u', ' ', $extra_data_raw);
		$extra_data_array = json_decode($extra_data_raw, true);

// fallback if old JSON is broken
if (json_last_error() !== JSON_ERROR_NONE) {
    $extra_data_array = [];
}
		if (isset($extra_data_array['mobile'])) {
    // Remove line breaks, tabs, extra spaces
    $extra_data_array['mobile'] = preg_replace('/\s+/', ' ', $extra_data_array['mobile']);
}

$extra_data = json_encode(
    $extra_data_array,
    JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE
);
		
		$extra_data = mysqli_real_escape_string($connNew, $extra_data);


    $sql = "INSERT INTO `call_details` SET
            call_status = '".addslashes($status)."',
			id_user = '".addslashes($_SESSION['userId'])."',
			id_shop = '$id_shop',
                assign_user_id = '".addslashes($assignedUser)."',
               internal_remark = '".addslashes($remark)."',
			   id_list_name = '".addslashes($list_id)."',
			   format_type = '".$format_type."',
			   extra_data = '".$extra_data."',
				created_at = '$created_at',
             call_id = '".addslashes($call_id)."'";

    
	$result = executeSql($sql);
		$call_details_id = mysqli_insert_id($connNew);
	
		if($result){
			if($list_id == $current_list_id){
				$resState = executeSql("SELECT * from `fs_daily_calender` where `id_shop` = '".addslashes($_SESSION['shop'])."' and   `type` = '7'  and  `visit_id` = '".addslashes($call_id)."' and `id_list_name`='".$current_list_id."'");
				
				$res = mysqli_fetch_assoc($resState);
				$followup_date = $res['dated'];
				
				if(num_rows($resState) > 0){
				$insertCalendar = "UPDATE `".TBL_DAILY_CALENDER."` SET 
				 		`enquiry_details`='1',
				 		`type`='7',
						`id_shop` = '".addslashes($_SESSION['shop'])."',
						`id_user`='".addslashes($_SESSION['userId'])."',
						`assign_user_id` = '".addslashes($assignedUser)."',
						`doc_id` ='".addslashes($call_details_id)."',
						`visit_id` ='".addslashes($call_id)."',
						`id_list_name` = '$current_list_id',
						`dated`='".$followup_date."',
						
						`status` = '".$status."' WHERE visit_id = '".$call_id."' AND id_list_name='".$list_id."'";
				mysqli_query($connNew, $insertCalendar);
				}
				
			}else{
				$insertCalendar = "INSERT INTO `".TBL_DAILY_CALENDER."` SET 
				 		`enquiry_details`='1',
				 		`type`='7',
						`id_shop` = '".addslashes($_SESSION['shop'])."',
						`id_user`='".addslashes($_SESSION['userId'])."',
						`assign_user_id` = '".addslashes($assignedUser)."',
						`doc_id` ='".addslashes($call_details_id)."',
						`visit_id` ='".addslashes($call_id)."',
						`id_list_name` = '$list_id',
						
						
						`status` = '".$status."'";
				mysqli_query($connNew, $insertCalendar);
			}
			
		}
	
	}
}
if($result){
	$response['success'] = true;
	}else{
	$response['message'] = 'Database error: ' . mysqli_error($connNew);
	}

}else{
	$response['message'] = 'No Id Found Found For Assigning!';

}
echo json_encode($response);

?>
